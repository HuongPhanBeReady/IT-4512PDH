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

    // Số lần thử lại
    public $attemptLimit = 2;

    // Thời gian giữa các lần thử lại (giây)
    public $retryInterval = 60;

    // Thời gian hết hạn (Time To Run)
    public function getTtr()
    {
        return 2 * 60; // Thời gian hết hạn 2 phút
    }

    // Kiểm tra có thể retry hay không
    public function canRetry($attempt, $error)
    {
        $errorMessenger = "";
        $status = true;

        if ($error instanceof \Exception) {
            $status = false;
            $errorMessenger = $error->getMessage();
        }

        if ($attempt >= $this->attemptLimit) {
            $status = false;
            $errorMessenger = "Attempt limit reached.";
        }

        if ($status === false && !empty($this->email)) {
            // Ghi log khi thất bại
            Yii::error('Error Message: ' . $errorMessenger, __METHOD__);
        }

        return $attempt < $this->attemptLimit && $status;
    }

    public function execute($queue)
    {
        $attempts = 0;
        $success = false;

        while ($attempts < $this->attemptLimit && !$success) {
            try {
                $result = Yii::$app->mailer->compose()
                    ->setFrom(Yii::$app->params['senderEmail'])
                    ->setTo($this->email)
                    ->setSubject($this->subject)
                    ->setTextBody($this->body)
                    ->send();

                if ($result) {
                    Yii::info('Email đã được gửi thành công!', __METHOD__);
                    $success = true;
                } else {
                    Yii::error('Không thể gửi email.', __METHOD__);
                    $attempts++;
                    if ($attempts < $this->attemptLimit) {
                        Yii::info('Đang thử lại, lần thử thứ ' . ($attempts + 1), __METHOD__);
                        sleep($this->retryInterval); // Thời gian giữa các lần thử lại
                    }
                }
            } catch (\Exception $e) {
                Yii::error('Lỗi khi gửi email: ' . $e->getMessage(), __METHOD__);
                $attempts++;
                if ($attempts < $this->attemptLimit) {
                    Yii::info('Đang thử lại, lần thử thứ ' . ($attempts + 1), __METHOD__);
                    sleep($this->retryInterval); // Thời gian giữa các lần thử lại
                }
            }
        }

        if (!$success) {
            Yii::error('Đã hết số lần thử lại mà không thành công.', __METHOD__);
        }
    }
}
