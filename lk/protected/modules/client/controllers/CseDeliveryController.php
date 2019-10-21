<?php

class CseDeliveryController extends BaseClientController
{

    public $defaultAction = 'list';

    public function actions()
    {
    }

    public function actionList()
    {
        $model = new CseDeliveryClient('search');
        $model->unsetAttributes();
        $model->user_id = Yii::app()->user->getId();

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
        $model = new CseDeliveryClient();
        $model->user_id = Yii::app()->user->getId();

        $params = Yii::app()->request->getPost(get_class($model));
        if (is_array($params)) {
            if (isset($params['status']))
                unset($params['status']);
            $model->presetAttributes($params);
            $model->attributes = $params;

            if ($model->save())
                $this->redirect(array('list'));
        }

        $this->render('add', array(
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
        $model = CseDeliveryClient::model()->byUserId(Yii::app()->user->getId())->findByPk($id);

        if (!$model)
            throw new CHttpException(404, Yii::t('error', 'error404'));

        return $model;
    }

    public function actionAddressbook($action = 'open', $type='', $id = '')
    {
        if ($action=='save') {
            $model = new CseAddressBook('add');

            $params = Yii::app()->request->getPost(get_class($model));
            if (is_array($params)) {
                $model->user_id = Yii::app()->user->getId();
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
            $model->user_id = Yii::app()->user->getId();
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
            $model = CseAddressBook::model()->findByattributes(array(
                'id' => $id,
                'user_id' => Yii::app()->user->getId(),
            ));
            if (!$model)
                throw new CHttpException(404, Yii::t('error', 'error404'));

            $type = $model->type;
            $model->delete();

            $model = new CseAddressBook('search');
            $model->user_id = Yii::app()->user->getId();
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