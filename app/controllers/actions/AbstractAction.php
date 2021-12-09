<?php

namespace tf\controllers\actions;

abstract class AbstractAction
{
    abstract public function getTitle(): string;
    abstract public function getInnerTitle(): string;

    // qstn не нравится что нужно передавать 3 аргумента, используются по факту 2
    abstract public function checkPermissions(int $userID, int $customerID, int $performerID): bool;
}
