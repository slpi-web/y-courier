<html>
<head>
    <meta content="text/html; charset=UTF-8" http-equiv="content-type">
    <style>
        hr {border: 0 none; border-top 1px solid #898989;}
    </style>
</head>

<?php echo $content; ?>

<?php echo Yii::t('mail_worker', 'Contact HTML'); ?>
<hr />
<p>
    <?php echo Yii::t('mail_worker', 'This message has been sent automatically, please do not reply.'); ?>
</p>
</body>
</html>