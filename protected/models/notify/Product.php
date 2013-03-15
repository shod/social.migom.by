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
class Notify_Product extends Notify
{


    public $id;
    public $product_id;
    public $user_id;

    public function tableName()
    {
        return 'notify_product';
    }

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return UserProviders the static model class
     */
    public static function model($className = 'Product')
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
            array('product_id, user_id', 'required'),
			array('userName, groupGrid', 'safe', 'on' => 'search'),
        );
    }
	
	public function search()
    {
        // Warning: Please modify the following code to remove attributes that
        // should not be searched.
		
		$criteria = new CDbCriteria;
		$criteria->together = true;
		$criteria->with = 'user';
		$criteria->compare('user.email', $this->userName, true);
        $criteria->compare('id', $this->id);
        $criteria->compare('user_id', $this->user_id);
        $criteria->compare('product_id', $this->product_id);
		$sort = array('attributes'=> array(
			'product_id',
			'created_at',
			'userName'	=>	array( // сортировка по связанном полю
				'asc' 	=> 	$expr='user.email',
				'desc' 	=> 	$expr.' DESC',
			),
		));
		
		if($this->groupGrid){
			$sort['attributes']['countIds'] = array(
					'asc' 	=> 	$expr='countIds',
					'desc' 	=> 	$expr.' DESC',
			);
			$criteria->group = $this->groupGrid;
			$criteria->select = '*, count(1) as countIds';
		}

        return new CActiveDataProvider($this, array(
                    'criteria' => $criteria,
					'pagination'=>array(
						'pageSize'=>50,
					),
					'sort'=>$sort,
                ));
    }

}