<?php

class AppealController extends BaseClientController
{

    public $defaultAction = 'list';

    public function actions()
    {
    }

    public function actionList()
    {
        $model = new AppealClient('search');
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
        $model = new AppealClient();
        $model->user_id = Yii::app()->user->getId();

        $params = Yii::app()->request->getPost(get_class($model));
        if (is_array($params)) {
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
        $messageParams = array();
        if ($messageModel)
            $messageParams = Yii::app()->request->getPost(get_class($messageModel));

        $params = Yii::app()->request->getPost(get_class($model));
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

    public function actionClose($id)
    {
        $model = $this->loadModel($id);

        $model->status = Appeal::STATUS_CLOSED;
        $model->save();

        $this->redirect(array('list'));
    }

    protected function loadModel($id)
    {
        $model = AppealClient::model()->byUserId(Yii::app()->user->getId())->findByPk($id);

        if (!$model)
            throw new CHttpException(404, Yii::t('error', 'error404'));

        return $model;
    }

} 