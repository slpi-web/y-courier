<?php
/**
 * @var $this ReportController
 * @var $reportHelper ReportHelper
 */

$this->pageTitle = Yii::t('worker_pagetitle', 'Report Create Progress');

$this->breadcrumbs=array(
    Yii::t('worker_pagetitle', 'Reports') => array('index'),
    Yii::t('worker_pagetitle', 'Report Create Progress'),
);
?>

<?php
$percent = $reportHelper->getPercent();
?>

    <div class="well">
        <?php $this->widget('booster.widgets.TbProgress', array(
            'striped' => true,
            'animated' => true,
            'percent' => $percent,
            'content' => $percent.'%',
            'htmlOptions' => array(
                'id' => 'progress',
            )
        )) ?>
    </div>

<?php
$cs = Yii::app()->getClientScript();
$cs->registerCoreScript('jquery');
$script = "
    var doExport = function(step) {
        $.ajax({
            cache: false,
            data: {
                'step': step
            },
            dataType: 'json',
            success: function(response) {
                if (typeof response.percent != 'undefined') {
                    $('#progress .progress-bar').css({
                        'width' : response.percent + '%'
                    }).text(response.percent + '%');

                    if ((typeof response.redirect != 'undefined') && response.redirect) {
                        $('#progress .progress-bar').css({
                            'width' : '100%'
                        }).text('100%');
                        setTimeout(function(){
                            window.location.href = response.redirect;
                        }, 1000);
                    } else {
                        if (response.nextStep) {
                            setTimeout(function(){
                                doExport(response.nextStep);
                            }, 1000);
                        }
                    }
                }
            }
        });
    }

    doExport(".$reportHelper->getNextStep().");
";
$cs->registerScript('data_export_form', $script, CClientScript::POS_READY);