<?php
/**
 * Created by JetBrains PhpStorm.
 * User: vadym
 * Date: 11/25/13
 * Time: 7:17 PM
 * To change this template use File | Settings | File Templates.
 */
namespace rvadym\languages;
class Initiator extends \Controller_Addon {

    public $namespace      = 'rvadym\languages';
    public $api_var        = 'rvadym_languages';
    public $addon_name     = 'Agile Toolkit Language Addon';
    public $addon_private_locations = array(
        'docs'      => 'docs',
        'php'       => 'lib',
        'page'      => 'page',
        'template'  => 'templates',
    );
    public $addon_public_locations  = array(
        'js'     => 'js',
        'css'    => 'css',
    );


    public $configs = array();
    public $addon_obj;
    public $view_class;
    private $translations = false;
    function init() {
        parent::init();
        $this->setConfigs();
        $this->addLocations();
        $this->addTranslator();
        $this->api->addHook('localizeString',array($this,'_'));
    }
    /* implement this function in your class if you need this */
    protected function addLocations() {}
    function _($trash,$string) {
        // do not translate if only spases
        if (trim($string) == '') return $string;
        return $this->translations->__($string);
    }
    private function setConfigs() {
        $default_config = array(
            'switcher_type'=>'session',
            'languages'=>array('en'),
        );
        $configs = $this->api->getConfig($this->getAddonName(),$default_config);
        $configs['initiator'] = $this;
        if($this->view_class){
            $configs['view_class'] = $this->view_class;
        }

        // TODO check all configs

        $this->configs = $configs;
    }
    private function addTranslator() {
        if (!$this->translations) {
            switch ($this->configs['switcher_type']) {
                case 'session';
                    $class = 'Controller_SessionLanguageSwitcher';
                    break;
                case 'url';
                    $class = 'Controller_UrlLanguageSwitcher';
                    break;
                default:
                    throw $this->exception("Language switcher type can be 'session' or 'url'. Current config type is ". $this->configs['switcher_type'].'.');
            }
            $class_with_namespace = __NAMESPACE__ . DIRECTORY_SEPARATOR . $class;
            $this->translations = $this->add($class_with_namespace, $this->configs);
            if ($this->configs['store_type'] == 'db') {
                $this->translations->setModel($this->configs['model']);
            }
        }
    }
    public function getAddonName() {
        return $this->addon_obj->get('name');
    }
    public function getTranslator() {
        return $this->translations;
    }
}