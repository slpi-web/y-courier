<?php
/**
 * @var $mailInstance YiiMailer
 * @var $this UserController
 * @var $user User
 * @var $key string
 */

$mailInstance->setSubject(Yii::t('mail_reset_password', 'mail subject'));
?>

<h1>
    <?php echo Yii::t('mail_reset_password', 'mail header'); ?>
</h1>

<?php
echo Yii::t('mail_reset_password', 'mail content {email} {changePasswordUrl}', array(
    '{email}' => $user->email,
    '{changePasswordUrl}' => Yii::app()->createAbsoluteUrl('/user/changePassword', array(
        'email' => $user->email,
        'token' => $key,
    )),
))
?>

