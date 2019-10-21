<?php
/**
 * @var $this CseDeliveryController
 */

$this->pageTitle = Yii::t('worker_pagetitle', 'Cse AddressBook');

?>

<div class="modal-header">
    <a class="close" data-dismiss="modal">&times;</a>
    <h4><?php echo $this->pageTitle; ?></h4>
</div>

<div class="modal-body">

    <?php
    $this->renderPartial('_addressbook_grid', array(
        'model' => $model,
    ));
    ?>

</div>

<div class="modal-footer">
    <?php $this->widget(
        'booster.widgets.TbButton',
        array(
            'label' => Yii::t('view_worker', 'Close'),
            'url' => '#',
            'htmlOptions' => array(
                'id' => 'popup-close',
                'data-dismiss' => 'modal'
            ),
        )
    ); ?>
</div>


