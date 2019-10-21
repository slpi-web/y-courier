<?php

class PostDeliveryController extends BaseWorkerController
{

    public $defaultAction = 'list';

    public function actions()
    {
    }

    public function actionList()
    {
        $model = new PostDeliveryWorker('search');
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
        $model = new PostDeliveryWorker();

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

    public function actionEdit($id)
    {
        $model = $this->loadModel($id);

        $params = Yii::app()->request->getPost(get_class($model));
        if (is_array($params)) {
            $model->attributes = $params;

            if ($model->save())
                $this->redirect(array('list'));
        }

        $this->render('edit', array(
            'model' => $model,
        ));
    }

    protected function loadModel($id)
    {
        $model = PostDeliveryWorker::model()->findByPk($id);

        if (!$model)
            throw new CHttpException(404, Yii::t('error', 'error404'));

        return $model;
    }

} 