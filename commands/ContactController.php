<?php

namespace app\commands;

use Yii;
use yii\console\Controller;
use app\models\Contact; // Assume this is your ActiveRecord model for the contacts table

class ContactController extends Controller
{
    /**
     * Action để liệt kê danh sách các contact từ bảng contact.
     */
    public function actionList()
    {
        $contacts = Contact::find()->all();
        foreach ($contacts as $contact) {
            $this->stdout("ID: {$contact->id}, Name: {$contact->name}, Email: {$contact->email}\n");
        }
    }

    /**
     * Action để tìm contact theo email được truyền vào.
     * @param string $email Email của contact cần tìm
     */
    public function actionFind($email)
    {
        $contacts = Contact::find()->where(['email' => $email])->all();
        if (!empty($contacts)) {
            foreach ($contacts as $contact) {
                $this->stdout("Found contact - ID: {$contact->id}, Name: {$contact->name}, Email: {$contact->email}\n");
            }
        } else {
            $this->stdout("Contact with email '{$email}' not found.\n");
        }
    }
}
