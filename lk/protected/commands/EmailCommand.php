<?php

class EmailCommand extends CConsoleCommand
{

    public function actionSendFromQueue($count = 5)
    {
        $mailer = new YiiMailerQueue();
        $mailer->sendQueuedMessages($count);
    }

}