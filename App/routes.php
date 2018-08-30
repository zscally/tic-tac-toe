<?php
use Respect\Validation\Validator as v;

//entry point setup a new game.
$app->get('/', 'HomeController:index')->setName('home');


//new game route and validation.
$playerOneValidator = v::stringType()->length(1, 25);
$playerTwoValidator = v::stringType()->length(1, 25);
$newGameValidator = array(
  'playerOne' => $playerOneValidator,
  'playerTwo' => $playerTwoValidator
);
$app->post('/startnewgame', 'HomeController:startnewgame')
  ->setName('startnewgame')
  ->add(new \DavidePastore\Slim\Validation\Validation($newGameValidator));
