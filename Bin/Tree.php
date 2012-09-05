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


            $this->_recursiveExtract($protocol->getIterator());


            cout(\Hoa\Console\Chrome\Text::columnize($this->_extractmapping));
            return;
        }

        private function _recursiveExtract($iterator, $parent = 'hoa://')
        {
            foreach ($iterator as $name => $it) {

                $current = $parent . $name . '/';

                $this->_extractmapping[] = array(
                    $current,
                    resolve($current)
                );
                if ($it instanceof \Hoa\Core\Protocol) {
                    $this->_recursiveExtract($it->getIterator(), $current);
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
