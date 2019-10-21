<?php

class WHistoryButton extends CWidget
{

    public $label;
    public $url = '';
    public $ajaxUrl = null;
    public $htmlOptions = array(
        'class' => 'btn btn-primary'
    );

    protected $baseUrl = null;

    public function init()
    {
        if (!$this->id)
            $this->id = $this->getId();

        if (!$this->label)
            $this->label = Yii::t('model_history_widget', 'Show history');

        if (!$this->ajaxUrl)
            $this->ajaxUrl = $this->url;

        $this->htmlOptions['id'] = $this->id;

        $dir = dirname(__FILE__) . DIRECTORY_SEPARATOR;
        $this->baseUrl = Yii::app()->getAssetManager()->publish($dir . 'assets', false, -1, YII_DEBUG);
    }

    public function run()
    {
        $cs = Yii::app()->getClientScript();
        $cs->registerCoreScript('jquery');
        if (YII_DEBUG)
            $cs->registerScriptFile($this->baseUrl . '/js/iframeResizer.js', CClientScript::POS_HEAD);
        else
            $cs->registerScriptFile($this->baseUrl . '/js/iframeResizer.min.js', CClientScript::POS_HEAD);

        $frameHtml = '<h2>'.Yii::t('model_history_widget', 'Edit history').'</h2>';
        $frameHtml .= CHtml::openTag('iframe', array(
            'id' => $this->id.'-frame',
            'src' => CHtml::normalizeUrl($this->ajaxUrl),
            'style' => 'width:100%;',
            'scrolling' => 'no',
            'frameborder' => 0,
        ));
        $frameHtml .= CHtml::closeTag('iframe');
        $cs->registerScript('WHistoryButton#'.$this->id,
            '$("#'.$this->id.'").click(function(e){
                $(this).hide();
                $("'.CJavaScript::quote($frameHtml).'").insertAfter(this);
                $("#'.$this->id.'-frame").iFrameResize({
                    log: true,
                    autoResize: true,
                    interval: 32
                });
                e.preventDefault();
                return false;
            });',
            CClientScript::POS_READY);

        echo CHtml::link($this->label, $this->url, $this->htmlOptions);
    }

}