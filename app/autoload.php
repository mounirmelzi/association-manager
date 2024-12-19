<?php

require_once __DIR__ . "/core/autoload.php";

spl_autoload_register(function ($classname) {
    $path = explode("\\", $classname);
    $file = array_pop($path) . ".php";
    unset($path[0]);

    $path = array_map("strtolower", $path);
    array_push($path, $file);
    $path = implode(DIRECTORY_SEPARATOR, $path);

    $path = __DIR__ . DIRECTORY_SEPARATOR . $path;
    if (file_exists($path)) {
        require_once $path;
    }
});
