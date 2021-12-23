<?php

use tf\helpers\CsvToSqlParser;
use tf\exceptions\ExistenceException;

require_once 'vendor/autoload.php';

foreach (new FilesystemIterator('data', FilesystemIterator::CURRENT_AS_PATHNAME) as $filePath) {
    try {
        CsvToSqlParser::parse($filePath, 'sql');
    } catch (ExistenceException $e) {
        echo $e->getMessage();
    }
}
