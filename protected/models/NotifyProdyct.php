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
class NotifyProduct extends CActiveRecord
{

    public $id;
    public $product_id;
    public $user_id;

    public function tableName()
    {
        return 'notify_product_cost';
    }

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return UserProviders the static model class
     */
    public static function model($className = __CLASS__)
    {
        if ($className != __CLASS__) {
            $className = self::$providersModel[$className];
        }
        return parent::model($className);
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('product_id, user_id', 'required'),
            array('email', 'email'),
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

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
     */
    public function search()
    {
        // Warning: Please modify the following code to remove attributes that
        // should not be searched.
        $criteria = new CDbCriteria;

        $criteria->compare('id', $this->id);
        $criteria->compare('user_id', $this->user_id);
        $criteria->compare('soc_id', $this->soc_id);

        return new CActiveDataProvider($this, array(
                    'criteria' => $criteria,
                ));
    }

}