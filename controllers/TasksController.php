<?php

namespace app\controllers;

use app\models\RefusalReasonAddForm;
use Throwable;

use Yii;
use yii\base\ErrorException;
use yii\web\NotFoundHttpException;
use yii\web\UploadedFile;

use app\models\UserReviewAddForm;
use app\models\ResponseAddFrom;
use app\models\TaskAddForm;
use app\models\User;
use app\models\Task;
use app\models\Category;
use app\models\TaskFilterForm;

use tf\models\Task as TaskHelper;

class TasksController extends SecuredController
{
    public function behaviors(): array
    {
        $rules = parent::behaviors();
        $rule = [
            'allow' => true,
            'actions' => ['add'],
            'roles' => ['customer'],
        ];

        array_unshift($rules['access']['rules'], $rule);

        return $rules;
    }

    public function actionIndex(): string
    {
        $filterForm = new TaskFilterForm();
        $filterForm->load(Yii::$app->request->get());

        $categories = Category::getCategories();

        return $this->render('index', [
            'model' => $filterForm,
            'dataProvider' => $filterForm->getDataProvider(),
            'categories' => $categories,
        ]);
    }

    /**
     * @throws ErrorException
     * @throws \tf\exceptions\ExistenceException
     */
    public function actionView($id): string
    {
        try {
            $task = Task::find()
                ->joinWith(['city', 'taskResponses', 'taskAttachments'])
                ->where(['tasks.id' => $id])
                ->one();

            if (!$task) {
                Yii::$app->response->setStatusCode(404);
                throw new NotFoundHttpException('Записи с таким ID не существует');
            }

        } catch (NotFoundHttpException $e) {
            return $e->getMessage();
        }

        $userID = Yii::$app->user->getId();
        $isCustomer = Yii::$app->user->can(User::ROLE_CUSTOMER) && $userID === $task->customer_id;
        $taskStatus = new TaskHelper($task->customer_id, $task->performer_id, $task->status);
        $action = $taskStatus->getActionByRole($userID);

        $actionModel = null;

        if ($action && $action->getInnerTitle() === 'respond') {
            $actionModel = new ResponseAddFrom();
        } elseif ($action && $action->getInnerTitle() === 'accept') {
            $actionModel = new UserReviewAddForm();
        } elseif ($action && $action->getInnerTitle() === 'refuse') {
            $actionModel = new RefusalReasonAddForm();
        }

        return $this->render('view', [
            'task' => $task,
            'model' => $actionModel,
            'isCustomer' => $isCustomer,
            'action' => $action
        ]);
    }

    public function actionMy()
    {
        // todo Страница выглядит по разному для роли «Заказчик» и «Исполнитель».
        // todo Заказчик https://up.htmlacademy.ru/profession/backender/1/yii/3/project/task-force#:~:text=%D0%97%D0%B0%D0%BA%D0%B0%D0%B7%D1%87%D0%B8%D0%BA%C2%BB%20%D0%B8%C2%A0%C2%AB%D0%98%D1%81%D0%BF%D0%BE%D0%BB%D0%BD%D0%B8%D1%82%D0%B5%D0%BB%D1%8C%C2%BB.-,%D0%97%D0%B0%D0%BA%D0%B0%D0%B7%D1%87%D0%B8%D0%BA,-%D0%9D%D0%B0%D0%B7%D0%BD%D0%B0%D1%87%D0%B5%D0%BD%D0%B8%D0%B5%20%D1%8D%D1%82%D0%BE%D0%B9%20%D1%81%D1%82%D1%80%D0%B0%D0%BD%D0%B8%D1%86%D1%8B
        // todo Исполнитель https://up.htmlacademy.ru/profession/backender/1/yii/3/project/task-force#:~:text=%D0%9E%D1%82%D0%BC%D0%B5%D0%BD%D0%B5%D0%BD%D0%BE%C2%BB%2C%20%C2%AB%D0%92%D1%8B%D0%BF%D0%BE%D0%BB%D0%BD%D0%B5%D0%BD%D0%BE%C2%BB%2C%20%C2%AB%D0%9F%D1%80%D0%BE%D0%B2%D0%B0%D0%BB%D0%B5%D0%BD%D0%BE%C2%BB-,%D0%98%D1%81%D0%BF%D0%BE%D0%BB%D0%BD%D0%B8%D1%82%D0%B5%D0%BB%D1%8C,-%D0%9D%D0%B0%D0%B7%D0%BD%D0%B0%D1%87%D0%B5%D0%BD%D0%B8%D0%B5%20%D1%8D%D1%82%D0%BE%D0%B9%20%D1%81%D1%82%D1%80%D0%B0%D0%BD%D0%B8%D1%86%D1%8B
    }

    /**
     * @throws \Throwable
     */
    public function actionAdd(): string
    {
        $newTask = new TaskAddForm();
        $categories = Category::getCategories();

        if (Yii::$app->request->getIsPost()) {
            $newTask->load(Yii::$app->request->post());
            $newTask->files = UploadedFile::getInstances($newTask, 'files');

            if ($newTask->validate()) {
                $newTask->save();
                $this->redirect("/tasks/view/{$newTask->id}");
            }
        }

        return $this->render('add', [
            'model' => $newTask,
            'categories' => $categories,
        ]);
    }

    public function actionCancel(int $taskID)
    {
        $task = Task::findOne($taskID);

        if ($task->status === TaskHelper::STATUS_NEW && $task->customer_id === Yii::$app->user->getId()) {
            $task->status = TaskHelper::STATUS_CANCELED;
            $task->save(false);
            $this->redirect("/tasks/view/{$taskID}");
        }
    }

    public static function actionStart(int $performerID, int $taskID)
    {
        $task = Task::find()->where(['id' => $taskID])->one();

        if ($task->customer_id === Yii::$app->user->getId() && $task->status === TaskHelper::STATUS_NEW) {
            $task->status = TaskHelper::STATUS_IN_PROGRESS;
            $task->link('performer', User::findOne($performerID));
            $task->save();
        }
    }

    public function actionRefuse(int $taskID)
    {
        $newRefusalReason = new RefusalReasonAddForm();

        if (Yii::$app->request->getIsPost()) {
            $newRefusalReason->load(Yii::$app->request->post());

            if ($newRefusalReason->validate()) {
                $newRefusalReason->save($taskID);
                $this->redirect("/tasks/view/{$taskID}");
            }
        }
    }

    /**
     * @throws Throwable
     */
    public function actionAccept(int $taskID)
    {
        $newReview = new UserReviewAddForm();

        if (Yii::$app->request->getIsPost()) {
            $newReview->load(Yii::$app->request->post());

            if ($newReview->validate()) {
                $newReview->save($taskID);
                $this->redirect("/tasks/view/{$taskID}");
            }
        }
    }
}
