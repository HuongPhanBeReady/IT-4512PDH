<?php

namespace app\jobs;

use Yii;
use yii\base\BaseObject;
use yii\queue\JobInterface;

class SendEmailJob extends BaseObject implements JobInterface
{
    public $email;
    public $subject;
    public $body;

    public function execute($queue)
    {
        try {
            $result = Yii::$app->mailer->compose()
                ->setFrom( Yii::$app->params['senderEmail']) 
                ->setTo($this->email)
                ->setSubject($this->subject)
                ->setTextBody($this->body)
                ->send();

            if ($result) {
                Yii::info('Email đã được gửi thành công!', __METHOD__);
            } else {
                Yii::error('Không thể gửi email.', __METHOD__);
            }
        } catch (\Exception $e) {
            Yii::error('Lỗi khi gửi email: ' . $e->getMessage(), __METHOD__);
        }
    }
}