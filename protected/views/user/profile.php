<div class="lenta">

    <?php $this->widget('UserMain', array('model' => $model, 'active' => 'profile')); ?>

    <div class="main profile">
        <div class="summary">
            <div class="avatar"><?= UserService::printAvatar($model->id, $model->login, 96); ?></div>
            <div class="name">
                <strong><?= $model->login; ?></strong>
                <?php if($model->id == Yii::app()->user->id): ?>
                    <?= CHtml::link(Yii::t('Profile', 'Редактировать профиль'), array('/profile/edit')) ?>
                <?php endif; ?>
            </div>
            <div class="info"><?= Yii::t('Profile', 'Дата регистрации'); ?><strong><?= SiteService::timeToDate($model->date_add, true) ?></strong></div>
            <!--<div class="info"><?= Yii::t('Profile', 'Просмотров профиля'); ?><strong>326</strong></div>-->
        </div>
        <table>
            <caption><?= Yii::t('Profile', 'Общая информация'); ?></caption>
            <tr>
                <th><?= $model->profile->getAttributeLabel('name') ?>:</th>
                <td><?= $model->profile->name; ?></td>
            </tr>
            <tr>
                <th><?= $model->profile->getAttributeLabel('surname') ?>:</th>
                <td><?= $model->profile->surname; ?></td>
            </tr>
            <tr>
                <th><?= $model->profile->getAttributeLabel('sex') ?>:</th>
                <td><?= Yii::t('Profile', Users_Profile::$sexs[$model->profile->sex]); ?></td>
            </tr>
            <tr>
                <th><?= $model->profile->getAttributeLabel('birthday') ?>:</th>
                <td><?= $model->profile->birthday ?></td>
            </tr>
            <?php if($model->profile->city): ?>
            <tr>
                <th><?= $model->profile->getAttributeLabel('city_id') ?>:</th>
                <td><?= $model->profile->city->name ?></td>
            </tr>
            <?php endif; ?>
        </table>

        <table>
            <caption><?= Yii::t('Profile', 'Активность на сайте'); ?></caption>
            <tr>
                <th><a href="#"><?= $model->getCountComments() ?></a></th>
                <td><?= SiteService::getCorectWordsT('Site', 'comments', $model->getCountComments()) ?></td>
            </tr>
<!--            <tr>
                <th><a href="#">16</a></th>
                <td>отзывов на товар</td>
            </tr>
            <tr>
                <th><a href="#">154</a></th>
                <td>отзывов на продавца</td>
            </tr>-->
        </table>
    </div>

</div>