<?php

namespace App\Utils;

class File
{
    public static function createDirectory(string $path): bool
    {
        return is_dir($path) || mkdir($path, 0777, true);
    }

    public static function copy(string $source, string $destination): bool
    {
        if (!file_exists($source)) {
            return false;
        }

        if (!self::createDirectory(dirname($destination))) {
            return false;
        }

        return copy($source, $destination);
    }

    public static function delete(string $path, bool $recursive = false): bool
    {
        if (!file_exists($path)) {
            return false;
        }

        if (!is_dir($path)) {
            return unlink($path);
        }

        if ($recursive) {
            foreach (scandir($path) as $file) {
                if ($file === '.' || $file === '..') continue;
                self::delete($path . DIRECTORY_SEPARATOR . $file, true);
            }
        }

        return rmdir($path);
    }

    public static function extension(string $path): string
    {
        return strtolower(pathinfo($path, PATHINFO_EXTENSION));
    }

    public static function size(string $path): int
    {
        return file_exists($path) ? filesize($path) : 0;
    }

    public static function upload(
        array $file,
        string $uploadDir = 'uploads',
        array $allowedExtensions = [],
        array $allowedTypes = [],
        int $maxSize = 0
    ): array {
        if ($file['error'] !== UPLOAD_ERR_OK) {
            return ['success' => false, 'message' => 'File upload failed'];
        }

        if ($maxSize > 0 && $file['size'] > $maxSize) {
            $maxSizeMB = $maxSize / 1024 / 1024;
            return ['success' => false, 'message' => "File too large (max {$maxSizeMB}MB)"];
        }

        if (!empty($allowedExtensions) && !in_array(self::extension($file['name']), $allowedExtensions)) {
            return ['success' => false, 'message' => "Invalid file extension"];
        }

        if (!empty($allowedTypes) && !in_array($file['type'], $allowedTypes)) {
            return ['success' => false, 'message' => 'Invalid file type'];
        }

        if (!self::createDirectory($uploadDir)) {
            return ['success' => false, 'message' => 'Failed to create the upload directory'];
        }

        $filename = uniqid() . '_' . basename($file['name']);
        $destination = $uploadDir . DIRECTORY_SEPARATOR . $filename;

        if (!move_uploaded_file($file['tmp_name'], $destination)) {
            return ['success' => false, 'message' => 'Failed to save file'];
        }

        return [
            'success' => true,
            'message' => 'File uploaded successfully',
            'path' => $destination
        ];
    }
}
