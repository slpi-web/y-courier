<?php

class Appeal extends BaseAppeal
{
    const STATUS_TRANSFERED_TO_DEPARTAMENT = 0;
    const STATUS_IN_WORK = 1;
    const STATUS_DONE = 2;
    const STATUS_CLOSED = 3;

    public $appeal_departaments = null;

    public $oldAppealDepartaments = array();

    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }

    public function rules()
    {
        return array(
        );
    }

    public function relations()
    {
        return array(
            'user' => array(self::BELONGS_TO, 'User', 'user_id'),
            'appealMessages' => array(self::HAS_MANY, 'AppealMessage', 'appeal_id'),
            'businessCenter' => array(self::BELONGS_TO, 'BusinessCenter', 'business_center_id'),
            'appealDepartaments' => array(self::MANY_MANY, 'AppealDepartament', 'appeal_to_appeal_departament(appeal_id, appeal_departament_id)'),
        );
    }

    public function behaviors()
    {
        return array(
            'modelHistory' => array(
                'class' => 'ext.ModelHistory.behaviors.ModelHistoryBehavior',
                'modelName' => 'Appeal',
                'attributes' => array(
                    'id' => '$data',
                    'user_id' => '$model->user ? $model->user->display : ""',
                    'timestamp' => 'Yii::app()->dateFormatter->formatDateTime($data, "short", "short")',
                    'business_center_id' => '$model->businessCenter ? $model->businessCenter->caption : ""',
                    'appeal_departaments' => '$model->getDepartaments("<br />", $model->appeal_departaments)',
                    'status' => '$model->getStatusLabel()',
                    'subject' => '$data',
                    'text' => '$data',
                ),
            ),
        );
    }

    public function getStatusLabels()
    {
        return array(
            self::STATUS_TRANSFERED_TO_DEPARTAMENT => Yii::t('model_appeal', 'status - transfered to departament'),
            self::STATUS_IN_WORK => Yii::t('model_appeal', 'status - in work'),
            self::STATUS_DONE => Yii::t('model_appeal', 'status - done'),
            self::STATUS_CLOSED => Yii::t('model_appeal', 'status - closed'),
        );
    }

    public function getStatuslabel()
    {
        $labels = $this->getStatusLabels();
        if (isset($labels[$this->status]))
            return $labels[$this->status];

        return '';
    }

    public function attributeLabels()
    {
        $labels = parent::attributeLabels();
        $labels['appeal_departaments'] = Yii::t('model_appeal', 'appeal_departaments');

        return $labels;
    }

    public function defaultScope()
    {
        $tableAlias = $this->getTableAlias(false, false);
        return array(
            'order' => $tableAlias.'.timestamp DESC',
        );
    }

    public function byUserId($userId)
    {
        $tableAlias = $this->getTableAlias();

        $this->getDbCriteria()->mergeWith(array(
            'condition' => $tableAlias.'.user_id = :selected_user_id',
            'params' => array(
                ':selected_user_id' => $userId,
            ),
        ));

        return $this;
    }

    public function getAppealDepartamentsId()
    {
        return Yii::app()->db->createCommand()->select('appeal_departament_id')->from('appeal_to_appeal_departament')->where('appeal_id = :appeal_id', array(':appeal_id' => $this->id))->queryColumn();
    }

    public function setAppealDeparamentsId($appealDepartaments)
    {
        Yii::app()->db->createCommand()->delete('appeal_to_appeal_departament', 'appeal_id = :appeal_id', array(':appeal_id' => $this->id));
        $data = array();
        if (!is_array($appealDepartaments) && is_int($appealDepartaments))
            $appealDepartaments = array($appealDepartaments);
        if (is_array($appealDepartaments)) {
            foreach ($appealDepartaments as $appealDepartamentId)
                $data[] = array('appeal_id' => $this->id, 'appeal_departament_id' => $appealDepartamentId);
            Yii::app()->db->schema->commandBuilder->createMultipleInsertCommand('appeal_to_appeal_departament', $data)->execute();
        }
    }

    public function init()
    {
        $this->timestamp = time();
    }

    protected  function afterFind()
    {
        $this->appeal_departaments = array();
        foreach ($this->appealDepartaments as $appealDepartament)
            $this->appeal_departaments[] = $appealDepartament->id;

        $this->oldAppealDepartaments = $this->appeal_departaments;

        parent::afterFind();
    }

    protected function beforeSave()
    {
        if(parent::beforeSave()) {

            if ($this->isNewRecord)
                $this->timestamp = time();

            return true;
        }
        return false;
    }

    protected function afterSave()
    {
        $this->setAppealDeparamentsId($this->appeal_departaments);
        $newAppealDepartaments = $this->appeal_departaments;
        if (!is_array($newAppealDepartaments))
            $newAppealDepartaments = array($newAppealDepartaments);
        $departamentsDiff = array_diff($newAppealDepartaments, $this->oldAppealDepartaments);
        if (count($departamentsDiff)) {
            $criteria = new CDbCriteria;
            $criteria->addInCondition('id', $departamentsDiff);
            $emailList = array();
            $departaments = AppealDepartament::model()->findAll($criteria);
            foreach ($departaments as $departament) {
                $emails = $departament->getEmails();
                foreach ($emails as $email)
                    $emailList[$email] = 1;
            }
            $emailList = array_keys($emailList);
            if (count($emailList)) {
                foreach ($emailList as $email) {
                    $mail = new YiiMailerQueue();
                    $mail->setLayout('mail_worker');
                    $mail->setView('appeal_worker');
                    $mail->setData(array(
                        'model' => $this,
                    ));
                    $mail->setTo($email);
                    $mail->send();
                }
            }
        }
        if ($this->isNewRecord) {
            if ($this->user) {
                if ($this->user->email) {
                    $mail = new YiiMailerQueue();
                    $mail->setView('appeal_client');
                    $mail->setData(array(
                        'model' => $this,
                    ));
                    $mail->setTo($this->user->email);
                    $mail->send();
                }
            }
        }
        parent::afterSave();
    }

    public function getDepartaments($implode = null, $fromArray = null)
    {
        $departaments = array();
        if (!is_array($fromArray)) {
            foreach ($this->appealDepartaments as $appealDepartament)
                $departaments[] = $appealDepartament->caption;

        } else {
            $criteria = new CDbCriteria();
            $criteria->addInCondition('id', $fromArray);
            $departamentModels = AppealDepartament::model()->findAll($criteria);
            foreach ($departamentModels as $departamentModel) {
                $departaments[] = $departamentModel->caption;
            }
        }
        if ($implode !== null)
            $departaments = implode($implode, $departaments);

        return $departaments;
    }

    public function getMessagesDataProvider($config = array())
    {
        if (!is_array($config))
            $config = array();

        $searchCriteria = new CDbCriteria();
        $searchCriteria->scopes = array(
            'byAppealId' => array($this->id),
        );
        if (isset($config['criteria']) && (is_array($config['criteria']) || ($config['criteria'] instanceof CDbCriteria))) {
            $searchCriteria->mergeWith($config['criteria']);
        }
        $config['criteria'] = $searchCriteria;

        return new CActiveDataProvider('AppealMessage', $config);
    }

    public function getNewMessageModel()
    {
        if ($this->status == self::STATUS_CLOSED)
            return null;

        $message = new AppealMessage();
        $message->appeal_id = $this->id;
        $message->user_id = Yii::app()->user->getId();

        return $message;
    }

    public function getSearchCriteria()
    {
        $criteria=new CDbCriteria;

        $tableAlias = $this->getTableAlias();

        $criteria->compare($tableAlias.'.id',$this->id);
        $criteria->compare($tableAlias.'.user_id',$this->user_id);
        $criteria->compare($tableAlias.'.business_center_id',$this->business_center_id);
        $criteria->compare($tableAlias.'.status',$this->status);
        $criteria->compare($tableAlias.'.subject',$this->subject,true);

        if ($this->timestamp) {
            $dates = explode('-', $this->timestamp);
            foreach ($dates as &$date) {
                $date = trim($date);
            }

            $timestamps = array();

            switch (count($dates)) {
                case 1:
                    $startTimestamp = CDateTimeParser::parse($dates[0].' 00:00:00','dd.MM.yyyy hh:mm:ss');
                    $endTimestamp = CDateTimeParser::parse($dates[0].' 23:59:59','dd.MM.yyyy hh:mm:ss');
                    if ($startTimestamp && $endTimestamp) {
                        $timestamps = array($startTimestamp, $endTimestamp);
                        sort($timestamps);
                    }
                    break;
                case 2:
                    $startTimestamp = CDateTimeParser::parse($dates[0].' 00:00:00','dd.MM.yyyy hh:mm:ss');
                    $endTimestamp = CDateTimeParser::parse($dates[1].' 00:00:00','dd.MM.yyyy hh:mm:ss');
                    if ($startTimestamp && $endTimestamp) {
                        $timestamps = array($startTimestamp, $endTimestamp);
                        sort($timestamps);
                        $timestamps[1] = $timestamps[1] + 24*60*60-1;
                    }
                    break;
            }

            if (!empty($timestamps)) {
                $criteria->addCondition($tableAlias.'.timestamp >= :start_timestamp AND '.$tableAlias.'.timestamp <= :end_timestamp');
                $criteria->params[':start_timestamp'] = $timestamps[0];
                $criteria->params[':end_timestamp'] = $timestamps[1];
            }
        }

        if ($this->appeal_departaments) {
            $criteria->together = true;
            if (is_array($this->appeal_departaments))
                $appealDepartamentsIds = implode(',', $this->appeal_departaments);
            else
                $appealDepartamentsIds = (int) $this->appeal_departaments;
            $criteria->addCondition("{$this->tableAlias}.id IN (SELECT appeal_id FROM `appeal_to_appeal_departament` WHERE appeal_departament_id IN ({$appealDepartamentsIds}))");
        }

        $criteria->with = array(
            'appealDepartaments' => array(
                'select' => array('id', 'caption'),
            ),
        );

        return $criteria;
    }

    public function search()
    {
        $criteria = $this->getSearchCriteria();

        $criteria->with = array(
            'businessCenter' => array(
                'select' => array('id', 'caption'),
            ),
            'appealDepartaments' => array(
                'select' => array('id', 'caption'),
            ),
        );

        return new CActiveDataProvider($this, array(
            'criteria'=>$criteria,
        ));
    }



}