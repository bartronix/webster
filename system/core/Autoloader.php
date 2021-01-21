<?php

function autoloadSystem($className)
{
    $paths = [
        '/app/controllers/',
        '/app/views/',
        '/system/core/',
        '/system/core/exceptions/',
        '/system/utils/',
    ];
    $parts = explode('\\', $className);
    $className = end($parts);
    $file = $className.'.php';
    for ($i = 0; $i < count($paths); ++$i) {
        if (file_exists(APP_ROOT.$paths[$i].$file)) {
            include_once APP_ROOT.$paths[$i].$file;
        }
    }
}

spl_autoload_register('autoloadSystem');
