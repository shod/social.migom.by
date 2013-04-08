<?php

class BookmarkController extends Controller
{
	public function actionAdmin()
	{
		$model=new Bookmarks_Bookmark('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Bookmarks_Bookmark'])){
			$model->attributes=$_GET['Bookmarks_Bookmark'];
		}

		$this->render('admin',array(
			'model'=>$model,
		));
	}
}
