<?php

use tf\helpers\CsvToSqlParser;
use tf\exceptions\ExistenceException;

require_once 'vendor/autoload.php';

$outputDir = 'sql';

try {
    CsvToSqlParser::parse('data/categories.csv', $outputDir);
} catch (ExistenceException $e) {
    echo $e->getMessage();
}

try {
    CsvToSqlParser::parse('data/cities.csv', $outputDir);
} catch (ExistenceException $e) {
    echo $e->getMessage();
}
