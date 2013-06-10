<?php
/**
 * Created by JetBrains PhpStorm.
 * User: vadym
 * Date: 1/11/13
 * Time: 9:28 AM
 * To change this template use File | Settings | File Templates.
 */

/*

Usage:
add to you Frontend


    // translations
    public $x_ls = false;
    function _($string) {
        if (!$this->x_ls) {
            $this->add('x_ls/Controller_LanguageSwitcher',array(
                'languages'=>array('en','ru','lv','ua'),
                'default_language'=>'en',
            ));
            //$this->x_ls->setModel('Translations');
        }
        return $this->x_ls->__($string);
    }


and translate strings by

$this->api->_('ane text to translate')


 */
namespace x_ls;
require_once __DIR__."/../../spyc/spyc.php";

class Controller_LanguageSwitcher extends \Controller {
    public $file_extension         = 'yml';
    public $languages              = array();
    public $default_language       = false;
    public $translation_dir_path   = false;
    public $switcher_tag           = 'x_ls_panel';
    public $view_class             = 'x_ls/View_LanguageSwitcher';
    public $var_name               = 'user_panel_lang';
    public $to_same_page           = true;

    function init() {
        parent::init();

		// add add-on locations to pathfinder
		$this->loc = $this->api->locate('addons',__NAMESPACE__,'location');
		$addon_location = $this->api->locate('addons',__NAMESPACE__);
		$this->api->pathfinder->addLocation($addon_location,array(
            'php'=>'lib',
            'template'=>'templates',
            'css'=>'templates/css',
		))->setParent($this->loc);

        $this->api->x_ls = $this;
        if (!$this->translation_dir_path) $this->translation_dir_path = $this->api->pm->base_directory.'translations';

        $this->switchLanguageIfRequired();
        $this->getLanguage();
        $this->addLangSwitcher();
    }

    ////////  translation  ////////
    private $translations = array();
    function __($string){
        if (count($this->translations)==0) {
            $this->translate();
        }

        if (array_key_exists($string,$this->translations)) {
            return $this->translations[$string];
        } else {
            return (($this->api->getConfig('x_ls/debug',false))?'☺':'').$string;
        }
    }
    private function translate() {
        if($this->model) {
            $t_trans = $this->model->getRows();
            foreach ($t_trans as $t) {
                if (!array_key_exists($this->l,$t)) continue;
                $this->translations[$t['value']] = $t[$this->l];
            }
        } else {
            $files = scandir($this->translation_dir_path);
            foreach ($files as $file) {
                if ($file != $this->l.'.'.$this->file_extension) continue;
                $this->translations = \Spyc::YAMLLoad($this->translation_dir_path.'/'.$file);
            }
        }
        return $this;
    }

    // do not use directly, use $this->getLanguage() instead.
    private $l = false;
    function getLanguage(){
        if (count($this->languages)==0) throw $this->exception('Provide language set.');
        if ($this->l) {
            // do nothing
        } else if ($this->recallLang()) {
            $this->l = $this->recall($this->var_name);
        } else if ($this->default_language) {
            $this->l = $this->default_language;
            $this->memorizeLang($this->l);
        } else {
            $this->l = $this->languages[0];
            $this->memorizeLang($this->l);
        }
        return $this->l;
    }
    private function memorizeLang($lang) {
        $this->memorize($this->var_name,$lang);
    }
    private function recallLang() {
        return $this->recall($this->var_name);
    }
    private function switchLanguageIfRequired() {
        if ($_GET[$this->var_name]) {
            $this->memorizeLang($_GET[$this->var_name]);
            if ($this->to_same_page) {
                $e = str_replace($this->var_name.'='.$_GET[$this->var_name],'',$_SERVER["REQUEST_URI"]);
                $e = str_replace('&&','',$e);
                $e = str_replace('??','',$e);
                $e = preg_replace('/\&$/','',$e);
                $e = preg_replace('/\?$/','',$e);
            } else {
                $e = $this->api->pm->base_path;
            }
            header("Location: ".$e); exit();
        }
    }
    function getRedirUrl() {
        $url = $_SERVER["REQUEST_URI"];
        $url .= ((substr_count($url,'?')?'&'.$this->var_name.'=':'?'.$this->var_name.'='));
        return $url;
    }
    private function addLangSwitcher() {
        $this->api->add($this->view_class,
            array('controller'=>$this),
        'lang_switcher');
    }
}



/*
//                $before = memory_get_usage();

                    create object here

//                $after = memory_get_usage();
//                var_dump(($after - $before)/(1024*1024));
 */