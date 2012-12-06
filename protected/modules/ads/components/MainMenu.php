<?php
class MainMenu extends CWidget
{
    public function run()
    {
        // Static home
        $itemsArray = array(
                array('label'=>'На сайт', 'url'=>array('/site/index')),
                array('label'=>'Пользователи', 'url'=>array('/ads/users/admin')),
//                array('label'=>'Комментарии', 'url'=>array('/ads/users/admin'), 'items'=>
//                        array('label'=>'Новости', 'url'=>array('/ads/users/admin'))
//                    ),
            );
        
        $modules_items = array();
            array_push(&$modules_items, array('label'=>'Новости', 'url'=>array('comments/news')));
        
        array_push(&$itemsArray, array('label'=>'Комментарии', 
                                        'url'=>array('comments/index'), 
                                        'items' => $modules_items 
                                      )
                );

        // Static login/logout
        array_push(&$itemsArray,
            array('label'=>Yii::t('Admin', 'Login'), 'url'=>array('/site/login'), 'visible'=>Yii::app()->user->isGuest)
        );
        array_push(&$itemsArray,
            array('label'=>Yii::t('Admin', 'Logout') .' ('.Yii::app()->user->name.')', 'url'=>array('/site/logout'), 'visible'=>!Yii::app()->user->isGuest)
        );

        $this->render('MainMenu', array('items' => $itemsArray));
    }
}
?>