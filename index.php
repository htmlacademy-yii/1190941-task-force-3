<?php

use tf\controllers\Task;
use tf\exceptions\ExistenceException;

require_once 'vendor/autoload.php';

$task = null;
$role = 'customer';

try {
    $task = new Task(1, 2, 'new');
} catch (ExistenceException $e) {
    echo $e->getMessage();
}

try {
    $task->setStatus('inProgress');
} catch (ExistenceException $e) {
    echo $e->getMessage();
}

try {
    var_dump($task->getActionByStatus($role));
} catch (ExistenceException $e) {
    echo $e->getMessage();
}



