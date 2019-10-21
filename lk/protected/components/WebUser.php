<?php

class WebUser extends CWebUser
{

    private $_model;

    public function init()
    {
        $conf = Yii::app()->session->cookieParams;
        $this->identityCookie = array(
            'path' => $conf['path'],
            'domain' => $conf['domain'],
        );
        parent::init();
    }

    /**
     * Load User model
     * @param string $class
     * @return User
     */
    public function loadUser($class = null)
    {
        if (!$class) {
            if ($this->_model==null && $this->id) {
                $this->_model = User::model()->findByPk($this->id);
            }
            return $this->_model;
        } else {
            return User::model($class)->findByPk($this->id);
        }
    }

    public function isAdmin()
    {
        $user = $this->loadUser();
        if ($user)
            return $user->isAdmin();
        return false;
    }

    public function isWorker()
    {
        $user = $this->loadUser();
        if ($user)
            return $user->isWorker();
        return false;
    }

    public function isClient()
    {
        $user = $this->loadUser();
        if ($user)
            return $user->isClient();
        return false;
    }

    public function getState($key,$defaultValue=null)
    {
        $key=$this->getStateKeyPrefix().$key;
        return isset($_SESSION[$key]) ? $_SESSION[$key] : $defaultValue;
    }

    protected function afterLogin($fromCookie)
    {
        parent::afterLogin($fromCookie);

        $user = User::model()->findByPk($this->id);
        $user->onLogin();
    }


}