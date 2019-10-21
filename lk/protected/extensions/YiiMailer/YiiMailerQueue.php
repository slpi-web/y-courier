<?php
/**
 * Created by PhpStorm.
 * User: dim
 * Date: 04.04.14
 * Time: 16:21
 */

Yii::import('ext.YiiMailer.models.*');
Yii::import('ext.YiiMailer.models.base.*');

class YiiMailerQueue extends YiiMailer {

    protected $fromQueue = false;

    protected $queueParams = array(
        'Body',
        'AltBody',
        'Subject',
        'to',
        'cc',
        'bcc',
        'ReplyTo',
        'all_recipients',
    );

    /**
     * Set and configure initial parameters
     * @param string $view View name
     * @param array $data Data array
     * @param string $layout Layout name
     */
    public function __construct($view='', $data=array(), $layout='', $importData = false)
    {
        parent::__construct($view, $data, $layout);

        if ($importData) {
            $this->fromQueue = true;
            $this->importParams($importData);
        }
    }

    protected function exportParams()
    {
        $data = array();
        foreach ($this->queueParams as $queueParam) {
            $data[$queueParam] = $this->{$queueParam};
        }
        return serialize($data);
    }

    protected function importParams($data)
    {
        $data = unserialize($data);
        foreach ($data as $key => $value) {
            $this->{$key} = $value;
        }
    }

    public function send()
    {
        if ($this->fromQueue) {
            try{
                //prepare the message
                if(!$this->PreSend())
                    return false;

                //in test mode, save message as a file
                if($this->testMode)
                    return $this->save();
                else
                    return $this->PostSend();
            } catch (phpmailerException $e) {
                $this->mailHeader = '';
                $this->SetError($e->getMessage());
                if ($this->exceptions) {
                    throw $e;
                }
                return false;
            }
        } else {
            $this->render();

            $model = new EmailQueue();
            $model->data = $this->exportParams();
            $model->save();
        }
    }

    public function sendQueuedMessages($count = 1)
    {
        $messages = EmailQueue::model()->first($count)->queued()->findAll();
        foreach ($messages as $message) {
            $message->saveAttributes(array(
                'status' => EmailQueue::STATUS_SENT,
            ));
        }
        foreach ($messages as $message) {
            $mailer = new YiiMailerQueue('',array(),'',$message->data);
            if (!$mailer->send())
                $message->saveAttributes(array(
                    'status' => EmailQueue::STATUS_ERROR,
                ));
        }
    }

} 