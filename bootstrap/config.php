<?php
return [
    'settings' => [
        'env' => 'dev',
        'displayErrorDetails' => True, // set to false in production
        'system' => [
            'domain' => 'http://localhost:8000/',
            'tiny_key' => 'gvXbPf8JuntCzwFBW65jrONZRDmh4AdLk29x0HclsMy1eVKI3pYGUEiSqTao7Q'
        ],
        // Renderer settings
        'view' => [
            'template_path' => __DIR__ . '/../resources/views',
            'cache_path' => __DIR__ . '/../resources/tmp',
            'debug' => true,
            'cache' => false
        ],
        //Database Info
        'db' => [
            'driver' => 'mysql',
            'host' => 'localhost',
            'database' => 'tictactoe',
            'username' => 'tictactoe',
            'password' => 'idonthaveone',
            'charset' => 'utf8',
            'collation' => 'utf8_unicode_ci',
            'prefix' => '',
        ],
    ],
];
