<?php
/**
 * @var $this IndexController
 */

$this->pageTitle = Yii::t('client_pagetitle', 'Index');

$this->breadcrumbs=array(
);

?>

<?php
    if (isset(Yii::app()->params['clientIndexPageContent']) && Yii::app()->params['clientIndexPageContent']) {
        echo '<div>';
        echo Yii::app()->params['clientIndexPageContent'];
        echo '</div>';
    }
?>
