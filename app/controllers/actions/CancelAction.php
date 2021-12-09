<?php

namespace tf\controllers\actions;

class CancelAction extends AbstractAction
{
    public const CODE = 'cancel';

    public function getTitle(): string
    {
        return 'Отменить';
    }

    public function getInnerTitle(): string
    {
        return self::CODE;
    }

    public function checkPermissions(int $userID, int $customerID, int $performerID): bool
    {
        return $userID === $customerID;
    }
}
