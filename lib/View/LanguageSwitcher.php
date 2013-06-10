<?php
namespace x_ls;
class View_LanguageSwitcher extends \View {
    function init() {
        parent::init();
        $this->showSwitcher();
    }
    function showSwitcher() {
        // css
        $this->api->jui->addStaticStylesheet('switcher');

        $source=array();
        foreach ($this->controller->languages as $lang) {
            $s=array(
                'href'=>$this->controller->getRedirUrl().$lang,
                'name'=>$lang,
            );
            $s['class']=($lang==$this->controller->getLanguage())?'current':'';
            $source[]=$s;
        }

        $lister = $this->add('x_ls\Lister_LanguageSwitcher',null,'lister');
        $lister->setSource($source);
    }
    function defaultTemplate() {
        return array('view/switcher');
    }
}
