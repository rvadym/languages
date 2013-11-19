<?php
/**
 * Created by JetBrains PhpStorm.
 * User: vadym
 * Date: 6/24/13
 * Time: 11:17 PM
 * To change this template use File | Settings | File Templates.
 */
namespace rvadym\language_switcher;
class Controller_PatternRouter extends \Controller_PatternRouter {

    function init(){
        parent::init();
//        var_dump($this->api->page);
    }

    function buildURL($junk,$url){
        if ($this->links[$url->page]) {
            $base_url = $url->page;
            // start consuming arguments
            $args=$this->links[$url->page];

            foreach ($args as $key=>$match) {

                if(is_numeric($key)){
                    $key=$match;
                }

                if (isset($url->arguments[$key])) {
                    if ($key == 'base_page') {
                        $url->page = str_replace($base_url,$url->arguments[$key],$url->page);
                    } else {
                        $url->page.='/'.$url->arguments[$key];
                    }
                    unset($url->arguments[$key]);
                }

            }
        }
    }

}
