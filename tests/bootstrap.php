<?php

$basePath = __DIR__ . '/../../../..';

/*
 * October autoloader
 */
require $basePath . '/bootstrap/autoload.php';

/*
 * Fallback autoloader
 */
$loader = new October\Rain\Support\ClassLoader(
    new October\Rain\Filesystem\Filesystem,
    $basePath,
    $basePath . '/storage/framework/classes.php'
);

$loader->register();
$loader->addDirectories([
    'modules',
    'plugins',
]);

/*
 * Plugin autoloader
 */
require __DIR__ . '/../vendor/autoload.php';
