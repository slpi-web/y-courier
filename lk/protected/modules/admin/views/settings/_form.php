<?php
/**
 * @var $this SettingsController
 * @var $model SettingsForm
 */

$form = $this->beginWidget(
    'booster.widgets.TbActiveForm',
    array(
        'id' => 'settings-form',
        'type' => 'horizontal',
        'htmlOptions' => array('class' => 'well'),
    )
);

$this->beginClip('system');
echo '<hr class="spacer" />';
echo $form->dropDownListGroup($model, 'enableCaptcha', array(
    'widgetOptions' => array(
        'data' => $model->getStatusLabels(),
        'htmlOptions' => array(),
    )
));
echo $form->dropDownListGroup($model, 'allowResetPassword', array(
    'widgetOptions' => array(
        'data' => $model->getStatusLabels(),
        'htmlOptions' => array(),
    )
));
$this->endClip();

$this->beginClip('post-delivery');
echo '<hr class="spacer" />';
echo $form->textAreaGroup($model, 'postDeliveryEmailList');
echo $form->textFieldGroup($model, 'postDeliveryFileTypes');
echo $form->maskedTextFieldGroup($model, 'postDeliveryMaxFileSize', array(
    'widgetOptions' => array(
        'mask' => '9?99999',
        'htmlOptions' => array(),
    )
));
$this->endClip();

$this->beginClip('cse-delivery');
echo '<hr class="spacer" />';
echo $form->textFieldGroup($model, 'cseLogin');
echo $form->passwordFieldGroup($model, 'csePassword');
echo $form->dropDownListGroup($model, 'cseDefaultCountry', array(
    'widgetOptions' => array(
        'data' => DataReceiver::getCseCountryList(),
        'htmlOptions' => array(),
    )
));
echo $form->dropDownListGroup($model, 'cseDefaultUrgency', array(
    'widgetOptions' => array(
        'data' => $model->getCseUrgencyLabels(),
        'htmlOptions' => array(),
    )
));
echo $form->dropDownListGroup($model, 'cseCompany', array(
    'widgetOptions' => array(
        'data' => $model->getCseCompanyLabels(),
        'htmlOptions' => array(),
    )
));
echo $form->dropDownListGroup($model, 'cseContactPerson', array(
    'widgetOptions' => array(
        'data' => $model->getCseContactPersonLabels(),
        'htmlOptions' => array(),
    )
));
echo $form->select2Group($model, 'cseNotifyAppealDepartaments', array(
    'widgetOptions' => array(
        'data' => DataReceiver::getAppealDepartamentsList(),
        'asDropDownList' => true,
        'htmlOptions' => array(
            'multiple' => 'multiple',
        ),
    )
));
$this->endClip();

$this->beginClip('page-content');
echo '<hr class="spacer" />';
echo $form->redactorGroup($model, 'clientIndexPageContent', array(
    'wrapperHtmlOptions' => array(
    ),
    'widgetOptions' => array(
        'editorOptions' => array(
            'plugins' => array('clips', 'fontfamily'),
            'imageUpload' => Yii::app()->createUrl('/admin/settings/AjaxImageUpload'),
            'fileUpload'  => Yii::app()->createUrl('/admin/settings/AjaxFileUpload'),
            'imageGetJson'=> Yii::app()->createUrl('/admin/settings/AjaxImageChoose'),
        ),
    )
));
echo $form->redactorGroup($model, 'workerIndexPageContent', array(
    'wrapperHtmlOptions' => array(
    ),
    'widgetOptions' => array(
        'editorOptions' => array(
            'plugins' => array('clips', 'fontfamily'),
            'imageUpload' => Yii::app()->createUrl('/admin/settings/AjaxImageUpload'),
            'fileUpload'  => Yii::app()->createUrl('/admin/settings/AjaxFileUpload'),
            'imageGetJson'=> Yii::app()->createUrl('/admin/settings/AjaxImageChoose'),
        ),
    )
));
echo $form->redactorGroup($model, 'clientDebtLimitPageContent', array(
    'wrapperHtmlOptions' => array(
    ),
    'widgetOptions' => array(
        'editorOptions' => array(
            'plugins' => array('clips', 'fontfamily'),
            'imageUpload' => Yii::app()->createUrl('/admin/settings/AjaxImageUpload'),
            'fileUpload'  => Yii::app()->createUrl('/admin/settings/AjaxFileUpload'),
            'imageGetJson'=> Yii::app()->createUrl('/admin/settings/AjaxImageChoose'),
        ),
    )
));
$this->endClip();

$this->widget('booster.widgets.TbTabs', array(
    'type' => 'tabs',
    'tabs' => array(
        array('label' => Yii::t('view_admin', 'settings tab - system'), 'content' => $this->clips['system'], 'active' => true),
        array('label' => Yii::t('view_admin', 'settings tab - post delivery'), 'content' => $this->clips['post-delivery']),
        array('label' => Yii::t('view_admin', 'settings tab - cse delivery'), 'content' => $this->clips['cse-delivery']),
        array('label' => Yii::t('view_admin', 'settings tab - page content'), 'content' => $this->clips['page-content']),
    ),
));
?>

<div class="text-center">
    <?php $this->widget(
        'ext.WLadda.TbLaddaButton',
        array('buttonType' => 'submit', 'label' => Yii::t('view_admin', 'Save'), 'htmlOptions' => array('class' => 'btn-primary'))
    ); ?>
</div>

<?php $this->endWidget(); ?>