<?php

namespace tf\controllers;
use tf\controllers\actions\AbstractAction;
use tf\controllers\actions\CancelAction;
use tf\controllers\actions\RespondAction;
use tf\controllers\actions\AcceptAction;
use tf\controllers\actions\AbandonAction;
use tf\exceptions\ExistenceException;

class Task
{
    public const STATUS_NEW = 'new';
    public const STATUS_CANCELED = 'canceled';
    public const STATUS_IN_PROGRESS = 'inProgress';
    public const STATUS_DONE = 'done';
    public const STATUS_FAILED = 'failed';

    public const ROLE_CUSTOMER = 'customer';
    public const ROLE_PERFORMER = 'performer';

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

    /**
     * @throws ExistenceException
     */
    function __construct(
        int $customerID,
        ?int $performerID,
        string $taskStatus
    )
    {
        $this->customerID = $customerID;
        $this->performerID = $performerID;

        if (!in_array($taskStatus, array_keys(self::STATUSES_MAP))) {
            throw new ExistenceException('Указанного статуса не существует');
        }

        $this->status = $taskStatus;
        $this->actions = [
            self::STATUS_NEW => [
                self::ROLE_CUSTOMER => new CancelAction(),
                self::ROLE_PERFORMER => new RespondAction()
            ],
            self::STATUS_IN_PROGRESS => [
                self::ROLE_CUSTOMER => new AcceptAction(),
                self::ROLE_PERFORMER => new AbandonAction()
            ],
        ];
    }

    /**
     * @throws ExistenceException
     */
    public function setStatus(string $status): void
    {
        if (!isset(self::STATUSES_MAP[$status])) {
            throw new ExistenceException('Указанного статуса не существует');
        }

        $this->status = $status;
    }

    public function getStatusByAction(AbstractAction $action): ?string
    {
        return self::ACTIONS_TO_STATUSES_MAP[$action->getInnerTitle()] ?? null;
    }

    /**
     * @throws ExistenceException
     */
    public function getActionByRole(string $role): AbstractAction
    {
        if ($role !== self::ROLE_CUSTOMER && $role !== self::ROLE_PERFORMER) {
            throw new ExistenceException('Не корректная роль пользователя');
        }

        return $this->actions[$this->status][$role];
    }
}
