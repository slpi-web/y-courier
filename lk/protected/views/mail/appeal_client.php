<?php
/**
 * @var $mailInstance YiiMailer
 * @var $model Appeal
 */

$mailInstance->setSubject(Yii::t('mail_appeal_client', 'mail subject {id}', array('{id}' => $model->id)));
?>

<h1>
    <?php echo Yii::t('mail_appeal_client', 'mail header {id}', array('{id}' => $model->id)); ?>
</h1>

<?php
$appealDepartament = array();
if ($model->appealDepartaments) {
    foreach ($model->appealDepartaments as $departament) {
        $appealDepartament[] = $departament->caption;
    }
}
$appealDepartament = implode(', ', $appealDepartament);

echo Yii::t('mail_appeal_client', 'mail content {id} {subject} {text} {appeal_departament}', array(
    '{id}' => $model->id,
    '{subject}' => $model->subject,
    '{text}' => $model->text,
    '{appeal_departament}' => $appealDepartament
));
?>

