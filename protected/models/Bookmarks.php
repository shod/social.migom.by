<?php

/**
 * This is the model class for table "news_comments".
 *
 * The followings are the available columns in table 'news_comments':
 * @property integer $id
 * @property integer $parent_id
 * @property integer $entity_id
 * @property integer $user_id
 * @property string $text
 * @property integer $likes
 * @property integer $dislikes
 * @property integer $status
 * @property integer $level
 * @property integer $created_at
 * @property integer $updated_at
 */
class Bookmarks extends ActiveRecord
{

	public $userName;
	public $countIds;
	public $groupGrid;
	
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
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return NewsComments the static model class
     */
    public static function model($className = __CLASS__, $new = false)
    {
        if ($className != __CLASS__) {
            $className = 'Bookmarks_' . $className;
        }
        return parent::model($className, $new);
    }
	
	public static function sinchronize($type, $user_id, array $data){
	
		$sections = array();
		
		self::model($type)->deleteAll('user_id = :id', array(':id' => $user_id));
		
		if(!count($data)){
			return false;
		}

		foreach($data as $id => $prod){
			if(!isset($prod['section_id']) || !$prod['section_id']){
				$prod['section_id'] = 0;
			}

			if($prod['section_id'] && !array_key_exists($prod['section_id'], $sections)){
				$sectionName = Yii::app()->cache->get('sections_name_' . $prod['section_id']);
				if(!$sectionName){
					$model = Catalog_Sections::model();
					$model = $model->find('section_id = :id', array(':id' => $prod['section_id']));
					if($model){
						$sections[$prod['section_id']] = $model->name;
					} else {
						$sections[$prod['section_id']] = null;
					}
				}
			}
			$class = 'Bookmarks_' . $type;
			$model = new $class();
			$model->attributes = $prod;
			$model->user_id = $user_id;
			$model->product_id = $id;
			$model->save();
		}
	}
	
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('user_id, section_id, product_id, image, productUrl, title, price', 'required'),
			array('user_id, section_id, product_id', 'numerical', 'integerOnly'=>true),
			array('price', 'numerical'),
			array('image, productUrl, title', 'length', 'max'=>200),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('user_id, section_id, product_id, image, productUrl, title, price, userName, countIds, groupGrid', 'safe', 'on'=>'search'),
		);
	}
	
    public function search()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria=new CDbCriteria;

		$criteria->together = true;
		$criteria->with = 'user';
		$criteria->compare('user.email', $this->userName, true);
		$criteria->compare('countIds', $this->countIds);
		$criteria->compare('user_id',$this->user_id);
		$criteria->compare('section_id',$this->section_id);
		$criteria->compare('product_id',$this->product_id);
		$criteria->compare('image',$this->image,true);
		$criteria->compare('productUrl',$this->productUrl,true);
		$criteria->compare('title',$this->title,true);
		$criteria->compare('price',$this->price);
		
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
			'criteria'		=>	$criteria,
			'pagination'	=>	array(
				'pageSize'	=>	50,
			),
			'sort' => $sort,
			
		));
	}
}
