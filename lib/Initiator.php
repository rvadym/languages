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

    public $with_pages = true;


    public $configs = array();
    public $view_class;
    private $translations = false;
    function init() {
        parent::init();
        $this->setConfigs();
        $this->addTranslator();
        $this->api->addHook('localizeString',array($this,'_'));
    }
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

                //Fill table 'language' from $this->configs['languages'] if it has no data.
                $m_languages = $this->add('rvadym/languages/Model_Language');
                if(!$m_languages->count()->getOne()){
                    $m_languages->setDefaultData($this->configs['languages']);
                }

                $m= ($this->configs['model'])?$this->configs['model']:'rvadym/languages/Model_Translation';
                $this->translations->setModel($m);
            }
        }
    }
    public function getAddonName() {
        return $this->getOption('name');
    }
    public function getTranslator() {
        return $this->translations;
    }

    //get() method for stdClass object from json
    public function getOption($name='',$object=null){

        //default values
        if(!$object){
            $object=$this->addon_obj;
        }

        //search object option $name
        if(array_key_exists($name,get_object_vars($object))){
            return $object->$name;
        }else{
            exit('object option "'.$name.'" is not found');
        }
    }

}