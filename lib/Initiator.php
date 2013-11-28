<?php
/**
 * Created by JetBrains PhpStorm.
 * User: vadym
 * Date: 11/25/13
 * Time: 7:17 PM
 * To change this template use File | Settings | File Templates.
 */
namespace rvadym\languages;
class Initiator extends \AbstractController {
    public $configs = array();
    public $addon_obj;
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
        $configs = $this->api->getConfig($this->addon_obj->name,array());

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
            $this->translations = $this->add('rvadym\\languages\\'.$class,$this->configs);
            if ($this->configs['store_type'] == 'db') {
                $this->translations->setModel($this->configs['model']);
            }
        }
    }
}