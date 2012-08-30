<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Julien
 * Date: 30/08/12
 * Time: 12:09
 * To change this template use File | Settings | File Templates.
 */
namespace Hoathis\Hawk {
    /**
     *
     */
    class Composer
    {
        /**
         * @var Scanner|null
         */
        private $_scanner = null;

        /**
         * @var array
         */
        private $_info = array();

        /**
         * @var bool
         */
        private $_interactive = true;

        /**
         * On passe notre instance de scanner
         * @param Scanner $_this
         */
        public function __construct(Scanner $_this)
        {
            $this->_scanner = $_this;
        }

        /**
         * On génére le contenu du fichiers , et on pose les questions ... en toutes assez sommaires mais hein :D
         *
         */
        public function generate()
        {
            $library = $this->_scanner->getLibraries();
            foreach ($library as $lib) {
                $uriOutput = './Output/' . $lib . '.composer.json';
                $this->set($lib, 'name', 'hoa/' . strtolower($lib));
                $this->set($lib, 'description', 'The Hoa\\' . ucfirst(strtolower($lib)) . ' library');
                $this->set($lib, 'type', 'library');
                $this->set($lib, 'keywords', 'foo,bar');
                $this->set($lib, 'homepage', 'http://hoa-project.net/');
                $this->set($lib, 'license', 'BSD-3-Clause');
                $this->_set($lib, 'authors', array(
                    array(
                        'name' => 'Ivan Enderlin',
                        'email' => 'ivan.enderlin@hoa-project.net'
                    ),
                    array(
                        'name' => 'Hoa community',
                        'homepage' => 'http://hoa-project.net/'
                    )
                ));
                $this->_set($lib, 'support', array(
                    'email' => 'ivan.enderlin@hoa-project.net',
                    'forum' => 'http://forum.hoa-project.net',
                    'irc' => 'irc://irc.freenode.org/hoaproject',
                    'source' => 'http://git.hoa-project.net/'
                ));

                $require = array();
                foreach ($this->_scanner->getDepend($lib) as $depend) {
                    $require['hoa/' . strtolower($depend)] = 'master';
                }

                $this->_set($lib, 'require', $require);
                $this->_generate($lib, $this->_scanner->getUri() . '/' . $lib . '/composer.json');
            }
        }

        /**
         * On ajoute un paramètre qui a besoin d'etre intéractif
         * @param $key
         * @param $question
         * @param $default
         */
        private function set($lib, $key, $default = null)
        {
            if ($this->getInteractive() === true) {
                $q = '[Hoa/' . $lib . '] ' . $key . ' ?';
                $this->_set($lib, $key, tell($q, $default));
            } else {
                cout('[Hoa/' . $lib . '] ' . $key . '? : ' . $default);
                $this->_set($lib, $key, $default);
            }
        }

        /**
         * On obtient les infos / librairies
         * @param $lib
         * @return mixed
         */
        private function get($lib)
        {
            if (array_key_exists($lib, $this->_info))
                return $this->_info[$lib];
        }

        /**
         * on ajoute un paramètre sans intéractivité
         * @param $lib
         * @param $key
         * @param $default
         */
        private function _set($lib, $key, $default)
        {
            $this->_info[$lib][$key] = $default;
        }


        /**
         * On construit le fichiers et les informations de Keywords
         * @param $data
         */
        private function _generate($lib, $filename)
        {
            $data = $this->get($lib);
            if (array_key_exists('keywords', $data)) {
                if ($data['keywords'] == '')
                    $data['keywords'] = array();
                else
                    $data['keywords'] = explode(',', $data['keywords']);
            }
            $json = json_encode($data, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
            file_put_contents($filename, $json);
        }

        /**
         * On définie si on veut les valeurs par défault ou non :)
         * @param boolean $interactive
         */
        public function setInteractive($interactive)
        {
            if (is_bool($interactive)) ;
            $this->_interactive = $interactive;
        }

        /**
         * @return boolean
         */
        public function getInteractive()
        {
            return $this->_interactive;
        }

    }
}
