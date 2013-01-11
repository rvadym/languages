<?php
/**
 * Created by JetBrains PhpStorm.
 * User: vadym
 * Date: 1/11/13
 * Time: 9:28 AM
 * To change this template use File | Settings | File Templates.
 */
namespace x_ls;

class Controller_LanguageSwitcher extends \Controller {
    public $file_extension = 'yml';
    public $languages = array();
    public $default_language = false;
    public $translation_dir_path=false;
    public $switcher_tag = 'x_ls_panel';
    function init() {
        parent::init();
        $this->check();
        $this->api->x_ls = $this;

        $this->l = $this->checkLanguage();
        $this->redir_url=$this->getUrl();
        $this->addLangSwitcher();
    }
    function __($string){
        if (!$this->translated) return $string;
        if (array_key_exists($string,$this->translated)) {
            return $this->translated[$string];
        }
        return '-'.$string.'-';
    }
    private $translated = false;
    function translate() {
        $files = scandir($this->translation_dir_path);
        foreach ($files as $file) {
            if ($file != $this->l.'.'.$this->file_extension) continue;
            $this->translated = \Spyc::YAMLLoad($this->translation_dir_path.'/'.$file);
        }
        return $this;
    }
    function defaultTemplate() {
		// add add-on locations to pathfinder
		$this->l = $this->api->locate('addons',__NAMESPACE__,'location');
		$addon_location = $this->api->locate('addons',__NAMESPACE__);
		$this->api->pathfinder->addLocation($addon_location,array(
//			'js'=>'templates/js',
//			'css'=>'templates/css',
//            'template'=>'templates',
		))->setParent($this->l);
        parent::defaultTemplate();
    }
    private function check() {
        if (count($this->languages)==0) throw $this->exception('Provide language set.');
        if (!$this->default_language) $this->default_language = $this->languages[0];
        if (!$this->translation_dir_path) $this->translation_dir_path = $this->api->pm->base_directory.'translations';
        require_once __DIR__."/../../spyc/spyc.php";
    }
    private function addLangSwitcher() {
        $v = $this->api->add('View',null,'lang_switcher');
        foreach ($this->languages as $lang) {
            $v->add('View')->addStyle('float','right')->setHTML('&nbsp;<a href="'.$this->redir_url.$lang.'">'.$lang.'</a>&nbsp;');
        }
    }
    private function checkLanguage() {
        if ($session_lang = $_GET['user_panel_lang']) {
            $this->memorize('user_panel_lang',$_GET['user_panel_lang']);
            $this->api->redirect();
        }
        if ($session_lang = $this->recall('user_panel_lang')) return $session_lang;
        return $this->default_language;
    }
    private function getUrl() {
        $url = $_SERVER["REQUEST_URI"];
        str_replace('#','',$url);
        $url .= ((substr_count($url,'?')?'&user_panel_lang=':'?user_panel_lang='));
        return $url;
    }
}