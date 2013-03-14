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
class Notify_Product_Cost extends Notify_Product
{
    public $cost;

    public function tableName()
    {
        return 'notify_product_cost';
    }

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return UserProviders the static model class
     */
    public static function model($className = 'Product_Cost')
    {
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
            array('product_id, cost, user_id', 'required'),
        );
    }

}