<?php
/**
 * Created by JetBrains PhpStorm.
 * User: vadym
 * Date: 6/10/13
 * Time: 4:55 PM
 * To change this template use File | Settings | File Templates.
 */
namespace rvadym\language_switcher;
class Lister_LanguageSwitcher extends \CompleteLister {
    function init() {
        parent::init();
    }
    function formatRow() {
        if ($this->current_row['class'] == 'current') {
            $this->current_row_html['element'] = '<span>'.$this->current_row['name'].'</span>';
        } else {
            $this->current_row_html['element'] = '<a href="'.$this->current_row['href'].'">'.$this->current_row['name'].'</a>';
        }
    }
    function defaultTemplate() {
        return array('lister/switcher');
    }
}