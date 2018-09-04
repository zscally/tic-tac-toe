<?php
use Respect\Validation\Validator as v;

//entry point setup a new game.
$app->get('/', 'HomeController:index')->setName('home');
$app->get('/{session_url}', 'SessionController:index')->setName('gameBoard');


//new game route and validation.
$playerOneValidator = v::alnum()->length(1, 25)->noWhitespace()->setName('Player one\'s name');
$playerTwoValidator = v::alnum()->length(1, 25)->noWhitespace()->setName('Player two\'s name');
$newGameValidator = array(
  'playerOne' => $playerOneValidator,
  'playerTwo' => $playerTwoValidator
);
$app->post('/startnewgame', 'HomeController:startnewgame')
  ->setName('startnewgame')
  ->add(new \DavidePastore\Slim\Validation\Validation($newGameValidator));

$app->get('/getgamestats/{session_url}', 'GameController:getGameStats')
    ->setName('getgamestats');

$game_id = v::intVal()->setName('Game ID');
$location = v::stringType()->min(9)->max(9)->noWhitespace()->setName('Location ID');
$player_id = v::intVal()->setName('Player ID');
$player_value = v::stringType()->setName('Player Value');
$newMoveValidator = array(
  'game_id' => $playerOneValidator,
  'location' => $playerTwoValidator,
  'player_id' => $player_id,
  'player_value' => $player_value
 );
$app->post('/game/saveMove', 'GameController:saveMove')->setName('SaveMove')
    ->add(new \DavidePastore\Slim\Validation\Validation($newMoveValidator));

$app->get('/game/newGame/{session_url}', 'GameController:newGame')->setName('newGame');
