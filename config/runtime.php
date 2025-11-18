<?php

use Symfony\Component\Runtime\SymfonyRuntime;

return static function (array $context) {
    $options = [];

    if (!is_file($context['project_dir'].'/.env')) {
        $options['disable_dotenv'] = true;
        $_SERVER['APP_ENV'] ??= 'dev';
        $_SERVER['APP_DEBUG'] ??= '1';
    }

    return $options;
};

