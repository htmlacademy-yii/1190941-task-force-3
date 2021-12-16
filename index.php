<?php

use tf\controllers\Task;
use tf\exceptions\ExistenceException;

require_once 'vendor/autoload.php';

$task = null;
$role = Task::ROLE_CUSTOMER;

try {
    $task = new Task(1, 2, Task::STATUS_NEW);
} catch (ExistenceException $e) {
    echo $e->getMessage();
}

try {
    $task->setStatus(Task::STATUS_IN_PROGRESS);
} catch (ExistenceException $e) {
    echo $e->getMessage();
}

try {
    var_dump($task->getActionByRole($role));
} catch (ExistenceException $e) {
    echo $e->getMessage();
}



