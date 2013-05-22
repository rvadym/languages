<?php
//namespace x_ls;
class View_LanguageSwitcher extends \View {
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
        $url = $_SERVER["REQUEST_URI"];
        $url .= ((substr_count($url,'?')?'&user_panel_lang=':'?user_panel_lang='));
        return $url;
    }
    
    function defaultTemplate() {
        return array('view/switcher');
    }
}
