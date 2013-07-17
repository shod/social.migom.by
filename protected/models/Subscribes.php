<?php

/**
 * This is the model class for table "subscribes".
 *
 * The followings are the available columns in table 'subscribes':
 * @property integer $id
 * @property integer $user_id
 * @property integer $tag_id
 */
class Subscribes extends CActiveRecord
{
	public $group_count;
	const LIMIT = 20;
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Subscribes the static model class
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
		return 'subscribes';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('user_id, tag_id', 'required'),
			array('user_id, tag_id', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('user_id, tag_id', 'safe', 'on'=>'search'),
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
			'user_id' => 'User',
			'tag_id' => 'Tag',
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

		$criteria->compare('user_id',$this->user_id);
		$criteria->compare('tag_id',$this->tag_id);
		$criteria->compare('is_search',0);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
	
	public function searchByUser($id, $limit, $offset)
	{
		$sql="
			SELECT 
				s.tag_id, s.time_group
			FROM 
				subscribes as s, (SELECT `time_group` FROM `subscribes` where user_id = {$id} group by time_group order by time_group limit {$offset},{$limit}) groups 
			WHERE 
				s.time_group IN (groups.time_group)
			ORDER BY 
				s.time_group";

		$connection = Yii::app()->db;

		$command=$connection->createCommand($sql);
		$rowCount=$command->execute();
		$subscribes=$command->queryAll();

		$ids = array();
		foreach($subscribes as $sub){
			$ids[$sub['tag_id']] = $sub['tag_id'];
		}
		$tags = Tags::model()->getByIds(array('ids' => $ids));
		if(!count($tags)){
			return array();
		}
		unset($tags->success);
		$words = array();
		foreach($tags as $tag){
			$words[$tag->id] = $tag->name;
		}
		$return = array();
		foreach($subscribes as $sub){
			$return[$sub['time_group']][] = $words[$sub['tag_id']];
		}
		return $return;
	}
}