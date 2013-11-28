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
    function init() {
        parent::init();
        $this->configs = $this->api->getConfigs($this->addon_obj->name,array());
        $this->api->addHook('localizeString',array($this,'_()'));

    }
    function _($str) {
        exit($str);
    }
}