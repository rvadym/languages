<?php
namespace rvadym\languages;
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
                'href'=>$this->controller->getChangeLangUrl($lang),
                'name'=>$lang,
            );
            $s['class']=($lang==$this->controller->getLanguage())?'current':'';
            $source[]=$s;
        }

        $lister = $this->add('rvadym\languages\Lister_LanguageSwitcher',null,'lister');
        $lister->setSource($source);
    }
    function defaultTemplate() {
        return array('view/switcher');
    }
}
