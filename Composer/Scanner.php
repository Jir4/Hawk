<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Julien
 * Date: 30/08/12
 * Time: 12:09
 * To change this template use File | Settings | File Templates.
 */
namespace Hoathis\Hawk\Composer {
    /**
     *
     */
    class Scanner
    {
        /**
         * @var array
         */
        private $_depends = array();
        /**
         * @var null
         */
        private $_uri = null;
        /**
         * @var array
         */
        private $_library = array();

        /**
         * @param string $uri
         */
        public function __construct($uri = 'Central/Hoa')
        {
            $this->setUri($uri);


        }

        /**
         * Va scanner toutes les librairies , s'arrète s'il y a déja un module composer.json
         */
        public function scan()
        {
            $libray = scandir($this->getUri());
            foreach ($libray as $id => $lib) {
                if ($lib == '.' or $lib == '..' or file_exists($this->getUri() . '/' . $lib . '/composer.json')) {
                    if ($lib[0] != '.')
                        cout($lib . ' has ever an composer loader');
                    unset($libray[$id]);
                } else {
                    $this->addLibrary($lib);
                    $this->_scanRecusive($this->_uri . '/' . $lib, $lib);
                }
            }
        }

        /**
         * Ouvre tous les fichiers récursivement pour trouver les imports
         * @param $libraryDirectory
         * @param $libraryName
         */
        private function _scanRecusive($libraryDirectory, $libraryName)
        {
            if (is_dir($libraryDirectory)) {
                foreach (scandir($libraryDirectory) as $e) {
                    if ($e != '..' && $e != '.')
                        if (is_dir($libraryDirectory . '/' . $e))
                            $this->_scanRecusive($libraryDirectory . '/' . $e, $libraryName);
                        else
                            $this->_scanFile($libraryDirectory . '/' . $e, $libraryName);
                }
            }
        }

        /**
         * Scan chaque fichier a la recherche des imports et les stocks
         * @param $uri
         * @param $libraryName
         */
        private function _scanFile($uri, $libraryName)
        {
            if (!file_exists($uri)) {
                cout($uri . ' is not accessible by the system !');
                return;
            }

            $this->addDepends($libraryName, 'Core');
            $data = file_get_contents($uri);

            preg_match_all("#import\('([[:alpha:]]+)#", $data, $m);
            foreach ($m[1] as $lib) {
                if ($libraryName != $lib)
                    $this->addDepends($libraryName, $lib);
            }


        }

        /**
         * Un seul enregistrement par librairies , pour éviter le bronx :D
         * @param $library
         * @param $elmt
         */
        public function addDepends($library, $elmt)
        {
            if (array_key_exists($library, $this->_depends)) {
                if (!in_array($elmt, $this->_depends[$library]))
                    $this->_depends[$library][] = $elmt;
            } else {
                $this->_depends[$library][] = $elmt;
            }
        }

        /**
         * Pour obtenir toutes les dépendances
         * @return array
         */
        public function getDepends()
        {
            return $this->_depends;
        }

        /**
         * Pour obtenir les dépendances de chaque librarie, utilisé dans composer
         * @param $library
         * @return mixed
         */
        public function getDepend($library)
        {
            return $this->_depends[$library];
        }

        /**
         * @param $uri
         */
        public function setUri($uri)
        {
            $this->_uri = $uri;
        }

        /**
         * @return null
         */
        public function getUri()
        {
            return $this->_uri;
        }

        /**
         * On liste toutes les librairies
         * @param $library
         */
        public function addLibrary($library)
        {
            if (!in_array($library, $this->_library))
                $this->_library[] = $library;
        }

        /**
         * @return array
         */
        public function getLibraries()
        {
            return $this->_library;
        }

    }
}
