<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;
use app\models\EntryForm;

class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

    /**
     * Login action.
     *
     * @return Response|string
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        }

        $model->password = '';
        return $this->render('login', [
            'model' => $model,
        ]);
    }

    /**
     * Logout action.
     *
     * @return Response
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * Displays contact page.
     *
     * @return Response|string
     */
    public function actionContact()
    {
        $model = new ContactForm();

        if ($model->load(Yii::$app->request->post()) && $model->saveContact()) {
            // Gửi email sau khi lưu thành công
            $this->sendEmail($model);

            Yii::$app->session->setFlash('contactFormSubmitted');

            return $this->refresh();
        }

        return $this->render('contact', [
            'model' => $model,
        ]);
    }
      // Action gửi email
     // Action gửi email
    public function sendEmail($model)
    {
        try {
            $result = Yii::$app->mailer->compose('home-link', ['model' => $model])
                ->setFrom( Yii::$app->params['senderEmail']) // Địa chỉ người gửi
                ->setTo($model->email) // Địa chỉ người nhận từ form
                ->setSubject($model->subject) // Tiêu đề email
                ->send(); // Gửi emails

            if ($result) {
                Yii::$app->session->setFlash('success', 'Email đã được gửi thành công!');
            } else {
                Yii::$app->session->setFlash('error', 'Không thể gửi email.');
            }
        } catch (\Exception $e) {
            Yii::error('Lỗi khi gửi email: ' . $e->getMessage());
            Yii::$app->session->setFlash('error', 'Lỗi khi gửi email: ' . $e->getMessage());
        }
    }
      
    public function actionAbout()
    {
        return $this->render('about');
    }
    public function actionSay($message = 'Hello')
    {
        return $this->render('say', ['message' => $message]);
    }
    public function actionEntry()
    {
        $model = new EntryForm();

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            // valid data received in $model

            // do something meaningful here about $model ...

            return $this->render('entry-confirm', ['model' => $model]);
        } else {
            // either the page is initially displayed or there is some validation error
            return $this->render('entry', ['model' => $model]);
        }
    }
    
    
}