<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\AccessControl;

// todo error_reporting = E_ALL

abstract class SecuredController extends Controller
{
    public function init()
    {
        parent::init();
        Yii::$app->view->params['user'] = Yii::$app->user->getIdentity();
    }

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['customer', 'performer'],
                        'controllers' => ['user', 'tasks'],
                        'actions' => ['index', 'view'],
                    ],
                    [
                        'allow' => true,
                        'roles' => ['customer'],
                        'controllers' => ['tasks', 'response'],
                        'actions' => ['add', 'refuse', 'accept', 'cancel'],
                    ],
                    [
                        'allow' => true,
                        'roles' => ['performer'],
                        'controllers' => ['response', 'tasks'],
                        'actions' => ['add', 'refuse'],
                    ]
                ],
            ]
        ];
    }
}
