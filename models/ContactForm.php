<?php

namespace app\models;

use Yii;
use yii\base\Model;
use app\jobs\SendEmailJob;

/**
 * ContactForm is the model behind the contact form.
 */
class ContactForm extends Model
{
    public $name;
    public $email;
    public $subject;
    public $body;
    public $verifyCode;


    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            // name, email, subject and body are required
            [['name', 'email', 'subject', 'body'], 'required'],
            // email has to be a valid email address
            ['email', 'email'],
            // verifyCode needs to be entered correctly
            ['verifyCode', 'captcha'],
        ];
    }

    /**
     * @return array customized attribute labels
     */
    public function attributeLabels()
    {
        return [
            'verifyCode' => 'Verification Code',
        ];
    }

    /**
     * Sends an email to the specified email address using the information collected by this model.
     * @param string $email the target email address
     * @return bool whether the model passes validation
     */ public function saveContact()
    {
        if ($this->validate()) {
            $contact = new Contact();
            $contact->name = $this->name;
            $contact->email = $this->email;
            $contact->subject = $this->subject;
            $contact->body = $this->body;

            if ($contact->save()) {
                // Đẩy job vào queue sau khi lưu contact thành công
                Yii::$app->queue->push(new SendEmailJob([
                    'email' => $this->email,
                    'subject' => $this->subject,
                    'body' => $this->body,
                ]));
                return true;
            }
        }
        return false;
    }
}