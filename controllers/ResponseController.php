<?php

namespace app\controllers;

use Yii;
use yii\base\ErrorException;
use yii\web\Response;

use app\models\ResponseAddFrom;
use app\models\TaskResponse;

class ResponseController extends SecuredController
{
    public function actionAdd(int $taskID): void
    {
        $newResponse = new ResponseAddFrom();

        if (Yii::$app->request->getIsPost()) {
            $newResponse->load(Yii::$app->request->post());

            if ($newResponse->validate()) {
                $newResponse->save($taskID);
                $this->redirect("/tasks/view/{$taskID}");
            }
        }
    }

    public function actionRefuse(int $id): Response|string
    {
        try {
            $response = TaskResponse::findOne($id);

            if ($response->status === 1) {
                $response->status = 0;
            } else {
                throw new ErrorException('Отклик уже отклонен.');
            }

            $response->update();
            return $this->redirect("/tasks/view/{$response->task_id}");

        } catch (ErrorException $e) {
            return $e->getMessage();
        }
    }

    public function actionAccept($id)
    {
        $response = TaskResponse::find()->where(['id' => $id])->one();

        TasksController::actionStart($response->user_id, $response->task_id);
        $this->redirect("/tasks/view/{$response->task_id}");
    }
}
