<?php

class AppealController extends BaseWorkerController
{

    public $defaultAction = 'list';

    public function actions()
    {
    }

    public function actionList()
    {
        $model = new AppealWorker('search');
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
        $model = new AppealWorker('add');

        $params = Yii::app()->request->getPost(get_class($model));
        if (is_array($params)) {
            if (!isset($params['appeal_departaments']))
                $params['appeal_departaments'] = array();
            $model->attributes = $params;

            if ($model->save())
                $this->redirect(array('list'));
        }

        $this->render('add', array(
            'model' => $model,
        ));
    }

    public function actionView($id)
    {
        $model = $this->loadModel($id);
        $messageModel = $model->getNewMessageModel();

        $params = Yii::app()->request->getPost(get_class($model));
        $messageParams = array();
        if ($messageModel)
            $messageParams = Yii::app()->request->getPost(get_class($messageModel));

        if (is_array($params)) {
            if (!isset($params['appeal_departaments']))
                $params['appeal_departaments'] = array();
            $model->attributes = $params;

            if ($model->save())
                $this->redirect(array('list'));
        }

        if ($messageModel && is_array($messageParams)) {
            $messageModel->attributes = $messageParams;

            if ($messageModel->save())
                $this->redirect(array('view', 'id' => $id));
        }

        $this->render('view', array(
            'model' => $model,
            'messagesDataProvider' => $model->getMessagesDataProvider(array(
                'criteria' => array(
                    'with' => array(
                        'user' => array(
                            'select' => array('id', 'type', 'display'),
                        )
                    )
                ),
                'pagination' => false,
            )),
            'messageModel' => $model->getNewMessageModel(),
        ));
    }

    protected function loadModel($id)
    {
        $model = AppealWorker::model()->workerAccessable()->findByPk($id);

        if (!$model)
            throw new CHttpException(404, Yii::t('error', 'error404'));

        return $model;
    }

} 