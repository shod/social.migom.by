<?php

/**
 * This is the model class for table "things".
 *
 * The followings are the available columns in table 'things':
 * @property integer $id
 * @property integer $user_id
 * @property integer $product_id
 * @property integer $have
 * @property integer $created_at
 */
class Things extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Things the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'things';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('user_id, product_id, created_at', 'required'),
			array('user_id, product_id, have, created_at', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, user_id, product_id, have, created_at', 'safe', 'on'=>'search'),
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
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'user_id' => 'User',
			'product_id' => 'Product',
			'have' => 'Have',
			'created_at' => 'Created At',
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

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('user_id',$this->user_id);
		$criteria->compare('product_id',$this->product_id);
		$criteria->compare('have',$this->have);
		$criteria->compare('created_at',$this->created_at);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
	
	public static function getCountThingsGroupByProduct($pIds){
		$productCounts = Yii::app()->db->createCommand()
			->select('count(product_id) as cpi, product_id')
			->from('things')
			->where('things.product_id IN (' . implode(',',$pIds) . ') AND have = 1')
			->group('things.product_id')
			->queryAll();
		$res = array();
		foreach($pIds as $pid){
			$res[$pid] = 0;
		}
		foreach($productCounts as $pa){
			$res[$pa['product_id']] = $pa['cpi'];
		}
		return $res;
	}
}