<?php
/**
 * Created by Vadym Radvansky
 * Date: 2/11/14 5:54 PM
 */
namespace rvadym\languages;
class page_index extends \Page_AddonIndex {
    function init() {
        parent::init();
        $this->api->title = 'Languages addon - ' . $this->api->title;
        $this->api->layout->template->set('page_title','Languages addon');

        $this->add('View')->set('This is good place to describe your addon ;-)');
    }
}