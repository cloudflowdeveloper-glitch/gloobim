<?php

define('GLOOBIM_START', microtime(true));

$basePath = __DIR__;
define('BASE_PATH', $basePath);

$debugLogFile = $basePath . '/storage/logs/debug.log';
$debugDir = dirname($debugLogFile);
if (!is_dir($debugDir)) {
    @mkdir($debugDir, 0755, true);
}

set_error_handler(function ($severity, $message, $file, $line) use ($debugLogFile) {
    $entry = "[" . date('Y-m-d H:i:s') . "] ERROR #{$severity}: {$message} in {$file} on line {$line}\n";
    @file_put_contents($debugLogFile, $entry, FILE_APPEND);
    return false;
});

set_exception_handler(function ($e) use ($debugLogFile) {
    $entry = "[" . date('Y-m-d H:i:s') . "] FATAL: " . get_class($e) . ": " . $e->getMessage() . " in " . $e->getFile() . " on line " . $e->getLine() . "\n";
    $entry .= "Stack trace:\n" . $e->getTraceAsString() . "\n\n";
    @file_put_contents($debugLogFile, $entry, FILE_APPEND);
});

register_shutdown_function(function () use ($debugLogFile) {
    $error = error_get_last();
    if ($error && in_array($error['type'], [E_ERROR, E_PARSE, E_CORE_ERROR, E_COMPILE_ERROR])) {
        $entry = "[" . date('Y-m-d H:i:s') . "] SHUTDOWN FATAL #{$error['type']}: {$error['message']} in {$error['file']} on line {$error['line']}\n\n";
        @file_put_contents($debugLogFile, $entry, FILE_APPEND);
    }
});

$envPath = $basePath . '/.env';
if (file_exists($envPath)) {
    $lines = file($envPath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos(trim($line), '#') === 0) continue;
        if (strpos($line, '=') === false) continue;
        [$key, $value] = explode('=', $line, 2);
        $key = trim($key);
        $value = trim($value);
        if (!empty($key) && getenv($key) === false) {
            putenv("{$key}={$value}");
            $_ENV[$key] = $value;
        }
    }
}

require $basePath . '/app/helpers.php';

require $basePath . '/core/App.php';
require $basePath . '/core/Config.php';
require $basePath . '/core/Session.php';
require $basePath . '/core/Database.php';
require $basePath . '/core/Request.php';
require $basePath . '/core/Response.php';
require $basePath . '/core/Router.php';
require $basePath . '/core/View.php';
require $basePath . '/core/Controller.php';
require $basePath . '/core/Auth.php';

spl_autoload_register(function (string $class) use ($basePath) {
    $prefixes = [
        'Core\\' => $basePath . '/core/',
        'App\\'  => $basePath . '/app/',
    ];

    foreach ($prefixes as $prefix => $dir) {
        $len = strlen($prefix);
        if (strncmp($prefix, $class, $len) === 0) {
            $relativeClass = substr($class, $len);
            $file = $dir . str_replace('\\', '/', $relativeClass) . '.php';
            if (file_exists($file)) {
                require $file;
                return;
            }
        }
    }
});

\Core\App::setBasePath($basePath);
\Core\App::run();
