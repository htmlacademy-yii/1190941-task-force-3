<?php

namespace tf\models\actions;

class RefuseAction extends AbstractAction
{
    public const CODE = 'refuse';

    public function getTitle(): string
    {
        return 'Отказаться от задания';
    }

    public function getInnerTitle(): string
    {
        return self::CODE;
    }

    public function checkPermission(int $userID, int $customerID, ?int $performerID): bool
    {
        return $userID === $performerID;
    }
}
