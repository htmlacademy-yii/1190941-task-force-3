<?php

namespace tf\controllers\actions;

class AbandonAction extends AbstractAction
{
    public const CODE = 'abandon';

    public function getTitle(): string
    {
        return 'Отказаться';
    }

    public function getInnerTitle(): string
    {
        return self::CODE;
    }

    public function checkPermissions(int $userID, int $customerID, int $performerID): bool
    {
        return $userID === $performerID;
    }
}
