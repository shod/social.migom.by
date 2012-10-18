<?php

abstract class ActiveRecord extends CActiveRecord
{
    private $_oldAttributes = array();
    public function setOldAttributes($value)
    {
        $this->_oldAttributes = $value;
    }
    public function getOldAttributes()
    {
        return $this->_oldAttributes;
    }

    
    /*
     *      // Inside the model itself
     *       $OldColumnInfo = $this->OldAttributes['ColumnName'];
     *
     *      // Via an instance
     *       $OldColumnInfo = $MyModelInstance->oldAttributes['ColumnName'];
     */
    public function init()
    {
        $this->attachEventHandler("onAfterFind", function ($event)
        {
            $event->sender->oldAttributes = $event->sender->attributes;
        });
    }
    
    public static function model($className = __CLASS__, $new = false)
    {
        if($new){
            return new $className();
        }
        return parent::model($className);
    }
}