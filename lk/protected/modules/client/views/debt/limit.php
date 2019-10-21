<?php
/**
 * @var $this DebtController
 */

$this->pageTitle = Yii::t('client_pagetitle', 'Debt limit exceeded');

$this->breadcrumbs=array(
);

?>

<?php
    if (isset(Yii::app()->params['clientDebtLimitPageContent']) && Yii::app()->params['clientDebtLimitPageContent']) {
        echo '<div>';
        echo Yii::app()->params['clientDebtLimitPageContent'];
        echo '</div>';
    }
?>
