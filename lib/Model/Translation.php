<?php
/**
 * Created by Vladyslav Polyukhovych
 * Date: 28.06.14
 */

class Model_Translation extends \SQL_Model{

    public $table ='translation';

    function init(){
        parent::init();
        $this->addFields();
    }

    function addFields(){
        $this->addField('value');

        foreach ($this->owner->languages as $el) {
            $this->addField($el);
        }

        return $this;
    }

    /*---data functions---*/

    /**
     * Fill table from translation files.
     * Use $config['rvadym']['languages']['translation_dir_path'] to set directory of files.
     * It would use just languages from $language if it is set.
     * @param string/array $language
     * @param string $mode - The mode of loading. Variants: 'update','replase'
     */
    public function fillFromFile($mode='update',$language = null){
        //TODO:
    }

    /**
     * Fill files from DB translation.
     * Use $config['rvadym']['languages']['translation_dir_path'] to set directory of files.
     * It would use just languages from $language if it is set.
     * @param string/array $language
     */
    public function fillFileFromDB(){
        //TODO:
    }

    /*---hooks---*/

    function beforeSave(){

    }

    function beforeDelete(){

    }

    function afterSave(){

    }

    function afterDelete(){

    }

}