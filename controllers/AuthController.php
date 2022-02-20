<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\helpers\ArrayHelper;

use app\models\City;
use app\models\LoginForm;
use app\models\RegistrationForm;

class AuthController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                        'actions' => ['logout'],
                    ],
                    [
                        'allow' => true,
                        'roles' => ['?'],
                        'actions' => ['registration', 'login'],
                    ],
                    [
                        'allow' => false,
                        'roles' => ['@'],
                        'actions' => ['registration', 'login'],
                        'denyCallback' => function ($rule, $action) {
                            $this->redirect('/tasks');
                        }
                    ],
                ],
            ]
        ];
    }

    public function actionRegistration(): string|Response
    {
        $registrationForm = new RegistrationForm();

        if (Yii::$app->request->getIsPost()) {
            $registrationForm->load(Yii::$app->request->post());

            if ($registrationForm->validate()) {
                $registrationForm->password = Yii::$app->security->generatePasswordHash($registrationForm->password);
                $registrationForm->save();
                // todo аутентификация тут
                // todo Если данные были сохранены успешно, то переадресовать пользователя на страницу списка заданий.
                return $this->goHome();
            }
        }

        $cities = ArrayHelper::map(City::find()->all(), 'id', 'name');

        return $this->render('registration', [
            'model' => $registrationForm,
            'cities' => $cities,
        ]);
    }

    public function actionLogin(): string|Response
    {
        $this->layout = 'landing';

        $loginForm = new LoginForm();

        if (Yii::$app->request->getIsPost()) {
            $loginForm->load(Yii::$app->request->post());

            if ($loginForm->validate() && $loginForm->login()) {
                return $this->goHome();
            }
        }

        return $this->render('login', ['model' => $loginForm]);
    }

    public function actionLogout(): Response
    {
        Yii::$app->user->logout();
        return $this->goHome();
    }
}
