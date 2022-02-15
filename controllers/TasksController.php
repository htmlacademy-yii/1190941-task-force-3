<?php

namespace app\controllers;

use yii\db\Query;
use yii\web\Controller;

class TasksController extends Controller
{
    public function actionIndex(): string
    {
        $query = new Query();
        $query->select([
            'status',
            'created_at',
            'title',
            'description',
            'price',
            'c.name AS city',
            'cat.name AS category'
        ])
            ->from('tasks t')
            ->innerJoin('cities c', 't.city_id = c.id')
            ->innerJoin('categories cat', 't .category_id = cat.id')
            ->where(['status' => 0])
            ->orderBy(['created_at' => SORT_DESC]);
        $tasks = $query->all();

        return $this->render('index', [
            'tasks' => $tasks,
        ]);
    }
}
