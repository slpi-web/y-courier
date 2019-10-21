<?php

class PostDelivery extends BasePostDelivery
{
    const STATUS_WAITING = 0;
    const STATUS_TRANFERED_TO_COURIER = 1;
    const STATUS_AT_COURIER = 2;
    const STATUS_AT_POST  = 3;

    public $file;

    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }

    public function rules()
    {
        if ($this->getIsNewRecord()) {
            $fileRule = array('file', 'file', 'allowEmpty' => true);
            if (isset(Yii::app()->params['postDeliveryFileTypes']) && Yii::app()->params['postDeliveryFileTypes'])
                $fileRule['types'] = Yii::app()->params['postDeliveryFileTypes'];
            if (isset(Yii::app()->params['postDeliveryMaxFileSize']) && Yii::app()->params['postDeliveryMaxFileSize'])
                $fileRule['maxSize'] = Yii::app()->params['postDeliveryMaxFileSize']*1024;
            return array(
                $fileRule
            );
        } else
            return array();
    }

    public function behaviors()
    {
        return array(
            'modelHistory' => array(
                'class' => 'ext.ModelHistory.behaviors.ModelHistoryBehavior',
                'modelName' => 'PostDelivery',
                'attributes' => array(
                    'user_id' => '$model->user ? $model->user->display : ""',
                    'organization' => '$data',
                    'timestamp' => 'Yii::app()->dateFormatter->formatDateTime($data, "short", "short")',
                    'status' => '$model->getStatusLabel()',
                    'business_center_id' => '$model->businessCenter ? $model->businessCenter->caption : ""',
                    'office' => '$data',
                    'letters_count' => '$data',
                    'comment' => '$data',
                ),
            ),
        );
    }

    public function relations()
    {
        return array(
            'user' => array(self::BELONGS_TO, 'User', 'user_id'),
            'businessCenter' => array(self::BELONGS_TO, 'BusinessCenter', 'business_center_id'),
        );
    }

    public function attributeLabels()
    {
        $labels = parent::attributeLabels();
        $labels['file'] = Yii::t('model_post_delivery', 'file');

        return $labels;
    }

    public function getStatusLabels()
    {
        return array(
            self::STATUS_WAITING => Yii::t('model_post_delivery', 'status - waiting'),
            self::STATUS_TRANFERED_TO_COURIER => Yii::t('model_post_delivery', 'status - transfered to courier'),
            self::STATUS_AT_COURIER => Yii::t('model_post_delivery', 'status - at courier'),
            self::STATUS_AT_POST => Yii::t('model_post_delivery', 'status - at post'),
        );
    }

    public function getStatusLabel()
    {
        $labels = $this->getStatusLabels();
        if (isset($labels[$this->status]))
            return $labels[$this->status];

        return '';
    }

    public function init()
    {
        $this->timestamp = time();
        $this->status = self::STATUS_WAITING;
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
        parent::afterSave();

        if ($this->isNewRecord) {
            if (isset(Yii::app()->params['postDeliveryEmailList'])) {
                $emails = DataParser::parseEmailList(Yii::app()->params['postDeliveryEmailList']);
                if (count($emails)) {
                    $file = CUploadedFile::getInstance($this, 'file');
                    foreach ($emails as $email) {
                        $mail = new YiiMailer();
                        $mail->setLayout('mail_worker');
                        $mail->setView('post_delivery_worker');
                        $mail->setData(array(
                            'model' => $this,
                        ));
                        $mail->setTo($email);
                        if ($file) {
                            $mail->AddAttachment($file->getTempName(), $file->getName());
                        }
                        $mail->send();
                    }
                }
            }
            if ($this->user) {
                if ($this->user->email) {
                    $mail = new YiiMailerQueue();
                    $mail->setView('post_delivery_client');
                    $mail->setData(array(
                        'model' => $this,
                    ));
                    $mail->setTo($this->user->email);
                    $mail->send();
                }
            }
        }
    }

    public function getSearchCriteria()
    {
        $criteria = new CDbCriteria;

        $tableAlias = $this->getTableAlias();

        $criteria->compare($tableAlias.'.id', $this->id);
        $criteria->compare($tableAlias.'.user_id', $this->user_id);
        $criteria->compare($tableAlias.'.organization', $this->organization, true);
        $criteria->compare($tableAlias.'.business_center_id', $this->business_center_id);
        $criteria->compare($tableAlias.'.office', $this->office, true);
        $criteria->compare($tableAlias.'.letters_count', $this->letters_count);

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

        return $criteria;
    }

    public function search()
    {
        $criteria = $this->getSearchCriteria();

        return new CActiveDataProvider($this, array(
            'criteria'=>$criteria,
        ));
    }



}