<?php
/**
 * @var $mailInstance YiiMailer
 * @var $model CseDelivery
 */

$mailInstance->setSubject(Yii::t('mail_new_cse_delivery_worker', 'mail subject {user}', array('user' => $model->user ? $model->user->display : '')));
?>

<h1>
    <?php echo Yii::t('mail_new_cse_delivery_worker', 'mail header {user}', array('user' => $model->user ? $model->user->display : '')); ?>
</h1>

<?php
echo Yii::t('mail_new_cse_delivery_worker', 'mail content {user} {date} {editLink} {waybillLink}', array(
    '{user}' => $model->user ? $model->user->display : '',
    '{date}' => Yii::app()->dateFormatter->formatDateTime($model->timestamp, 'short', 'short'),
    '{editLink}' => Yii::app()->createAbsoluteUrl('/worker/cseDelivery/edit', array('id' => $model->id)),
    '{waybillLink}' => Yii::app()->createAbsoluteUrl('/worker/cseDelivery/getWaybill', array('id' => $model->id)),
));
?>

