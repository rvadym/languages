<?php
/**
 * Created by JetBrains PhpStorm.
 * User: vadym
 * Date: 1/11/13
 * Time: 9:28 AM
 * To change this template use File | Settings | File Templates.
 */
namespace rvadym\LanguageSwitcher;

class Controller_UrlLanguageSwitcher extends Controller_AbstractLanguageSwitcher {

    public $get_var_name = 'lang';

    function init() {
        parent::init();
        $this->api->addHook('buildURL',$this);
        $this->getLanguage();
        $this->addLangSwitcher();
    }

    private $l = false;
    function getLanguage(){
        if (count($this->languages)==0) throw $this->exception('Provide language set.');
        if ($this->l) {
            return $this->l; // This is not first call of this method. So just return previous result.
        }
        $this->l = $this->getLanguageFromUrl();
        return $this->l;
    }
    private function  getLanguageFromUrl() {

        // no mode_rewrite
        if ($_GET[$this->get_var_name]) {
            $this->api->stickyGet($this->get_var_name);
            return $_GET[$this->get_var_name];
        }

        $page = $this->api->page;
        $url_arr = explode('_', $page);
        foreach ($this->languages as $l) {
            if ($url_arr[0] == $l) {
                unset($url_arr[0]);
                $page = implode('_',$url_arr);
                $this->api->page = ($page=='')?'index':$page;
                return $l;
            }
        }
        if (!$this->l) {
            $path = $this->getChangeLangUrl($this->getDefaultLanguage());
            header("HTTP/1.1 301 Moved Permanently");
            header("Location: ". $path );
            exit();
        }
    }
    function getChangeLangUrl($lang) {

        // just to keep all parameters from original URL
        $get = array();
        foreach ($_GET as $key=>$value) {
            $get[$key] = $value;
        }

        // no mode_rewrite
        if ($_GET[$this->get_var_name]) {
            $this->api->stickyForget($this->get_var_name);
            $url = $this->api->url(null,
                array_merge($get,array($this->get_var_name=>$lang))
            );
            $this->api->stickyGet($this->get_var_name);
            return $url;
        }

        $args_merged = array_merge($get, array('lang'=>$lang,));
        $url = $this->api->url(null,$args_merged);

        return $url;
    }
    function buildURL($junk,$url){
        if ($url->page == 'index') {
            $page = '';
        } else {
            $page = '/'.$url->page;
        }
        if (array_key_exists('lang',$url->arguments)) {
            $url->page = $url->arguments['lang'].$page;
            unset($url->arguments['lang']);
        } else {
            $url->page = $this->getLanguage().$page;
        }
    }
}