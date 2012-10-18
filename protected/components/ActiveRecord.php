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
}