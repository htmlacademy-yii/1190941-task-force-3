<?php

namespace app\models;

use Yii;
use yii\base\Model;

class ResponseAddFrom extends Model
{
    public $budget;
    public $comment;

    public function attributeLabels()
    {
        return [
            'budget' => 'Ваша цена',
            'comment' => 'Комментарий'
        ];
    }

    public function rules()
    {
        return [
            ['comment', 'string'],
            ['budget', 'integer'],
        ];
    }

    public function save(int $taskID)
    {
        $response = new TaskResponse();
        $response->price = $this->budget;
        $response->comment = $this->comment;
        $response->task_id = $taskID;
        $response->user_id = Yii::$app->user->getId();
        $response->save();
    }
}
