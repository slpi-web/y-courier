<?php


class SettingsController extends BaseAdminController
{

    public function actions()
    {
        return array(
            'AjaxFileUpload' => array(
                'class' => 'application.modules.admin.components.actions.AFileUpload',
                'uploadCreate' => true,
            ),
            'AjaxImageUpload' => array(
                'class' => 'application.modules.admin.components.actions.AImageUpload',
                'uploadCreate' => true,
            ),
            'AjaxImageChoose' => array(
                'class' => 'application.modules.admin.components.actions.AImageList',
            ),
        );
    }

    public function actionIndex()
    {
        $file = dirname(__FILE__).'/../../../config/user_params.inc';
        $content = file_get_contents($file);
        $arr = unserialize(base64_decode($content));
        $loaded = array();
        if (!is_array($arr))
            $arr = array();
        foreach ($arr as $variable => $value) {
                $loaded[$variable] = $value;
        }

        $model = new SettingsForm();
        $model->setAttributes($loaded);

        $params = Yii::app()->request->getPost(get_class($model));

        if (is_array($params) && !empty($params)) {
            if (!isset($params['cseNotifyAppealDepartaments']))
                $params['cseNotifyAppealDepartaments'] = array();
            $model->attributes = $params;
            if ($model->validate()) {
                $config = array();
                foreach ($model->attributes as $variable => $value) {
                    if (strpos($variable, '0')!=false) {
                        $variableName = explode('0', $variable);
                        $lastVar = array_pop($variableName);
                        $configArray = &$config;
                        foreach ($variableName as $varName) {
                            if (!isset($configArray[$varName]))
                                $configArray[$varName] = array();
                            $configArray = &$configArray[$varName];
                        }
                        $configArray[$lastVar] = $value;
                    } else {
                        $config[$variable] = $value;
                    }
                }
                $str = base64_encode(serialize($config));
                file_put_contents($file, $str);
                Yii::app()->user->setFlash('success', Yii::t('view_admin', 'Settings saved.'));
                $this->redirect(array('index'));
            }
        }

        $this->render('index',array('model'=>$model));
    }
}