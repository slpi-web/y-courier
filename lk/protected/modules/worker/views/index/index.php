<?php
/**
 * @var $this IndexController
 */

$this->pageTitle = Yii::t('worker_pagetitle', 'Index');

$this->breadcrumbs=array(
);

?>

<?php
if (isset(Yii::app()->params['workerIndexPageContent']) && Yii::app()->params['workerIndexPageContent']) {
    echo '<div>';
    echo Yii::app()->params['workerIndexPageContent'];
    echo '</div>';
}
?>
