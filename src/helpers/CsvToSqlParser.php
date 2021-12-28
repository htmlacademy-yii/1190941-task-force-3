<?php

namespace tf\helpers;
use SplFileInfo;
use SplFileObject;
use tf\exceptions\ExistenceException;

class CsvToSqlParser
{
    /**
     * @throws ExistenceException
     */
    public static function parse(string $sourceFilePath, string $outputDirectory): void
    {
        if (!file_exists($sourceFilePath)) {
            throw new ExistenceException('Исходный файл не найден');
        }

        $fileInfo = new SplFileInfo($sourceFilePath);

        if ($fileInfo->getExtension() != 'csv') {
            throw new ExistenceException('Неверный формат файла');
        }

        $fileObject = new SplFileObject($sourceFilePath);

        $values = [];
        $columns = [];

        while (!$fileObject->eof()) {
            if ($fileObject->key() === 0) {
                $columns = $fileObject->fgetcsv();
            }

            $values[] = sprintf("\t(%s)", implode(', ', array_map(
                function ($value) {
                    $value = htmlspecialchars($value);

                    return "'{$value}'";
                },
                $fileObject->fgetcsv()
            )));
        }

        $tableName = $fileInfo->getBasename('.csv');

        $sqlQuery = sprintf(
            "INSERT INTO `%s` (%s)\r\nVALUES %s;",
            $tableName,
            implode(', ', array_map(function ($value) {
                // Убираю ﻿ из названия колонок
                $value = preg_replace( '/[\x{200B}-\x{200D}\x{FEFF}]/u', '', $value );
                return "`{$value}`";
            }, $columns)),
            implode(',' . PHP_EOL, $values)
        );

        $outputFilePath = rtrim($outputDirectory, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . "{$tableName}.sql";

        if (!file_put_contents($outputFilePath, $sqlQuery)) {
            throw new ExistenceException("Не удается сохранить sql файл по пути ({$outputFilePath})");
        }
    }
}
