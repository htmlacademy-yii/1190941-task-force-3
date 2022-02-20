<?php

namespace tf\models;

use tf\models\actions\AbstractAction;
use tf\models\actions\CancelAction;
use tf\models\actions\RespondAction;
use tf\models\actions\AcceptAction;
use tf\models\actions\RefuseAction;
use tf\exceptions\ExistenceException;
use Yii;

class Task
{
    public const STATUS_NEW = 1;
    public const STATUS_CANCELED = 2;
    public const STATUS_IN_PROGRESS = 3;
    public const STATUS_DONE = 4;
    public const STATUS_FAILED = 5;

    public const ROLE_CUSTOMER = 'customer';
    public const ROLE_PERFORMER = 'performer';

    public const STATUSES_MAP = [
        self::STATUS_NEW => 'Новое',
        self::STATUS_CANCELED => 'Отменено',
        self::STATUS_IN_PROGRESS => 'В работе',
        self::STATUS_DONE => 'Выполнено',
        self::STATUS_FAILED => 'Провалено',
    ];

    private const ACTIONS_TO_STATUSES_MAP = [
        CancelAction::CODE => self::STATUS_CANCELED,
        AcceptAction::CODE => self::STATUS_DONE,
        RefuseAction::CODE => self::STATUS_FAILED
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

        if (!isset(self::STATUSES_MAP[$taskStatus])) {
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
                self::ROLE_PERFORMER => new RefuseAction()
            ],
        ];
    }

    public function getStatusByAction(AbstractAction $action): ?string
    {
        return self::ACTIONS_TO_STATUSES_MAP[$action->getInnerTitle()] ?? null;
    }

    /**
     * @throws ExistenceException
     */
    public function getActionByRole(int $userID): ?AbstractAction
    {
        $role = array_key_first(Yii::$app->authManager->getRolesByUser($userID));

        if ($role !== self::ROLE_CUSTOMER && $role !== self::ROLE_PERFORMER) {
            throw new ExistenceException('Не корректная роль пользователя');
        }

        $action = $this->actions[$this->status][$role] ?? null;

        if ($action && $action->checkPermission($userID, $this->customerID, $this->performerID)) {
            return $action;
        }

        return null;
    }
}
