<?php


class ReportController extends BaseWorkerController
{

    public $defaultAction = 'index';

    public function actionIndex()
    {
        $model = new ReportForm();

        $params = Yii::app()->request->getPost(get_class($model));
        if (is_array($params)) {
            $model->presetAttributes($params);
            $model->attributes = $params;

            if ($model->validate()) {
                $params = $model->attributes;
                $helper = new ReportHelper($params);
                if ($helper && $helper->getKey()) {
                    $lastExportTime = $helper->check();
                    if ($lastExportTime) {
                        $this->redirect(array('info', 'key' => $helper->getKey()));
                    } else
                        $this->redirect(array('create', 'key' => $helper->getKey()));
                }
            }
        }

        $this->render('index', array(
            'model' => $model,
        ));
    }

    public function actionCreate($key, $step = -1)
    {
        $helper = new ReportHelper($key);
        if ($helper && $helper->isLoaded()) {
            $next = $helper->reportStep($step);
            if (Yii::app()->request->isAjaxRequest || isset($_GET['ajax'])) {
                $nextStep = $helper->getNextStep();
                $response = array(
                    'percent' => $helper->getPercent(),
                    'nextStep' => $nextStep,
                );
                if (!$next)
                    $response['redirect'] = Yii::app()->createAbsoluteUrl('/worker/report/result', array('key' => $helper->getKey()));

                header('Content-type: application/json');
                echo CJSON::encode($response);
                Yii::app()->end();
            } else {
                $this->render('report_progress', array(
                    'reportHelper' => $helper,
                ));
            }
        } else
            throw new CHttpException(404, Yii::t('error', 'error404'));
    }

    public function actionInfo($key)
    {
        $helper = new ReportHelper($key);
        if ($helper && $helper->isLoaded()) {
            $lastTime = $helper->check();
            if ($lastTime) {
                $this->render('info', array(
                    'reportHelper' => $helper,
                    'lastTime' => $lastTime,
                ));
            }
        } else
            throw new CHttpException(404, Yii::t('error', 'error404'));
    }

    public function actionResult($key)
    {
        $helper = new ReportHelper($key);
        if ($helper && $helper->isLoaded()) {
            $lastTime = $helper->check();
            if ($lastTime) {
                $this->render('result', array(
                    'reportHelper' => $helper,
                ));
                Yii::app()->end();
            }
        }

        throw new CHttpException(404, Yii::t('error', 'error404'));
    }

    public function actionDownload($key)
    {
        $helper = new ReportHelper($key);
        if ($helper && $helper->isLoaded()) {
            $helper->getResult();
            Yii::app()->end();
        } else
            throw new CHttpException(404, Yii::t('error', 'error404'));
    }

}