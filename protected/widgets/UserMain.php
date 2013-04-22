<?php
class UserMain extends QWidget {

    public $model;
    public $news;
    public $active;

    public function run()
    {
        $this->render('userMain', array('model' => $this->model, 'news' => $this->news));
    }

}