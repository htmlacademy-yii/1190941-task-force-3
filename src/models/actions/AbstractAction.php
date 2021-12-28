<?php

namespace tf\models\actions;

abstract class AbstractAction
{
    abstract public function getTitle(): string;
    abstract public function getInnerTitle(): string;
    abstract public function checkPermissions(int $userID, int $customerID, int $performerID): bool;
}
