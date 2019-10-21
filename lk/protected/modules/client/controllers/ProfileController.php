<?php

class ProfileController extends BaseClientController
{

    public $defaultAction = 'index';

    public function actionIndex()
    {
        $model = Yii::app()->user->loadUser('UserClient');

        if ($model) {
            $params = Yii::app()->request->getPost(get_class($model));
            if (is_array($params)) {
                $model->attributes = $params;

                if ($model->save())
                    $this->redirect(array('list'));
            }

            $this->render('index', array(
                'model' => $model,
            ));
        }
    }

}