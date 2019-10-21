<?php
/**
 * @var $this BaseClientController
 */
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <base href="<?php echo Yii::app()->getBaseUrl().'/'; ?>">

    <link rel="icon" href="favicon.ico" type="image/x-icon">
    <link rel="shortcut icon" href="favicon.ico" type="image/x-icon">

    <title><?php echo $this->pageTitle . ' - ' . Yii::app()->name; ?></title>

    <?php Yii::app()->getClientScript()->registerCssFile(CHtml::asset(Yii::app()->basePath.'/less/client.less')); ?>
</head>

<body>

<?php
$this->widget(
    'booster.widgets.TbNavbar',
    array(
        'brand' => Yii::app()->name,
        'brandUrl' => array('/index/index'),
        'fixed' => false,
        'fluid' => false,
        'items' => array(
            array(
                'class' => 'booster.widgets.TbMenu',
                'type' => 'navbar',
                'items' => array(

                ),
            ),
            array(
                'class' => 'booster.widgets.TbMenu',
                'type' => 'navbar',
                'htmlOptions' => array(
                    'class' => 'navbar-right'
                ),
                'items' => array(
                    array(
                        'label' => Yii::t('view_menu', 'Login'),
                        'url' => array('user/login'),
                        'visible' => Yii::app()->user->isGuest
                    ),
                    array(
                        'label' => Yii::t('view_menu', 'Logout'),
                        'url' => array('user/logout'),
                        'visible' => !Yii::app()->user->isGuest
                    )
                )
            )
        )
    )
);?>

<div class="container-fluid" id="page">
    <div class="container">
        <h1><?php echo $this->getPageTitle(); ?></h1>

        <?php echo $content; ?>
    </div>
</div>

</body>
</html>