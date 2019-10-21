<?php
/**
 * @var $this CseDeliveryController
 */

$this->pageTitle = Yii::t('client_pagetitle', 'Cse AddressBook Add');

?>

<div class="modal-header">
    <a class="close" data-dismiss="modal">&times;</a>
    <h4><?php echo $this->pageTitle; ?></h4>
</div>

<?php
$form = $this->beginWidget(
    'booster.widgets.TbActiveForm',
    array(
        'id' => 'cse-addressbook-form',
        'type' => 'horizontal',
        'htmlOptions' => array(
        ),
    )
); ?>

<div class="modal-body">

    <?php
    echo $form->hiddenField($model, 'type');
    echo $form->textFieldGroup($model, 'name');
    echo $form->textFieldGroup($model, 'contact');
    $this->widget('ext.Cse.CseCitySelectorWidget', array(
        'id' => 'address',
        'form' => $form,
        'model' => $model,
        'type' => '',
        'ajaxCountryInfoUrl' => array('/client/ajax/getCseCountyInfo'),
        'ajaxRegionAutocompleteUrl' => array('/client/ajax/autocompleteCseRegion'),
        'ajaxAreaAutocompleteUrl' => array('/client/ajax/autocompleteCseArea'),
        'ajaxCityAutocompleteUrl' => array('/client/ajax/autocompleteCseCity'),
    ));
    echo $form->textFieldGroup($model, 'address');
    echo $form->textFieldGroup($model, 'post_index');
    echo $form->textFieldGroup($model, 'phone');
    echo $form->textFieldGroup($model, 'email');
    echo $form->textAreaGroup($model, 'info', array(
        'widgetOptions' => array(
            'htmlOptions' => array(
                'class' => 'vresize',
            ),
        ),
    ));
    ?>

</div>

<div class="modal-footer">
    <?php $this->widget(
        'ext.WLadda.TbLaddaButton',
        array('buttonType' => 'submit', 'label' => Yii::t('view_client', 'Save'), 'htmlOptions' => array('id' => 'popup-submit', 'class' => 'btn-primary', 'name' => 'save'))
    ); ?>
    <?php $this->widget(
        'booster.widgets.TbButton',
        array(
            'label' => Yii::t('view_client', 'Close'),
            'url' => '#',
            'htmlOptions' => array(
                'id' => 'popup-close',
                'data-dismiss' => 'modal'
            ),
        )
    ); ?>
</div>
<?php $this->endWidget(); ?>

<?php
$cs = Yii::app()->getClientScript();
$script = array();
if (isset($cs->scripts[CClientScript::POS_READY]['cse_city_selector-address']))
    $script[] = $cs->scripts[CClientScript::POS_READY]['cse_city_selector-address'];
    echo '<script type="text/javascript"> '.implode(' ', $script).' </script>';
