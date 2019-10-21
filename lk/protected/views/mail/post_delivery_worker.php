ш<?php
/**
 * @var $mailInstance YiiMailer
 * @var $model PostDelivery
 */

$mailInstance->setSubject(Yii::t('mail_post_delivery_worker', 'mail subject'));
?>

<h1>
    <?php echo Yii::t('mail_post_delivery_worker', 'mail header'); ?>
</h1>

<?php
echo Yii::t('mail_post_delivery_worker', 'mail content {organization} {office} {phone}', array(
    '{organization}' => $model->organization,
    '{office}' => $model->office,
    '{phone}' => ($model->user) ? $model->user->phone : '',
));
?>

