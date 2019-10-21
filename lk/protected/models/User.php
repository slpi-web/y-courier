<?php

class User extends BaseUser
{

    const STATUS_INACTIVE = 0;
    const STATUS_ACTIVE = 1;
    const STATUS_BANNED = 2;

    const TYPE_CLIENT = 0;
    const TYPE_WORKER = 4;
    const TYPE_ADMIN = 9;

    protected $oldUserType = null;

    public $business_centers = null;

    public $appeal_departaments = null;

    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }

    public function rules()
    {
        return array();
    }

    public function relations()
    {
        return array(
            'appeals' => array(self::HAS_MANY, 'Appeal', 'user_id'),
            'cseAddressBooks' => array(self::HAS_MANY, 'CseAddressBook', 'user_id'),
            'cseDeliveries' => array(self::HAS_MANY, 'CseDelivery', 'user_id'),
            'postDeliveries' => array(self::HAS_MANY, 'PostDelivery', 'user_id'),
            'appealDepartaments' => array(self::MANY_MANY, 'AppealDepartament', 'user_to_appeal_departament(user_id, appeal_departament_id)'),
            'businessCenters' => array(self::MANY_MANY, 'BusinessCenter', 'user_to_business_center(user_id, business_center_id)'),
        );
    }

    public function attributeLabels()
    {
        $labels = parent::attributeLabels();
        $labels['business_centers'] = Yii::t('model_user', 'business_centers');
        $labels['appeal_departaments'] = Yii::t('model_user', 'appeal_departaments');

        return $labels;
    }

    public function getStatusLabels()
    {
        return array(
            self::STATUS_INACTIVE => Yii::t('model_user', 'Status-inactive'),
            self::STATUS_ACTIVE => Yii::t('model_user', 'Status-active'),
            self::STATUS_BANNED => Yii::t('model_user', 'Status-banned'),
        );
    }

    public function getStatusLabel()
    {
        $labels = $this->getStatusLabels();
        if (isset($labels[$this->status]))
            return $labels[$this->status];

        return '';
    }

    public function getTypeLabels()
    {
        return array(
            self::TYPE_CLIENT => Yii::t('model_user', 'Type-client'),
            self::TYPE_WORKER => Yii::t('model_user', 'Type-worker'),
            self::TYPE_ADMIN => Yii::t('model_user', 'Type-admin'),
        );
    }

    public function getTypeLabel()
    {
        $labels = $this->getTypeLabels();
        if (isset($labels[$this->type]))
            return $labels[$this->type];

        return '';
    }

    public static function findUser($email)
    {
        return self::model()->findByAttributes(array('email'=>$email));
    }

    public function init()
    {
        $this->status = self::STATUS_INACTIVE;
        $this->type = self::TYPE_CLIENT;
        $this->oldUserType = self::TYPE_CLIENT;
        $this->debt = 0;
        $this->debt_limit = 0;

        parent::init();
    }

    protected function afterFind()
    {
        parent::afterFind();
        $this->oldUserType = $this->type;
    }

    public function validatePassword($password)
    {
        //$this->password_hash = $this->hashPassword($password);
        //$this->save(false);
        return CPasswordHelper::verifyPassword($password, $this->password_hash);
    }

    public function hashPassword($password)
    {
        return CPasswordHelper::hashPassword($password);
    }

    public function onLogin()
    {
        $this->saveAttributes(array(
            'last_login_time' => time(),
        ));
    }

    public function type($type)
    {
        $tableAlias = $this->getTableAlias();

        $this->getDbCriteria()->mergeWith(array(
            'condition' => $tableAlias.'.type = :selected_type',
            'params' => array(
                ':selected_type' => $type,
            ),
        ));

        return $this;
    }

    public function scopes()
    {
        $tableAlias = $this->getTableAlias();

        return array(
            'active' => array(
                'condition' => $tableAlias.'.status = :status_active',
                'params' => array(
                    ':status_active' => self::STATUS_ACTIVE,
                ),
            ),
            'registered' => array(
                'condition' => $tableAlias.'.password_hash != :empty_password AND '.$tableAlias.'.status != :status_inactive',
                'params' => array(
                    ':empty_password' => '',
                    ':status_inactive' => self::STATUS_INACTIVE,
                ),
            ),
            'client' => array(
                'condition' => $tableAlias.'.type = :type_client',
                'params' => array(
                    ':type_client' => self::TYPE_CLIENT,
                ),
            ),
            'worker' => array(
                'condition' => $tableAlias.'.type = :type_worker',
                'params' => array(
                    ':type_worker' => self::TYPE_WORKER,
                ),
            ),
            'admin' => array(
                'condition' => $tableAlias.'.type = :type_admin',
                'params' => array(
                    ':type_admin' => self::TYPE_ADMIN,
                ),
            ),
        );
    }

    public function isRegistered()
    {
        if ($this->password_hash != '' && $this->status != self::STATUS_INACTIVE)
            return true;
        return false;
    }

    public function isClient()
    {
        if ($this->type == self::TYPE_CLIENT)
            return true;

        return false;
    }

    public function isWorker()
    {
        if ($this->type == self::TYPE_WORKER)
            return true;

        return false;
    }

    public function isAdmin()
    {
        if ($this->type == self::TYPE_ADMIN)
            return true;

        return false;
    }

    public function generateSecurityToken($prefix = '')
    {
        $this->security_token = $prefix.md5(uniqid());
        return $this->security_token;
    }

    public function validateSecurityToken($token, $prefix = '')
    {
        if ($token == $this->security_token)
            if ($prefix) {
                if (strpos($token, $prefix) === 0)
                    return true;
            } else
                return true;

        return false;
    }

    public function cleanSecurityToken()
    {
        $this->security_token = '';
    }

    protected function beforeSave()
    {
        if(parent::beforeSave()) {

            if ($this->type == self::TYPE_CLIENT) {
                $this->display = $this->organization.' ('.$this->inn.')';
            } else
                $this->display = $this->email;

            if ($this->isNewRecord)
                $this->create_time = time();

            return true;
        }

        return false;
    }

    public function getBusinessCentersId()
    {
        return Yii::app()->db->createCommand()->select('business_center_id')->from('user_to_business_center')->where('user_id = :user_id', array(':user_id' => $this->id))->queryColumn();
    }

    public function setBusinessCentersId($businessCenters)
    {
        Yii::app()->db->createCommand()->delete('user_to_business_center', 'user_id = :current_user_id', array(':current_user_id' => $this->id));
        $data = array();
        if (!is_array($businessCenters) && is_int($businessCenters))
            $businessCenters = array($businessCenters);
        if (is_array($businessCenters)) {
            foreach ($businessCenters as $businessCenter)
                $data[] = array('user_id' => $this->id, 'business_center_id' => $businessCenter);
            Yii::app()->db->schema->commandBuilder->createMultipleInsertCommand('user_to_business_center', $data)->execute();
        }
    }

    public function getAppealDepartamentsId()
    {
        return Yii::app()->db->createCommand()->select('appeal_departament_id')->from('user_to_appeal_departament')->where('user_id = :user_id', array(':user_id' => $this->id))->queryColumn();
    }

    public function setAppealDepartamentsId($appealDepartaments)
    {
        Yii::app()->db->createCommand()->delete('user_to_appeal_departament', 'user_id = :current_user_id', array(':current_user_id' => $this->id));
        $data = array();
        if (!is_array($appealDepartaments) && is_int($appealDepartaments))
            $appealDepartaments = array($appealDepartaments);
        if (is_array($appealDepartaments)) {
            foreach ($appealDepartaments as $appealDepartament)
                $data[] = array('user_id' => $this->id, 'appeal_departament_id' => $appealDepartament);
            Yii::app()->db->schema->commandBuilder->createMultipleInsertCommand('user_to_appeal_departament', $data)->execute();
        }
    }

    protected function afterSave()
    {
        if ($this->getScenario() == 'edit' || $this->getScenario() == 'add') {
            switch ($this->type) {
                case self::TYPE_CLIENT:
                    if ($this->business_centers) {
                        $this->setBusinessCentersId($this->business_centers);
                    }
                    break;
                case self::TYPE_WORKER:
                    if ($this->appeal_departaments) {
                        $this->setAppealDepartamentsId($this->appeal_departaments);
                    }
                    break;
            }
        }
        if ($this->type != $this->oldUserType) {
            switch ($this->type)
            {
                case self::TYPE_CLIENT:
                    Yii::app()->db->createCommand()->delete('user_to_appeal_departament', 'user_id = :current_user_id', array(':current_user_id' => $this->id));
                    break;
                case self::TYPE_WORKER:
                    Yii::app()->db->createCommand()->delete('user_to_business_center', 'user_id = :current_user_id', array(':current_user_id' => $this->id));
                    break;
                case self::TYPE_ADMIN:
                    Yii::app()->db->createCommand()->delete('user_to_appeal_departament', 'user_id = :current_user_id', array(':current_user_id' => $this->id));
                    Yii::app()->db->createCommand()->delete('user_to_business_center', 'user_id = :current_user_id', array(':current_user_id' => $this->id));
                    break;
            }
        }

        parent::afterSave();
    }

    public function search()
    {
        $criteria=new CDbCriteria;

        $criteria->compare('id', $this->id);
        $criteria->compare('email', $this->email, true);
        $criteria->compare('display', $this->display, true);
        $criteria->compare('status', $this->status);
        $criteria->compare('type', $this->type);
        $criteria->compare('inn', $this->inn, true);
        $criteria->compare('phone', $this->phone, true);
        $criteria->compare('organization', $this->organization, true);

        return new CActiveDataProvider($this, array(
            'criteria'=>$criteria,
        ));
    }

}