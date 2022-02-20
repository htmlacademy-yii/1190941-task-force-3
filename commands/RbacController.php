<?php

namespace app\commands;

use app\models\User;
use Yii;
use yii\console\Controller;
use yii\console\ExitCode;

class RbacController extends Controller
{
    public function actionCreateRoles()
    {
        $authManager = Yii::$app->authManager;

        $customerRole = $authManager->createRole('customer');
        $authManager->add($customerRole);

        $performerRole = $authManager->createRole('performer');
        $authManager->add($performerRole);

        $this->stdout('Роли успешно созданы.');

        return ExitCode::OK;
    }

    public function actionAssignRandomRole()
    {
        $customerRole = Yii::$app->authManager->getRole('customer');
        $customers = (new User())->find()->select('users.id')->with('customerTasks')->groupBy('users.id')->all();

        foreach ($customers as $customer) {
            Yii::$app->authManager->assign($customerRole, $customer->id);
        }

        $performerRole = Yii::$app->authManager->getRole('performer');
        $performers = (new User())->find()->select('users.id')->with('performerTasks')->groupBy('users.id')->all();

        foreach ($performers as $performer) {
            Yii::$app->authManager->assign($performerRole, $performer->id);
        }

        $this->stdout('Пользователям успешно назначены роли.');

        return ExitCode::OK;
    }
}
