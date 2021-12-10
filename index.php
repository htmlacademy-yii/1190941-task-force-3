<?php

use tf\controllers\Task;

require_once 'vendor/autoload.php';

$task = new Task(1, 2, 'new');

$task->setStatus('inProgress');

var_dump($task->getActionByStatus());


