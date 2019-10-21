<?php
/**
 * @var $data AppealMessage
 */
?>

<div class="panel panel-default">
    <div class="panel-heading">
        <strong><?php echo Yii::app()->dateFormatter->formatDateTime($data->timestamp, 'short', 'short'); ?></strong>
        -
        <?php if ($data->user) {
            echo CHtml::link($data->user->display, array('/admin/user/edit', 'id' => $data->id));
        } ?>
        <div class="pull-right">
            <?php
            echo CHtml::link('<i class="glyphicon glyphicon-remove-circle"></i>', array('/admin/appeal/deleteAppealMessage', 'id' => $data->id), array(
                'class' => 'btn btn-default btn-xs',
                'title' => Yii::t('view_appeal', 'Delete message')
            ));
            ?>
        </div>
    </div>
    <div class="panel-body">
        <?php
        echo htmlspecialchars(nl2br($data->message));
        ?>
    </div>
</div>