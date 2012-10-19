<?php 
    Yii::app()->clientScript->registerCss(
                'error',
                '
                    .fatalError{
                        background-image: url(\'/images/error.jpg\');
                        background-repeat: no-repeat;
                        width: 150px;
                        height: 76px;
                        font-size: 36px;
                        font-weight: 600;
                        color: white;
                        padding-left: 41px;
                        padding-top: 5px;
                        left: 50%;
                        position: absolute;
                        overflow: auto;
                        top: 50%;
                        margin-left: -75px;
                        margin-top: -120px;
                    }
                '
            );
        
?>
<div class="fatalError"><?php echo $code; ?></div>
<div class="clear"><?php echo $message; ?></div>