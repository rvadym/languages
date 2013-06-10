<?php
namespace x_ls;
class View_LanguageSwitcher extends \View {
    public $controller;
    function init() {
        parent::init();
        
        $source=array();
        foreach ($this->languages as $lang) {
            $s=array(
                'href'=>$this->getRedirUrl().$lang,
                'name'=>$lang,
                );
            ($lang==$this->default_language)?$s['class']='default':$s['class']='';
            $source[]=$s;
        }
        
        $lister = $this->add('Lister',null,'lister',array('lister/switcher'));
        $lister->setSource($source);
    }
    private function getRedirUrl() {
        return $this->controller->getRedirUrl();
    }
    
    function defaultTemplate() {
        return array('view/switcher');
    }
}
