<?php
/**
 * @var $mailInstance YiiMailer
 * @var $this UserController
 * @var $user User
 * @var $password string
 */

$mailInstance->setSubject(Yii::t('mail_change_password', 'mail subject'));
?>

<h1>
    <?php echo Yii::t('mail_change_password', 'mail header'); ?>
</h1>

<?php
echo Yii::t('mail_change_password', 'mail content {email} {password}', array(
    '{email}' => $user->email,
    '{password}' => $password,
))
?>

