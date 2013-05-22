<?php
/**
 * Created by JetBrains PhpStorm.
 * User: vadym
 * Date: 1/11/13
 * Time: 9:28 AM
 * To change this template use File | Settings | File Templates.
 */

/*


  !!! NO MORE SESSION  !!!

Usage:
add to you Frontend


    // translations
    public $x_ls = false;
    function __($string) {
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

$this->api->__('ane text to translate')


 */
namespace x_ls;
require_once __DIR__."/../../spyc/spyc.php";

class Controller_LanguageSwitcher extends \Controller {
    public $file_extension = 'yml';
    public $languages = array();
    public $default_language = false;
    public $translation_dir_path=false;
    public $switcher_tag = 'x_ls_panel';

    function init() {
        parent::init();
        
        // add locations
        $l=$this->api->locate('addons',__NAMESPACE__,'location');
        $addon = $this->api->locate('addons',__NAMESPACE__);
        $this->api->pathfinder->addLocation($addon,array(
            'php'=>'lib',
            'template'=>'templates',
            'css'=>'templates/css',
        ))->setParent($l);
        $this->api->jui->addStaticStylesheet('switcher');

        $this->api->x_ls = $this;
        if (!$this->translation_dir_path) $this->translation_dir_path = $this->api->pm->base_directory.'translations';

        $this->switchLanguageIfRequired();
        $this->getLanguage();
        $this->addLangSwitcher();
    }

    ////////  translation  ////////
    private $translations = array();
    function __($string){
        if (count($this->translations)==0) $this->translate();
        if ($this->model) {
            if (array_key_exists($string,$this->translations)) {
                return $this->translations[$string];
            }
        } else {
            if (array_key_exists($string,$this->translations)) {
                return $this->translations[$string];
            }
        }
        return '☺'.$string;
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
        if ($this->l) return $this->l;
        if ($this->recallLang()) return $this->l = $this->recallLang();
        if ($this->default_language) {
            $this->memorizeLang($this->default_language);
            return $this->l = $this->default_language;
        } else {
            $this->memorizeLang($this->languages[0]);
            return $this->l = $this->languages[0];
        }
    }
    private function memorizeLang($lang) {
        $this->memorize('user_panel_lang',$lang);
    }
    private function recallLang() {
        return $this->recall('user_panel_lang');
    }
    private function switchLanguageIfRequired() {
        if ($_GET['user_panel_lang']) {
            $this->memorizeLang($_GET['user_panel_lang']);
            $e = str_replace('user_panel_lang='.$_GET['user_panel_lang'],'',$_SERVER["REQUEST_URI"]);
            $e = str_replace('&&','',$e);
            $e = str_replace('??','',$e);
            $e = preg_replace('/\&$/','',$e);
            $e = preg_replace('/\?$/','',$e);
            header("Location: ".$e);
        }
    }
    private function addLangSwitcher() {
        $v = $this->api->add('View_LanguageSwitcher',
                array(
                    'languages'=>$this->languages,
                    'default_language'=>$this->getLanguage()),
                'lang_switcher');
        /*
        $v = $this->api->add('View',null,'lang_switcher');
        foreach ($this->languages as $lang) {
            $lv = $v->add('View')->addStyle('float','right');
            if ($lang == $this->getLanguage()) {
                $lv->setHTML('&nbsp;'.$lang.'&nbsp;');
            } else {
                $lv->setHTML('&nbsp;<a href="'.$this->getRedirUrl().$lang.'">'.$lang.'</a>&nbsp;');
            }
        }
         * 
         */
    }
    ///////////     addon config      //////////////
    function defaultTemplate() {
		// add add-on locations to pathfinder
		$this->l = $this->api->locate('addons',__NAMESPACE__,'location');
		$addon_location = $this->api->locate('addons',__NAMESPACE__);
		$this->api->pathfinder->addLocation($addon_location,array(
			//'js'=>'templates/js',
			//'css'=>'templates/css',
            //'template'=>'templates',
		))->setParent($this->l);
        parent::defaultTemplate();
    }
}



/*
//                $before = memory_get_usage();

                    create object here

//                $after = memory_get_usage();
//                var_dump(($after - $before)/(1024*1024));
 */