<?php
/**
 * Created by JetBrains PhpStorm.
 * User: vadym
 * Date: 11/25/13
 * Time: 7:17 PM
 * To change this template use File | Settings | File Templates.
 */
class Init extends \AbstractController {
    public $addon_obj;
    function init() {
        parent::init();
        $this->api->addHook('localizeString',array($this,'_()'));

    }
    function _($str) {
        exit($str);
    }
}