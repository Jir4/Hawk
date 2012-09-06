<?php
namespace {

}

namespace Hoathis\Hawk\Bin {


    class Fork extends \Hoa\Console\Dispatcher\Kit
    {

        protected $options = array(
            array('help', \Hoa\Console\GetOption::NO_ARGUMENT, 'h'),
            array('help', \Hoa\Console\GetOption::NO_ARGUMENT, '?'),
            array('inverse', \Hoa\Console\GetOption::NO_ARGUMENT, 'i'),
            array('version', \Hoa\Console\GetOption::NO_ARGUMENT, 'v'),
        );

        public function main()
        {
            $permute = false;


            while (false !== $c = $this->getOption($v)) switch ($c) {
                case 'i':
                    $permute = true;
                    break;
                case 'v':
                    cout('Hawk Installation by thehawk_ , version 0.01 alpha');
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
            $this->parser->listInputs($packageName);

            if (null === $packageName)
                return $this->usage();

            $packageName = ucfirst($packageName);

            $core = \Hoa\Core\Core::getInstance();

            $destination = realpath($core->getParameters()->getKeyword('root') . '/hoathis/') . '/' . $packageName;

            $source = 'hoa://Data/Library/' . $packageName;

            if ($permute === true) {
                $s = $source;
                $source = $destination;
                $destination = $s;
            }

            if (!is_dir($source))
                return cout('Its not a valid package !');


            $result = $this->_copyDirectory($source, $destination);


            if (!$result)
                return count('An error on the copy has been encounter !');
            else
                return cout($source . ' > ' . $destination);

            return;
        }

        private function _copyDirectory($source, $destination)
        {
            if (!is_dir($destination))
                mkdir($destination);

            $scan = scandir($source);
            foreach ($scan as $elmt) {
                if ($elmt[0] == '.')
                    continue;
                else
                    if (is_dir($source . '/' . $elmt))
                        $this->_copyDirectory($source . '/' . $elmt, $destination . '/' . $elmt);
                    else
                        if (!file_exists($destination . '/' . $elmt))
                            if (!copy($source . '/' . $elmt, $destination . '/' . $elmt))
                                return false;

            }

            return true;
        }

        public function usage()
        {
            cout('Usage   : myapp  hoathis hawk:install <options> packageName');
            cout('Its for copy a local library on global installation');
            cout('Options :');
            cout($this->makeUsageOptionsList(array(
                'help' => 'This help.',
                'version' => 'Application version.',
                'inverse' => 'for copy global library on local installation',
                ''
            )));

            return;
        }
    }

}
