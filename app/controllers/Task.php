<?php

// qstn 1. Примеры использования исключений в продакшен коде. (работа с БД?)
// qstn 2. 2 слова про SPL
// qstn 3. Предпочитать строгую типизацию не строгой?
// qstn 4. Не сильно понятно что такое генераторы и для чего нужны

namespace tf\controllers;
use tf\controllers\actions\AbstractAction;
use tf\controllers\actions\CancelAction;
use tf\controllers\actions\RespondAction;
use tf\controllers\actions\AcceptAction;
use tf\controllers\actions\AbandonAction;

class Task
{
    private const STATUS_NEW = 'new';
    private const STATUS_CANCELED = 'canceled';
    private const STATUS_IN_PROGRESS = 'inProgress';
    private const STATUS_DONE = 'done';
    private const STATUS_FAILED = 'failed';

    private const STATUSES_MAP = [
        self::STATUS_NEW => 'Новое',
        self::STATUS_CANCELED => 'Отменено',
        self::STATUS_IN_PROGRESS => 'В работе',
        self::STATUS_DONE => 'Выполнено',
        self::STATUS_FAILED => 'Провалено',
    ];

    private const ACTIONS_TO_STATUSES_MAP = [
        CancelAction::CODE => self::STATUS_CANCELED,
        AcceptAction::CODE => self::STATUS_DONE,
        AbandonAction::CODE => self::STATUS_FAILED
    ];

    private string $status;
    private int $customerID;
    private ?int $performerID;
    private array $actions;

    function __construct(
        int $customerID,
        ?int $performerID,
        string $taskStatus
    )
    {
        $this->customerID = $customerID;
        $this->performerID = $performerID;
        $this->status = $taskStatus;
        $this->actions = [
            self::STATUS_NEW => [
                new CancelAction(), new RespondAction()
            ],
            self::STATUS_IN_PROGRESS => [
                new AcceptAction(), new AbandonAction()
            ],
        ];
    }

    public function setStatus(string $status): string
    {
        return $this->status = $status;
    }

    public function getStatusByAction(AbstractAction $action): ?string
    {
        return self::ACTIONS_TO_STATUSES_MAP[$action->getInnerTitle()] ?? null;
    }

    /**
     * @return AbstractAction[]
     */
    public function getActionByStatus(): array
    {
        return $this->actions[$this->status];
    }
}
