<?php

/**
 * This is the model class for table "user_providers".
 *
 * The followings are the available columns in table 'user_providers':
 * @property integer $id
 * @property integer $user_id
 * @property integer $provider_id
 * @property integer $soc_id
 */
class Mail_Log extends ActiveRecord
{
    
    public function tableName()
    {
        return 'mail_log';
    }
	
	public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return UserProviders the static model class
     */
    /*public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }*/

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('user_id, template', 'required'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'user' => array(self::BELONGS_TO, 'Users', 'user_id')
        );
    }

}