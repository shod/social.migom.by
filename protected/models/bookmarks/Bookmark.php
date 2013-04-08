<?php

/**
 * This is the model class for table "bookmarks_bookmark".
 *
 * The followings are the available columns in table 'bookmarks_bookmark':
 * @property integer $user_id
 * @property integer $section_id
 * @property integer $product_id
 * @property string $image
 * @property string $productUrl
 * @property string $title
 * @property double $price
 */
class Bookmarks_Bookmark extends Bookmarks
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return bookmarksBookmark the static model class
	 */
	public static function model($className = 'Bookmark')
    {
        return parent::model($className);
    }

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'bookmarks_bookmark';
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'user_id' => 'User',
			'section_id' => 'Section',
			'product_id' => 'Product',
			'image' => 'Image',
			'productUrl' => 'Product Url',
			'title' => 'Title',
			'price' => 'Price',
		);
	}
}