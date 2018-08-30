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

    return $view;
};

$container['db'] = function ($c) {
    $capsule = new \Illuminate\Database\Capsule\Manager;
    $capsule->addConnection($c['settings']['db']);

    $capsule->setAsGlobal();
    $capsule->bootEloquent();

    return $capsule;
};

$container['logger'] = function ($c) {
    $settings = $c->get('settings')['logger'];
    $logger = new Monolog\Logger($settings['name']);
    $logger->pushProcessor(new Monolog\Processor\UidProcessor());
    $logger->pushHandler(new Monolog\Handler\StreamHandler($settings['path'], $settings['level']));
    return $logger;
};

$container['session'] = function(){
    return new \SlimSession\Helper;
};

$container['flash'] = function ($c) {
    return new \Slim\Flash\Messages();
};

$container['tiny'] = function ($c) {
    $settings = $c->get('settings')['system'];
    return new \ZackKitzmiller\Tiny($settings['tiny_key']);
};

$controllers = ['HomeController'];

foreach($controllers as $controller) {
    $container[$controller] = function($c) use ($controller) {
        $myController = '\App\Controllers\\' . $controller;
        return new $myController($c);
    };
}





require __DIR__ . '/../App/middleware.php';
require __DIR__ . '/../App/routes.php';
