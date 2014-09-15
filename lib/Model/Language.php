<?php
/**
 * Created by Vladyslav Polyukhovych
 * Date: 28.06.14
 */
namespace rvadym\languages;
class Model_Language extends \SQL_Model{

    public $table ='language';

    function init(){
        parent::init();

        //fields
        $this->addField('name');
        $this->addField('ui_name');
        $this->addField('is_active')->type('boolean');
    }

    /**
     * @param array - List of languages. Use hashes to set parameters for every record.
     * example:
     $languages=('en','ru','ua'); //simple array
     $languages= array( // array of hashes
        'en'=>array('name'=>'en','ui_name'=>'English','is_active'=>1)
        'lv'=>array('name'=>'lv','ui_name'=>'Latvian','is_active'=>0)
     );
     */
    public function setDefaultData($languages = null){

        if(!is_array($languages)) return;

        $records = array();

        //form records
        foreach ($languages as $lang) {
            if(is_array($lang)){

                //check for required fields
                if(!$lang['name']){
                    $lang['name']=key($languages);
                }

                $records[]=$lang;

            }else{  //simple array
                $records[]= array('name'=>$lang, 'ui_name'=>$lang, 'is_active'=>1);
            }
        }

        //insert records
        if($records){
            $q = $this->dsql();
            $q->insertAll($records);
        }
    }

}