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
$items = array();
$user = Yii::app()->user->loadUser();

if (Yii::app()->user->isClient()) {
    $items = CMap::mergeArray($items, array(
        array(
            'label' => Yii::t('view_client_menu', 'Cse Delivery'),
            'url' => array('/client/cseDelivery'),
            'items' => array(
                array(
                    'label' => Yii::t('view_client_menu', 'Cse Delivery'),
                    'url' => array('/client/cseDelivery'),
                ),
                array(
                    'label' => Yii::t('view_client_menu', 'Cse Delivery Calc'),
                    'url' => array('/client/cseDelivery/calc'),
                ),
            )
        ),
        array(
            'label' => Yii::t('view_client_menu', 'Appeals'),
            'url' => array('/client/appeal'),
        ),
        array(
            'label' => Yii::t('view_client_menu', 'Post Delivery'),
            'url' => array('/client/postDelivery'),
        ),

        array(
            'label' => Yii::t('view_client_menu', 'Profile'),
            'url' => array('/client/profile'),
        ),
    ));
}

$this->widget(
    'booster.widgets.TbNavbar',
    array(
        'brand' => Yii::app()->name,
        'brandUrl' => array('/client/index'),
        'fixed' => false,
        'fluid' => false,
        'items' => array(
            array(
                'class' => 'booster.widgets.TbMenu',
                'type' => 'navbar',
                'items' => $items,
            ),
            array(
                'class' => 'booster.widgets.TbMenu',
                'type' => 'navbar',
                'encodeLabel' => false,
                'htmlOptions' => array(
                    'class' => 'navbar-right'
                ),
                'items' => array(
                    array(
                        'label' => $user ? '<p class="navbar-text">'.$user->getAttributeLabel('debt').': '.$user->debt.'</p>' : '',
                        'visible' => !Yii::app()->user->isGuest && $user
                    ),
                    array(
                        'label' => Yii::t('view_client_menu', 'Login'),
                        'url' => array('/user/login'),
                        'visible' => Yii::app()->user->isGuest
                    ),
                    array(
                        'label' => Yii::t('view_client_menu', 'Logout'),
                        'url' => array('/user/logout'),
                        'visible' => !Yii::app()->user->isGuest
                    )
                )
            )
        )
    )
);?>

<div class="container-fluid" id="page">
    <div class="container">
        <h1>
            <?php echo $this->getPageTitle(); ?>
            <?php if ($this->headerButton) {
                $headerButton = '';
                if (is_array($this->headerButton)) {
                    if (isset($this->headerButton['title']) && isset($this->headerButton['link'])) {
                        $headerButton = '<a href="'.$this->headerButton['link'].'" class="btn btn-primary pull-right" role="button">'.$this->headerButton['title'].'</a>';
                    }
                } else
                    $headerButton = $this->headerButton;

                if ($headerButton)
                    echo $headerButton;
            } ?>
        </h1>

        <?php
        if ($this->breadcrumbs) {
            $this->widget('booster.widgets.TbBreadcrumbs', array(
                'homeLink' => false,
                'links' => $this->breadcrumbs,
            ));
        }
        ?>

        <?php echo $content; ?>
    </div>
</div>

</body>
</html>