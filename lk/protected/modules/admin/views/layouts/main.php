<?php
/**
 * @var $this BaseAdminController
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

if (Yii::app()->user->isAdmin()) {
    $items = CMap::mergeArray($items, array(
        array(
            'label' => Yii::t('view_admin_menu', 'Cse Delivery'),
            'url' => array('/admin/cseDelivery'),
            'items' => array(
                array(
                    'label' => Yii::t('view_admin_menu', 'Cse Delivery List'),
                    'url' => array('/admin/cseDelivery/list'),
                ),
                array(
                    'label' => Yii::t('view_admin_menu', 'Cse Delivery Add'),
                    'url' => array('/admin/cseDelivery/add'),
                ),
                array(
                    'label' => Yii::t('view_admin_menu', 'Cse Delivery Calc'),
                    'url' => array('/admin/cseDelivery/calc'),
                ),
            )
        ),
        array(
            'label' => Yii::t('view_admin_menu', 'Appeals'),
            'url' => array('/admin/appeal'),
            'items' => array(
                array(
                    'label' => Yii::t('view_admin_menu', 'Appeals List'),
                    'url' => array('/admin/appeal/list'),
                ),
                array(
                    'label' => Yii::t('view_admin_menu', 'Appeal Add'),
                    'url' => array('/admin/appeal/add'),
                ),
                array(
                    'label' => Yii::t('view_admin_menu', 'Appeal Departaments List'),
                    'url' => array('/admin/appeal/departamentList'),
                ),
                array(
                    'label' => Yii::t('view_admin_menu', 'Appeal Departament Add'),
                    'url' => array('/admin/appeal/departamentAdd'),
                ),
            )
        ),
        array(
            'label' => Yii::t('view_admin_menu', 'Post Delivery'),
            'url' => array('/admin/postDelivery'),
            'items' => array(
                array(
                    'label' => Yii::t('view_admin_menu', 'Post Delivery List'),
                    'url' => array('/admin/postDelivery/list'),
                ),
                array(
                    'label' => Yii::t('view_admin_menu', 'Post Delivery Add'),
                    'url' => array('/admin/postDelivery/add'),
                ),
            )
        ),

        array(
            'label' => Yii::t('view_admin_menu', 'Users, BC'),
            'url' => array('/admin/user'),
            'items' => array(
                array(
                    'label' => Yii::t('view_admin_menu', 'Users List'),
                    'url' => array('/admin/user/list'),
                ),
                array(
                    'label' => Yii::t('view_admin_menu', 'User Add'),
                    'url' => array('/admin/User/add'),
                ),
                array(
                    'label' => Yii::t('view_admin_menu', 'Business Centers List'),
                    'url' => array('/admin/businessCenter/list'),
                ),
                array(
                    'label' => Yii::t('view_admin_menu', 'Business Center Add'),
                    'url' => array('/admin/businessCenter/add'),
                ),
            )
        ),

        array(
            'label' => Yii::t('view_admin_menu', 'Reports'),
            'url' => array('/admin/report'),
        ),

        array(
            'label' => Yii::t('view_admin_menu', 'System'),
            'url' => array('/admin'),
            'items' => array(
                array(
                    'label' => Yii::t('view_admin_menu', 'Settings'),
                    'url' => array('/admin/settings'),
                ),
                array(
                    'label' => Yii::t('view_admin_menu', 'Clean'),
                    'url' => array('/admin/clean'),
                ),
                array(
                    'label' => Yii::t('view_admin_menu', 'Web Console'),
                    'url' => array('/admin/webShell'),
                ),
            )
        ),
    ));
}

$this->widget(
    'booster.widgets.TbNavbar',
    array(
        'brand' => Yii::app()->name,
        'brandUrl' => array('/admin/index'),
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
                        'label' => Yii::t('view_admin_menu', 'Login'),
                        'url' => array('auth/login'),
                        'visible' => Yii::app()->user->isGuest
                    ),
                    array(
                        'label' => Yii::t('view_admin_menu', 'Logout'),
                        'url' => array('auth/logout'),
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