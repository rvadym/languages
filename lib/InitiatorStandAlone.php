<?php
/**
 * Created by JetBrains PhpStorm.
 * User: vadym
 * Date: 11/25/13
 * Time: 7:17 PM
 * To change this template use File | Settings | File Templates.
 */

/* ***********************************************************
 *   For stand alone (non afiletoolkit-bandle) compatibility
 */

namespace rvadym\languages;
class InitiatorStandAlone extends Initiator {
    function init() {
        parent::init();
    }

    private $dummy_addon_obj_name = 'rvadym_languages';
    private function getAddonName() {
        return $this->dummy_addon_obj_name;
    }
}