<?php
/**
 * @var $this AppealController
 * @var $model AppealAdmin
 * @var $messagesDataProvider CActiveDataProvider
 * @var $messageModel AppealMessage
 */
?>

<h2><?php echo Yii::t('view_appeal', 'Messages'); ?></h2>

<?php $this->widget('zii.widgets.CListView', array(
    'dataProvider' => $messagesDataProvider,
    'itemsCssClass' => 'appeal-message-list',
    'itemView' => '_appeal_message',
    'template' => '{items} {pager}',
    'emptyText' => Yii::t('view_appeal', 'This appeal has no posts. <hr class="spacer" />'),
)); ?>

<?php if ($messageModel) { ?>
<div class="panel panel-default">
    <div class="panel-heading">
        <?php echo Yii::t('view_appeal', 'Leave a message') ?>
    </div>
    <div class="panel-body">
        <?php
        $form = $this->beginWidget(
            'booster.widgets.TbActiveForm',
            array(
                'id' => 'appeal-message-form',
                'type' => 'horizontal',
                'htmlOptions' => array(),
            )
        );

        echo $form->textAreaGroup($messageModel, 'message', array(
            'widgetOptions' => array(
                'htmlOptions' => array(
                ),
            )
        ));
        ?>
        <div class="text-center">
            <?php $this->widget('ext.WLadda.TbLaddaButton',
                array('buttonType' => 'submit', 'label' => Yii::t('view_appeal', 'Save message'), 'htmlOptions' => array('class' => 'btn-primary'))
            ); ?>
        </div>
        <?php
        $this->endWidget();
        ?>
    </div>
</div>
<?php } ?>
