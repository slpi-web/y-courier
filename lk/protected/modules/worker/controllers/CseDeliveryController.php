<?php

class CseDeliveryController extends BaseWorkerController
{

    public $defaultAction = 'list';

    public function actions()
    {
    }

    public function actionList()
    {
        $model = new CseDeliveryWorker('search');
        $model->unsetAttributes();

        $params = Yii::app()->request->getQuery(get_class($model));
        if (is_array($params))
            $model->attributes = $params;

        if (Yii::app()->request->isAjaxRequest) {
            header( 'Content-type: application/json' );
            $this->renderPartial('_grid', compact('model'));
            Yii::app()->end();
        }

        $this->render('list', array(
            'model' => $model,
        ));
    }

    public function actionAdd()
    {
        $model = new CseDeliveryWorker();

        $params = Yii::app()->request->getPost(get_class($model));
        if (is_array($params)) {
            $model->presetAttributes($params);
            $model->attributes = $params;

            if ($model->save()) {
                if ($model->isSynchronizeAvailable() && isset($_POST['sync'])) {
                    if ($model->synchronize())
                        $this->redirect(array('list'));
                    else
                        Yii::app()->user->setFlash('error', Yii::t('view_cse_form', 'Cant synchronize. Cse service returns errors.'));
                } else
                    $this->redirect(array('list'));
            }
        }

        $this->render('add', array(
            'model' => $model,
        ));
    }

    public function actionEdit($id)
    {
        $model = $this->loadModel($id);
        if ($model->status != CseDelivery::STATUS_NOT_VERIFIED && $model->status != CseDelivery::STATUS_NOT_SYNCED) {
            throw new CHttpException(404, Yii::t('error', 'error404'));
            Yii::app()->end();
        }

        $params = Yii::app()->request->getPost(get_class($model));
        if (is_array($params)) {
            if ($model->status == CseDelivery::STATUS_SYNC) {
                Yii::app()->user->setFlash('error', Yii::t('view_cse_form', 'Cant save. Please wait for the synchronization.'));
            }

            $model->presetAttributes($params);
            $model->attributes = $params;

            if ($model->status != CseDelivery::STATUS_SYNC && $model->status != CseDelivery::STATUS_SYNCED) {
                if ($model->save()) {
                    if ($model->isSynchronizeAvailable() && isset($_POST['sync'])) {
                        if ($model->synchronize())
                            $this->redirect(array('list'));
                        else
                            Yii::app()->user->setFlash('error', Yii::t('view_cse_form', 'Cant synchronize. Cse service returns errors.'));
                    } else
                        $this->redirect(array('list'));
                }
            }
        }

        $this->render('edit', array(
            'model' => $model,
        ));
    }

    public function actionGetWaybill($id)
    {
        $model = $this->loadModel($id);

        if ($model->isWaybillAvailable()) {
            if (!$model->downloadWaybillWeb())
                throw new CHttpException(500, Yii::t('error', 'error500'));
        } else
            throw new CHttpException(404, Yii::t('error', 'error404'));
    }

    protected function loadModel($id)
    {
        $model = CseDeliveryWorker::model()->findByPk($id);

        if (!$model)
            throw new CHttpException(404, Yii::t('error', 'error404'));

        return $model;
    }

    public function actionAddressbook($action = 'open', $type='', $user_id='', $id = '')
    {
        if ($action=='save') {
            $model = new CseAddressBook('add');

            $params = Yii::app()->request->getPost(get_class($model));
            if (is_array($params)) {
                if (isset($params['user_id']))
                    $model->user_id = $params['user_id'];
                $model->attributes = $params;

                if (isset($_POST['save'])) {
                    if ($model->save()) {
                        Yii::app()->end();
                    }
                }
            }
            $this->renderPartial('addressbook_save', array(
                'model' => $model,
            ));
        } elseif ($action == 'open') {
            $model = new CseAddressBook('search');
            $model->user_id = $user_id;
            $model->setType($type);

            $params = Yii::app()->request->getQuery(get_class($model));
            if (is_array($params))
                $model->attributes = $params;

            if (Yii::app()->request->isAjaxRequest) {
                header( 'Content-type: application/json' );
                $this->renderPartial('_addressbook_grid', compact('model'));
                Yii::app()->end();
            }

            $this->renderPartial('addressbook_open', array(
                'model' => $model,
            ));
        } elseif ($action == 'delete') {
            $model = CseAddressBook::model()->findByPk($id);
            if (!$model)
                throw new CHttpException(404, Yii::t('error', 'error404'));

            $user_id = $model->user_id;
            $type = $model->type;
            $model->delete();

            $model = new CseAddressBook('search');
            $model->user_id = $user_id;
            $model->setType($type);

            header( 'Content-type: application/json' );
            $this->renderPartial('_addressbook_grid', compact('model'));
            Yii::app()->end();
        }
    }

    public function actionCalc()
    {
        $model = new CseDeliveryCalcForm();

        $params = Yii::app()->request->getPost(get_class($model));
        if (is_array($params)) {
            $model->attributes = $params;

            if ($model->validate()) {
                $model->renderResponse('calc_result');
                Yii::app()->end();
            }
        }

        $this->render('calc', array(
            'model' => $model,
        ));
    }

}