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

class Controller_SessionLanguageSwitcher extends Controller_AbstractLanguageSwitcher {
    function init() {
        parent::init();
        $this->switchLanguageIfRequired();
        $this->getLanguage();
        $this->addLangSwitcher();
    }

    // do not use directly, use $this->getLanguage() instead.
    private $l = false;
    function getLanguage(){
        if (count($this->languages)==0) throw $this->exception('Provide language set.');
        if ($this->l) {
            return $this->l; // This is not first call of this method. So just return previous result.
        }

        if ($this->recallLang()) {
            $this->l = $this->recallLang();
            return $this->l;
        } else {
            $this->l = $this->getDefaultLanguage();
            $this->memorizeLang($this->l);
            return $this->l;
        }
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
    function getChangeLangUrl($lang) {
        $url = $_SERVER["REQUEST_URI"];
        $url .= ((substr_count($url,'?')?'&'.$this->var_name.'='.$lang:'?'.$this->var_name.'='.$lang));
        return $url;
    }
}