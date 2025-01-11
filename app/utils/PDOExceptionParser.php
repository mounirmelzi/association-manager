<?php

namespace App\Utils;

use PDOException;

class PDOExceptionParser
{
    public static function toErrorArray(PDOException $exception): array
    {
        $errors = [];
        $message = $exception->getMessage();

        $patterns = [
            'duplicate' => [
                'pattern' => "/Duplicate entry '(.+)' for key '(.+)'/",
                'message' => "Value '{value}' already exists",
                'code' => 'SQLSTATE[23000]'
            ],
            'too_long' => [
                'pattern' => "/column '(.+)'/",
                'message' => "Value too long for column",
                'code' => 'SQLSTATE[22001]'
            ],
            'not_null' => [
                'pattern' => "/Column '(.+)' cannot be null/",
                'message' => "Value cannot be empty",
                'code' => 'SQLSTATE[23000]'
            ]
        ];

        foreach ($patterns as $type => $config) {
            if (strpos($message, $config['code']) === false) {
                continue;
            }

            if (preg_match($config['pattern'], $message, $matches)) {
                if ($type === 'duplicate') {
                    $constraintName = $matches[2];
                    if (strpos($constraintName, 'unique__') === 0) {
                        $columns = explode('__', $constraintName);
                        array_shift($columns);

                        foreach ($columns as $column) {
                            $errors[$column] = str_replace('{value}', $matches[1], $config['message']);
                        }
                    } else {
                        $errors[$matches[2]] = str_replace('{value}', $matches[1], $config['message']);
                    }
                } else {
                    $columnName = $matches[count($matches) - 1];
                    $errorMessage = $config['message'];

                    if (strpos($errorMessage, '{value}') !== false) {
                        $errorMessage = str_replace('{value}', $matches[1], $errorMessage);
                    }

                    $errors[$columnName] = $errorMessage;
                }
            }
        }

        return $errors;
    }
}
