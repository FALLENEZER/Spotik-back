<?php

use App\Kernel;

$projectDir = dirname(__DIR__);
$envFile = $projectDir.'/.env';

if (!is_file($envFile) || !is_readable($envFile)) {
    $options = $_SERVER['APP_RUNTIME_OPTIONS'] ?? [];
    if (!\is_array($options)) {
        $options = [];
    }
    $options['disable_dotenv'] = true;
    $_SERVER['APP_RUNTIME_OPTIONS'] = $options;
    $_SERVER['APP_ENV'] ??= 'dev';
    $_SERVER['APP_DEBUG'] ??= '1';
}

require_once $projectDir.'/vendor/autoload_runtime.php';

return function (array $context) {
    return new Kernel($context['APP_ENV'], (bool) $context['APP_DEBUG']);
};
