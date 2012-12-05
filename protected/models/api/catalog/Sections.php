<?php

class Api_Catalog_Sections extends ERestDocument
{
    public $catalog_id;
	public $name;
	public $catalog_type;
	public $hidden;
	public $section_id;
	public $subject_type;
	public $f_main;


    public function getCollectionName()
    {
        return 'Catalog_Sections';
    }

    /**
	 * Get ERest component instance
	 * By default it is ERest application component
	 *
	 * @return ERest
	 * @since v1.0
	 */
	public function getRestComponent()
	{
		return $this->setRestComponent(Yii::app()->getComponent('migom'));
	}

    public static function model($className = 'Catalog_Sections')
	{
		return parent::model($className);
	}

}
