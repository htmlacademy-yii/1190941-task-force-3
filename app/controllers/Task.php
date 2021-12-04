<?php
namespace tf\controllers;

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

    private const ACTION_CANCEL = 'cancel';
    private const ACTION_RESPOND = 'respond';
    private const ACTION_ACCEPT = 'accept';
    private const ACTION_ABANDON = 'abandon';

    private const ACTIONS_MAP = [
        self::ACTION_CANCEL => 'Отменить',
        self::ACTION_RESPOND => 'Откликнуться',
        self::ACTION_ACCEPT => 'Выполнено',
        self::ACTION_ABANDON => 'Отказаться',
    ];

    private const ACTION_TO_STATUS_MAP = [
        self::ACTION_CANCEL => self::STATUS_CANCELED,
        self::ACTION_ACCEPT => self::STATUS_DONE,
        self::ACTION_ABANDON => self::STATUS_FAILED,
    ];

    private const STATUS_TO_ACTIONS_MAP = [
        self::STATUS_NEW => [self::ACTION_CANCEL, self::ACTION_RESPOND],
        self::STATUS_IN_PROGRESS => [self::ACTION_ACCEPT, self::ACTION_ABANDON],
    ];

    public string $status;
    private int $customerID;
    private int $performerID;

    function __construct(int $customerID, int $performerID, string $taskStatus)
    {
        $this->customerID = $customerID;
        $this->performerID = $performerID;
        $this->status = $taskStatus;
    }

    public function getStatusByAction(string $actionCode): ?string
    {
        return self::ACTION_TO_STATUS_MAP[$actionCode] ?? null;
    }

    public function getActionsByStatus(): ?array
    {
        return self::STATUS_TO_ACTIONS_MAP[$this->status] ?? [];
    }
}
