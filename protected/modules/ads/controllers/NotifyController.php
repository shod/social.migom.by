<?php

class NotifyController extends Controller
{


	/**
	 * Manages all models.
	 */
	public function actionProduct()
	{
		$model=new Notify_Product('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Notify_Product'])){
			$model->attributes=$_GET['Notify_Product'];
		}

		$this->render('product',array(
			'model'=>$model,
		));
	}
	
	public function actionProductCost()
	{
		$model=new Notify_Product_Cost('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Notify_Product_Cost'])){
			$model->attributes=$_GET['Notify_Product_Cost'];
		}

		$this->render('productcost',array(
			'model'=>$model,
		));
	}

	/**
	 * Performs the AJAX validation.
	 * @param CModel the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='users-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
