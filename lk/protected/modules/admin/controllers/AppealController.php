<?php

class AppealController extends BaseAdminController
{

    public $defaultAction = 'list';

    public function actions()
    {
    }

    public function actionList()
    {
        $model = new AppealAdmin('search');
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
        $model = new AppealAdmin();

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

    public function actionEdit($id)
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
                $this->redirect(array('edit', 'id' => $id));
        }

        $this->render('edit', array(
            'model' => $model,
            'messagesDataProvider' => $model->getMessagesDataProvider(array(
                'criteria' => array(
                    'with' => array(
                        'user' => array(
                            'select' => array('id', 'display'),
                        )
                    )
                ),
                'pagination' => false,
            )),
            'messageModel' => $model->getNewMessageModel(),
        ));
    }

    public function actionHistory($id, $light = false)
    {
        $model = $this->loadModel($id);

        if (!$light) {
            if (Yii::app()->request->isAjaxRequest) {
                header( 'Content-type: application/json' );
                $this->renderPartial('history', array(
                    'model' => $model,
                    'iframed' => false,
                ));
                Yii::app()->end();
            }

            $this->render('history', array(
                'model' => $model,
                'iframed' => false,
            ));
        } else {
            if (Yii::app()->request->isAjaxRequest) {
                header( 'Content-type: application/json' );
                $this->renderPartial('history', array(
                    'model' => $model,
                    'iframed' => true,
                ));
                Yii::app()->end();
            }

            $this->renderPartial('history', array(
                'model' => $model,
                'iframed' => true,
            ), false, true);
        }
    }

    public function actionDelete($id)
    {
        $model = $this->loadModel($id);

        $model->delete();

        $this->redirect(array('list'));
    }

    public function actionDeleteAppealMessage($id)
    {
        $model = AppealMessage::model()->findByPk($id);

        if ($model) {
            $model->delete();

            $this->redirect(array('edit', 'id' => $model->appeal_id));
        } else
            throw new CHttpException(404, Yii::t('error', 'error404'));
    }

    /**
     * @param $id
     * @return AppealAdmin
     * @throws CHttpException
     */
    protected function loadModel($id)
    {
        $model = AppealAdmin::model()->findByPk($id);

        if (!$model)
            throw new CHttpException(404, Yii::t('error', 'error404'));

        return $model;
    }

    public function actionDepartamentList()
    {
        $model = new AppealDepartamentAdmin('search');
        $model->unsetAttributes();

        $params = Yii::app()->request->getQuery(get_class($model));
        if (is_array($params))
            $model->attributes = $params;

        if (Yii::app()->request->isAjaxRequest) {
            header( 'Content-type: application/json' );
            $this->renderPartial('_departament_grid', compact('model'));
            Yii::app()->end();
        }

        $this->render('departament_list', array(
            'model' => $model,
        ));
    }

    public function actionDepartamentAdd()
    {
        $model = new AppealDepartamentAdmin();

        $params = Yii::app()->request->getPost(get_class($model));
        if (is_array($params)) {
            $model->attributes = $params;

            if ($model->save())
                $this->redirect(array('departamentList'));
        }

        $this->render('departament_add', array(
            'model' => $model,
        ));
    }

    public function actionDepartamentEdit($id)
    {
        $model = $this->loadDepartamentModel($id);

        $params = Yii::app()->request->getPost(get_class($model));
        if (is_array($params)) {
            $model->attributes = $params;

            if ($model->save())
                $this->redirect(array('departamentList'));
        }

        $this->render('departament_edit', array(
            'model' => $model,
        ));
    }

    public function actionDepartamentDelete($id)
    {
        $model = $this->loadDepartamentModel($id);

        $model->delete();

        $this->redirect(array('departamentList'));
    }

    /**
     * @param $id
     * @return AppealDepartamentAdmin
     * @throws CHttpException
     */
    protected function loadDepartamentModel($id)
    {
        $model = AppealDepartamentAdmin::model()->findByPk($id);

        if (!$model)
            throw new CHttpException(404, Yii::t('error', 'error404'));

        return $model;
    }



} 