<?php
/**
 * Created by JetBrains PhpStorm.
 * User: vadym
 * Date: 6/24/13
 * Time: 10:44 PM
 * To change this template use File | Settings | File Templates.
 */
namespace rvadym\languages;
//require_once __DIR__."/../../spyc/spyc.php";

abstract class Controller_AbstractLanguageSwitcher extends \AbstractController {
    public $file_extension         = 'yml';
    public $languages              = array();
    public $default_language       = false;
    public $translation_dir_path   = false;
    public $switcher_tag           = 'lang_switch'; // 'lang_switch'
    public $view_class             = 'rvadym/languages/View_LanguageSwitcher';
    public $var_name               = 'language_switcher_lang';
    public $to_same_page           = true;
    public $initiator              = null;

    function init() {
        parent::init();
        $this->api->languages = $this;
        if ($this->translation_dir_path) {
            $this->translation_dir_path = $this->api->pathfinder->base_location->getPath().'/'.$this->translation_dir_path;
        } else {
            $this->translation_dir_path = $this->api->pathfinder->base_location->getPath().'/translations';
        }
        $this->api->getConfig($this->initiator->getAddonName().'/switcher_tag',$this->switcher_tag);
    }

    ////////  translation  ////////
    protected $translations = array();
    public function __($string){
        if (count($this->translations)==0) {
            $this->translate();
        }

        $this->isTranslated($string); // for test env only
        if (array_key_exists($string,$this->translations)) {
            return $this->translations[$string] . (($this->api->getConfig($this->initiator->getAddonName().'/debug',false))?"\xe2\x80\x8b":'');
        } else {
            return (($this->api->getConfig($this->initiator->getAddonName().'/debug',false))?'☺':'') . $string;
        }
    }
    // works in dev env only
    function isTranslated($string) {
        // check if passed twise throw translation
        if ($this->api->getConfig($this->initiator->getAddonName().'/debug',false)) {
            if(strpos($string,"\xe2\x80\x8b")!==false){
                if (!strpos($string,'passed through _() twice')) {
                    throw $this->exception('String '.$string.' passed through _() twice');
                }
            }
        }
    }
    public function translate() {
        if($this->model) {
            $t_trans = $this->model->getRows();
            $lang = $this->getLanguage();
            foreach ($t_trans as $t) {
                if (!array_key_exists($lang,$t)) continue;
                $this->translations[$t['value']] = $t[$lang];
            }
        } else {
            $this->createDirIfNotExist($this->translation_dir_path);
            $files = scandir($this->translation_dir_path);
            foreach ($files as $file) {
                if ($file != $this->getLanguage().'.'.$this->file_extension) continue;
                $this->translations = \Spyc::YAMLLoad($this->translation_dir_path.'/'.$file);
            }
        }
        return $this;
    }
    public function getDefaultLanguage() {
        if ($this->default_language) {
            return $this->default_language;
        } else {
            return $this->languages[0];
        }
    }
    public function addLangSwitcher($view,$class=null) {

        $view_class = ($class)?$class:$this->view_class;
        $view->add($view_class,
            array('controller'=>$this),
        $this->switcher_tag);
    }
    private function createDirIfNotExist($path) {
        if (file_exists($path)) {
            if (!is_dir($path)) {
                throw $this->exception('File '.$path.' exist and is not a dir!');
            }
        } else {
            mkdir($path);
            chmod($path,0777);
        }
    }

    public function setModel($model){
        $m = $this->add($model);

        //If model is empty, load data from files
        if(!$m->count()->getOne()){
            $m->fillFromFile();
        }

        parent::setModel($m);
    }

    /**
     * Remove all data from model
     */
    public function ClearModel(){
        $m = $this->getModel();
        $m->dsql()->delete();
    }

}