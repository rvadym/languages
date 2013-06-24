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

class Controller_UrlLanguageSwitcher extends Controller_AbstractLanguageSwitcher {

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
        if ($this->language_in_url) {
            $url = $_SERVER["REQUEST_URI"];
            $url_with_no_base_path = preg_replace('/^'. str_replace( '/','\/', $this->api->pm->base_path ) .'/','/',$url);
            echo '$url_with_no_base_path : '; var_dump($url_with_no_base_path); echo '<hr>';

            if ($url_with_no_base_path == '/') {
                $http = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on')? 'https://':'http://';
                $port = ($_SERVER['SERVER_PORT'] == 80)? '' : ':'.$_SERVER['SERVER_PORT'];
                $path = $this->getChangeLangUrl($this->getDefaultLanguage());
                $link = $http . $_SERVER['SERVER_NAME'] . $port . $path;
                var_dump($path);
//                // TODO change url to url with language
                header("HTTP/1.1 301 Moved Permanently");
                header("Location: ". $link );
                exit();
            }

            return 'RU';

        } else {
            if ($this->recallLang()) {
                $this->l = $this->recallLang();
                return $this->l;
            } else {
                $this->l = $this->getDefaultLanguage();
                $this->memorizeLang($this->l);
                return $this->l;
            }
        }
    }
    private function switchLanguageIfRequired() {
        if ($this->language_in_url) {
            return false;
        }
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
        if ($this->language_in_url) {
            $url_with_no_base_path = preg_replace('/^'. str_replace( '/','\/', $this->api->pm->base_path ) .'/','/',$url);

            $found = false;
            foreach ($this->languages as $l) {
                $current_lang_position = strpos($url_with_no_base_path,'/'.$l.'/');
                if ($current_lang_position === 0) {
                    $found = $l;
                }
            }

            if ($found) {
                $url = $this->api->pm->base_path . preg_replace('/^\/'.$found.'/', $lang, $url_with_no_base_path);
                $this->api->page = str_replace('//','/',
                    preg_replace('/^\/'.$found.'\//', '', $url_with_no_base_path)
                );
            } else {
                $url = str_replace('//','/','/'.$this->api->pm->base_path.$lang.$url_with_no_base_path);
            }
        } else {
            $url .= ((substr_count($url,'?')?'&'.$this->var_name.'='.$lang:'?'.$this->var_name.'='.$lang));
        }
        return $url;
    }

    /**
     * Create URL with language
     */
    public function url(/* some params */) {

    }
}