<?php
/**
 * Created by Vladyslav Polyukhovych
 * Date: 28.06.14
 */
namespace rvadym\languages;
class Model_Translation extends \SQL_Model{

    public $table ='translation';

    function init(){
        parent::init();
        $this->addFields();
    }

    private function addFields(){
        $this->addField('value');
        foreach ($this->owner->getActiveLanguages() as $el) {
            $this->addField($el);
        }
        return $this;
    }
}