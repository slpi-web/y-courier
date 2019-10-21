<?php

class PostDeliveryController extends BaseClientController
{

    public $defaultAction = 'list';

    public function actions()
    {
    }

    public function actionList()
    {
        $model = new PostDeliveryClient('search');
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
        $model = new PostDeliveryClient();
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

    protected function loadModel($id)
    {
        $model = PostDeliveryClient::model()->byUserId(Yii::app()->user->getId())->findByPk($id);

        if (!$model)
            throw new CHttpException(404, Yii::t('error', 'error404'));

        return $model;
    }

} 