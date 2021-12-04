<?php
use tf\controllers\Task;
require_once 'vendor/autoload.php';

$task = new Task(1, 2, 'new');

var_dump($task);
