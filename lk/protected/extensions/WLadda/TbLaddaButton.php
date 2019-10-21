<?php

Yii::import('booster.widgets.TbButton');

class TbLaddaButton extends TbButton
{

    public $style = 'expand-left';

    public $options = array();

    public function init() {

        $id = $this->getId();

        $this->widget('ext.WLadda.WLadda');

        if (false === $this->visible) {
            return;
        }

        $classes = array('btn', 'ladda-button');

        if ($this->isValidContext()) {
            $classes[] = 'btn-' . $this->getContextClass();
        }

        if($this->buttonType == self::BUTTON_LINK) {
            $classes[] = 'btn-link';
        }

        $validSizes = array(
            self::SIZE_LARGE,
            self::SIZE_DEFAULT,
            self::SIZE_SMALL,
            self::SIZE_EXTRA_SMALL
        );

        if (isset($this->size) && in_array($this->size, $validSizes)) {
            $classes[] = self::$sizeClasses[$this->size];
        }

        if ($this->block) {
            $classes[] = 'btn-block';
        }

        if ($this->active) {
            $classes[] = 'active';
        }

        if ($this->disabled) {
            $disableTypes = array(
                self::BUTTON_BUTTON,
                self::BUTTON_SUBMIT,
                self::BUTTON_RESET,
                self::BUTTON_AJAXBUTTON,
                self::BUTTON_AJAXSUBMIT,
                self::BUTTON_INPUTBUTTON,
                self::BUTTON_INPUTSUBMIT
            );

            if (in_array($this->buttonType, $disableTypes)) {
                $this->htmlOptions['disabled'] = 'disabled';
            }

            $classes[] = 'disabled';
        }

        if (!isset($this->url) && isset($this->htmlOptions['href'])) {
            $this->url = $this->htmlOptions['href'];
            unset($this->htmlOptions['href']);
        }

        if ($this->encodeLabel) {
            $this->label = CHtml::encode($this->label);
        }

        if ($this->hasDropdown()) {
            if (!isset($this->url)) {
                $this->url = '#';
            }

            $classes[] = 'dropdown-toggle';
            $this->label .= ' <span class="caret"></span>';
            $this->htmlOptions['data-toggle'] = 'dropdown';
        }

        if (!empty($classes)) {
            $classes = implode(' ', $classes);
            if (isset($this->htmlOptions['class'])) {
                $this->htmlOptions['class'] .= ' ' . $classes;
            } else {
                $this->htmlOptions['class'] = $classes;
            }
        }

        if (isset($this->icon)) { // no need for implode as newglyphicon only supports one icon
            if (strpos($this->icon, 'icon') === false && strpos($this->icon, 'fa') === false) {
                $this->icon = 'glyphicon glyphicon-' . $this->icon;
                $this->label = '<span class="' . $this->icon . '"></span> ' . $this->label;
            } else { // to support font awesome icons
                $this->label = '<i class="' . $this->icon . '"></i> ' . $this->label;
            }
        }

        if (!$this->hasDropdown()) {
            $this->label = '<span class="ladda-label">'.$this->label.'</span>';
        }

        if (!isset($this->htmlOptions['id'])) {
            $this->htmlOptions['id'] = $id;
        }

        if (isset($this->toggle)) {
            $this->htmlOptions['data-toggle'] = 'button';
        }

        if (isset($this->loadingText)) {
            $this->htmlOptions['data-loading-text'] = $this->loadingText;
        }

        if (isset($this->completeText)) {
            $this->htmlOptions['data-complete-text'] = $this->completeText;
        }

        if ($this->style)
            $this->htmlOptions['data-style'] = $this->style;

        if (!empty($this->tooltip) && !$this->toggle) {
            if (!is_array($this->tooltipOptions)) {
                $this->tooltipOptions = array();
            }

            $this->htmlOptions['data-toggle'] = 'tooltip';
            foreach ($this->tooltipOptions as $key => $value) {
                $this->htmlOptions['data-' . $key] = $value;
            }

            /**
             * Encode delay option
             * @link http://getbootstrap.com/2.3.2/javascript.html#tooltips
             */
            if (isset($this->htmlOptions['data-delay']) && is_array($this->htmlOptions['data-delay'])) {
                $this->htmlOptions['data-delay'] = CJSON::encode($this->htmlOptions['data-delay']);
            }
        }

        $clientScript = Yii::app()->getClientScript();
        $clientScript->registerCoreScript('jquery');
        $clientScript->registerScript('LaddaButton#'.$id, '$("#'.$id.'").ladda("bind", '.CJavaScript::jsonEncode($this->options).');');
    }

    protected function createButton() {

        switch ($this->buttonType) {
            case self::BUTTON_LINK:
                return CHtml::link($this->label, $this->url, $this->htmlOptions);

            case self::BUTTON_SUBMIT:
                $this->htmlOptions['type'] = 'submit';
                return CHtml::htmlButton($this->label, $this->htmlOptions);

            case self::BUTTON_RESET:
                $this->htmlOptions['type'] = 'reset';
                return CHtml::htmlButton($this->label, $this->htmlOptions);

            case self::BUTTON_SUBMITLINK:
                return CHtml::linkButton($this->label, $this->htmlOptions);

            case self::BUTTON_AJAXLINK:
                return CHtml::ajaxLink($this->label, $this->url, $this->ajaxOptions, $this->htmlOptions);

            case self::BUTTON_AJAXBUTTON:
                $this->ajaxOptions['url'] = $this->url;
                $this->htmlOptions['ajax'] = $this->ajaxOptions;
                return CHtml::htmlButton($this->label, $this->htmlOptions);

            case self::BUTTON_AJAXSUBMIT:
                $this->ajaxOptions['type'] = isset($this->ajaxOptions['type']) ? $this->ajaxOptions['type'] : 'POST';
                $this->ajaxOptions['url'] = $this->url;
                $this->htmlOptions['type'] = 'submit';
                $this->htmlOptions['ajax'] = $this->ajaxOptions;
                return CHtml::htmlButton($this->label, $this->htmlOptions);

            case self::BUTTON_INPUTBUTTON:
                return CHtml::button($this->label, $this->htmlOptions);

            case self::BUTTON_INPUTSUBMIT:
                $this->htmlOptions['type'] = 'submit';
                return CHtml::button($this->label, $this->htmlOptions);

            case self::BUTTON_TOGGLE_RADIO:
                return $this->createToggleButton('radio');

            case self::BUTTON_TOGGLE_CHECKBOX:
                return $this->createToggleButton('checkbox');

            default:
                return CHtml::htmlButton($this->label, $this->htmlOptions);
        }
    }

}