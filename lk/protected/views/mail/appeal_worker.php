<?php
/**
 * @var $mailInstance YiiMailer
 * @var $model Appeal
 */

$mailInstance->setSubject(Yii::t('mail_appeal_worker', 'mail subject {id}', array('{id}' => $model->id)));
?>

<h1>
    <?php echo Yii::t('mail_appeal_worker', 'mail header {id}', array('{id}' => $model->id)); ?>
</h1>

<?php
echo Yii::t('mail_appeal_worker', 'mail content {id} {subject} {text} {phone} {email}', array(
    '{id}' => $model->id,
    '{subject}' => $model->subject,
    '{text}' => $model->text,
    '{phone}' => ($model->user) ? $model->user->phone : '',
    '{email}' => ($model->user) ? $model->user->email : '',
));
?>

