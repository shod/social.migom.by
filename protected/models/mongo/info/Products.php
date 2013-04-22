<?php

class Info_Products extends EMongoDocument {

    public $product_id;
    public $section_id;
	public $name;

	public function embeddedDocuments() {  // встроенные, суб массивы!
        return array(
            // property name => embedded document class name
            //  'entities' => 'NewsEntity'
        );
    }
	
	public function beforeSave() {
        return parent::beforeSave();
    }
	
    /**
     * This method have to be defined in every Model
     * @return string MongoDB collection name, witch will be used to store documents of this model
     */
    public function getCollectionName() {
        return 'info_products';
    }

    /**
     * We can define rules for fields, just like in normal CModel/CActiveRecord classes
     * @return array
     */
    public function rules() {
        return array(
            array('product_id, section_id, name', 'required'),
			//array('product_id', 'unique'),
        );
    }

    public static function model($className = __CLASS__) {
        return parent::model($className);
    }
}