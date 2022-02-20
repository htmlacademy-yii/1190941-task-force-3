<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\db\Expression;

use tf\models\Task as TaskHelper;

class TaskFilterForm extends Model
{
    const PERIOD_DAY = 1;
    const PERIOD_WEEK = 7;
    const PERIOD_MONTH = 30;
    const PERIOD_ALL = 0;

    const PERIOD_MAP = [
        self::PERIOD_DAY => 'За день',
        self::PERIOD_WEEK => 'За неделю',
        self::PERIOD_MONTH => 'За месяц',
        self::PERIOD_ALL => 'За все время',
    ];

    public string|array $categories = [];
    public bool $withoutResponses = false;
    public bool $remoteWork = false;
    public int $period = 0;

    public function attributeLabels(): array
    {
        return [
            'withoutResponses' => 'Без откликов',
            'remoteWork' => 'Удаленная работа',
            'period' => 'Период',
        ];
    }

    public function rules()
    {
        return [
            [['withoutResponses', 'remoteWork', 'period', 'categories'], 'safe']
        ];
    }

    public function getDataProvider(): ActiveDataProvider
    {
        // todo Показываются только задания без привязки к адресу,
        //  а также задания из города текущего пользователя.

        $query = Task::find()->joinWith([
            'category cat',
            'city c',
            'taskResponses tr'
        ])->where(['tasks.status' => TaskHelper::STATUS_NEW])->orderBy(['created_at' => SORT_DESC])->groupBy('tasks.id');

        if ($this->categories) {
            $query->andWhere(['category_id' => $this->categories]);
        }

        if ($this->withoutResponses) {
            $query->andWhere(['tr.user_id' => null]);
        }

        if ($this->remoteWork) {
            $query->andWhere(['city_id' => null]);
        }

        if ($this->period) {
            $query->andWhere(['>=', 'tasks.created_at', (new Expression("NOW() - INTERVAL {$this->period} DAY"))]);
        }

        return new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 5
            ],
            'sort' => [
                'defaultOrder' => [
                    'created_at' => SORT_DESC,
                ]
            ]
        ]);
    }
}
