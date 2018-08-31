<?php
use Respect\Validation\Validator as v;

//entry point setup a new game.
$app->get('/', 'HomeController:index')->setName('home');


//new game route and validation.
$playerOneValidator = v::alnum()->length(1, 25)->setName('Player one\'s name');
$playerTwoValidator = v::alnum()->length(1, 25)->setName('Player two\'s name');
$newGameValidator = array(
  'playerOne' => $playerOneValidator,
  'playerTwo' => $playerTwoValidator
);
$app->post('/startnewgame', 'HomeController:startnewgame')
  ->setName('startnewgame')
  ->add(new \DavidePastore\Slim\Validation\Validation($newGameValidator));

/**
* main game board - this will show the game reteaive the current game and let
* JS take over sending and receiving updates from the ajax calls.
*/
$app->get('/{game_id}', 'TicTacToeController:index')->setName('gameBoard');

$app->post('/{game_id}/save_turn', 'TicTacToeController:saveTurn')->setName('SaveTurn');
