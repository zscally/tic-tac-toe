<?php

session_start();

require __DIR__ . '/../vendor/autoload.php';

$config = require __DIR__ . '/../bootstrap/config.php';
$app = new \Slim\App($config);


$container = $app->getContainer();

$container['view'] = function ($container) {
    $view = new \Slim\Views\Twig(__DIR__ . '/../resources/views', [
        'cache' => __DIR__ . '/../resources/tmp/',
        'debug' => true
    ]);

    $view->addExtension(new \Slim\Views\TwigExtension(
        $container->router,
        $container->request->getUri()
    ));

    $view->addExtension(new Twig_Extension_Debug());

    return $view;
};

$capsule = new \Illuminate\Database\Capsule\Manager;
$capsule->addConnection($container['settings']['db']);
$capsule->setAsGlobal();
$capsule->bootEloquent();

$container['db'] = function ($container) use ($capsule) {
    return $capsule;
};

$container['logger'] = function ($container) {
    $settings = $container->get('settings')['logger'];
    $logger = new Monolog\Logger($settings['name']);
    $logger->pushProcessor(new Monolog\Processor\UidProcessor());
    $logger->pushHandler(new Monolog\Handler\StreamHandler($settings['path'], $settings['level']));
    return $logger;
};

$container['session'] = function(){
    return new \SlimSession\Helper;
};

$container['flash'] = function ($container) {
    return new \Slim\Flash\Messages();
};

$container['tiny'] = function ($container) {
    $settings = $container->get('settings')['system'];
    return new \ZackKitzmiller\Tiny($settings['tiny_key']);
};

$controllers = ['HomeController', 'SessionController', 'GameController', 'PlayerController'];

foreach($controllers as $controller) {
    $container[$controller] = function($container) use ($controller) {
        $myController = '\App\Controllers\\' . $controller;
        return new $myController($container);
    };
}

require __DIR__ . '/../App/middleware.php';
require __DIR__ . '/../App/routes.php';
