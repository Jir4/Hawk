<?php
namespace Hoathis\Hawk\Bin {


    class Tree extends \Hoa\Console\Dispatcher\Kit
    {

        protected $options = array(
            array('help', \Hoa\Console\GetOption::NO_ARGUMENT, 'h'),
            array('help', \Hoa\Console\GetOption::NO_ARGUMENT, '?'),
            array('version', \Hoa\Console\GetOption::NO_ARGUMENT, 'v'),
        );

        private $_extractmapping = array();

        public function main()
        {

            $registry = \Hoa\Registry\Registry::set('foo', $this);

            var_dump(resolve('hoa://Library/Registry#foo'));

            while (false !== $c = $this->getOption($v)) switch ($c) {


                case 'v':
                    cout('Hawk Protocol Tree by thehawk_ , version 0.01 alpha');
                    return;
                    break;
                case 'h':
                case '?':
                    return $this->usage();
                    break;

                case '__ambiguous':
                    $this->resolveOptionAmbiguity($v);
                    break;
            }

            cout('Hoa Protocol Tree');
            cout();

            $protocol = \Hoa\Core\Core::getProtocol();


            $this->_recursiveExtract($protocol);


            cout(\Hoa\Console\Chrome\Text::columnize($this->_extractmapping));
            return;
        }

        private function _recursiveExtract($iterator, $parent = 'hoa://', $lvl = -1)
        {
            ++$lvl;
            foreach ($iterator as $name => $it) {

                $current = $parent . $name . '/';

                $this->_extractmapping[] = array(
                    str_repeat('  ', $lvl) . $name,
                    resolve($current)
                );
                if ($it instanceof \Hoa\Core\Protocol) {
                    $this->_recursiveExtract($it, $current, $lvl);
                }

            }

        }

        public function usage()
        {

            cout('Usage   : myapp  hoathis hawk:tree <options>');
            cout('Options :');
            cout($this->makeUsageOptionsList(array(
                'help' => 'This help.',
                'version' => 'Application version.'
            )));

            return;
        }
    }

}
