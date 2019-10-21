<?php
/**
 * @var $data AppealMessage
 */
?>

<div class="panel panel-default">
    <div class="panel-heading">
        <strong><?php echo Yii::app()->dateFormatter->formatDateTime($data->timestamp, 'short', 'short'); ?></strong>
        <?php if ($data->user) {
            if (in_array($data->user->type, array(User::TYPE_CLIENT)))
                echo ' - '.$data->user->display;
        } ?>
    </div>
    <div class="panel-body">
        <?php
        echo htmlspecialchars(nl2br($data->message));
        ?>
    </div>
</div>