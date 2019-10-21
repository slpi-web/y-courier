<?php
/**
 * @var $this BaseWorkerController
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

if (Yii::app()->user->isWorker()) {
    $items = CMap::mergeArray($items, array(
        array(
            'label' => Yii::t('view_worker_menu', 'Cse Delivery'),
            'url' => array('/worker/cseDelivery'),
            'items' => array(
                array(
                    'label' => Yii::t('view_worker_menu', 'Cse Delivery List'),
                    'url' => array('/worker/cseDelivery/list'),
                ),
                array(
                    'label' => Yii::t('view_worker_menu', 'Cse Delivery Add'),
                    'url' => array('/worker/cseDelivery/add'),
                ),
                array(
                    'label' => Yii::t('view_worker_menu', 'Cse Delivery Calc'),
                    'url' => array('/worker/cseDelivery/calc'),
                ),
            )
        ),
        array(
            'label' => Yii::t('view_worker_menu', 'Appeals'),
            'url' => array('/worker/appeal'),
            'items' => array(
                array(
                    'label' => Yii::t('view_worker_menu', 'Appeals List'),
                    'url' => array('/worker/appeal/list'),
                ),
                array(
                    'label' => Yii::t('view_worker_menu', 'Appeal Add'),
                    'url' => array('/worker/appeal/add'),
                ),
            )
        ),
        array(
            'label' => Yii::t('view_worker_menu', 'Post Delivery'),
            'url' => array('/worker/postDelivery'),
            'items' => array(
                array(
                    'label' => Yii::t('view_worker_menu', 'Post Delivery List'),
                    'url' => array('/worker/postDelivery/list'),
                ),
                array(
                    'label' => Yii::t('view_worker_menu', 'Post Delivery Add'),
                    'url' => array('/worker/postDelivery/add'),
                ),
            )
        ),
        array(
            'label' => Yii::t('view_worker_menu', 'Users'),
            'url' => array('/worker/user'),
            'items' => array(
                array(
                    'label' => Yii::t('view_worker_menu', 'User List'),
                    'url' => array('/worker/user/list'),
                ),
                array(
                    'label' => Yii::t('view_worker_menu', 'User Add'),
                    'url' => array('/worker/user/add'),
                ),
            )
        ),
        array(
            'label' => Yii::t('view_worker_menu', 'Reports'),
            'url' => array('/worker/report'),
        ),
    ));
}

$this->widget(
    'booster.widgets.TbNavbar',
    array(
        'brand' => Yii::app()->name,
        'brandUrl' => array('/worker/index'),
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
                'htmlOptions' => array(
                    'class' => 'navbar-right'
                ),
                'items' => array(
                    array(
                        'label' => Yii::t('view_worker_menu', 'Login'),
                        'url' => array('/user/login'),
                        'visible' => Yii::app()->user->isGuest
                    ),
                    array(
                        'label' => Yii::t('view_worker_menu', 'Logout'),
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