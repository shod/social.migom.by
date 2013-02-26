<?php

/**
 * This is the model class for table "messages".
 *
 * The followings are the available columns in table 'messages':
 * @property integer $id
 * @property integer $user_id
 * @property integer $status
 * @property integer $sender_id
 *
 * The followings are the available model relations:
 * @property MessagesText $id0
 */
class Messages extends CActiveRecord
{

	const DIALOGS_LIMIT = 10;
	const MESSAGES_ON_DIALOG = 5;

	public $last_message_id;
	
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Messages the static model class
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
		return 'messages';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('user_id, sender_id', 'required'),
			array('user_id, status, sender_id', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('message_id, user_id, status, sender_id', 'safe', 'on'=>'search'),
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
			'textTable' => array(self::BELONGS_TO, 'Messages_Text', 'message_id'),
			'sender' 	=> array(self::BELONGS_TO, 'Users', 'sender_id'),
			'user' 	=> array(self::BELONGS_TO, 'Users', 'user_id'),
			'toUser' 	=> array(self::BELONGS_TO, 'Users', 'to'),
			'lastMessage' => array(self::BELONGS_TO, 'Messages', 'last_message_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'message_id' => 'ID',
			'user_id' => 'User',
			'status' => 'Status',
			'sender_id' => 'Sender',
		);
	}
	
	public function getCountUnreadMessages($user_id){
		$sql = 'SELECT COUNT(1) count FROM(
					SELECT message_id 
					FROM `messages` `t` 
						INNER JOIN messages_text mt on (message_id = id) 
					WHERE t.to = ' . $user_id . ' AND mt.status = 0 
					GROUP BY t.dialog_id) sq';
		
		$connection=Yii::app()->db;
		$command=$connection->createCommand($sql);
		$dataReader=$command->query();
		$data = $dataReader->read();
		return $data['count'];
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function searchDialogs($user_id, $offset = 0)
	{
		$more = false;
		$lim = self::DIALOGS_LIMIT + 1 ;
		$sql = 'SELECT
			t.message_id,
			t.user_id,
			t.dialog_id,
			t.sender_id,
			t.to,
			t.status,
			messages_text.text,
			messages_text.created_at,
			messages_text.status as text_status
		FROM 
			messages as t, (
				SELECT MAX(message_id) last_message_id from messages m WHERE 
					(m.user_id='.$user_id.')
					AND 
						(m.status = 0)
					GROUP BY
						m.dialog_id 
					ORDER BY last_message_id DESC
					LIMIT ' . $offset . ','. $lim .'
				) as last
			INNER JOIN 
				messages_text
			ON 
				(last.last_message_id  = messages_text.id)
		WHERE 
			message_id = last.last_message_id  AND user_id='.$user_id.' 
		ORDER BY message_id DESC
				';
		
		$connection=Yii::app()->db;
		$command=$connection->createCommand($sql);
		$dataReader=$command->query();
		$users = array();
		$messages = array();
		while(($row=$dataReader->read())!==false) {
			$messages[] = $row;
			if($row['sender_id'] == $row['user_id']){
				$users[$row['to']] = $row['to'];
			} else {
				$users[$row['sender_id']] = $row['sender_id'];
			}
		}
		if(count($messages) > self::DIALOGS_LIMIT){
			array_pop($messages);
			$more = true;
		}

		$criteria=new CDbCriteria;
		$criteria->addInCondition('id', $users);

		$usersObjs = Users::model()->with('profile')->findAll($criteria);
		foreach($usersObjs as $user){
			$users[$user->id] = $user;
		}

		return array('messages' => $messages, 'users' => $users, 'more' => $more);
	}
}