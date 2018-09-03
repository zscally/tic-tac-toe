<?php

namespace App\Controllers;

use Illuminate\Support\Facades\DB;
use \Slim\Twig as view;
use App\Models\Game;
use App\Models\Session;
use App\Models\Player;

class SessionController extends Controller
{
    public $container;
    private $session, $player, $game, $move;

    public function __construct($container)
    {
        $this->container = $container;
        $this->session = new \App\Models\Session();
        $this->game = new \App\Models\Game();
        $this->player = new \App\Models\Player();
        $this->move = new \App\Models\Move();
    }

    /**
    * Display play for accepting two players and forward them to start a new game.
    */
    public function index($request, $response, $args)
    {
        $messages = $this->flash->getMessages();
        if( ! empty( $messages ) ) {
            $args['messages'] = $messages;
        }
        $session_url = $args['session_url'];
        $session_id = $this->session->getSessionIdFromUrl($session_url);
        //get current game
        $current_game = $this->game->getCurrentGameBySessionId($session_id);
        //get game stats
        $game_stats = $this->GameController->gameStats($session_url);

        //current game moves
        $moves = $this->move->getMovesByGameId($current_game->id);

        //figure out whos move it is.
        $next_move = $this->move->getNextMove($current_game->id);
        $args = [
            'current_game_status' => $current_game->status,
            'winner' => $current_game->winner,
            'current_game_id' => $current_game->id,
            'page_title' => 'Session | ' . $session_url,
            'gameboard' => true,
            'total_games_played' => $game_stats['total_games_played'],
            'tiecount' => $game_stats['ties'],
            'first_move' => $current_game->first_move,
            'next_move' => (is_null($next_move) ? $current_game->first_move : $next_move->player_id),
            'moves' => json_encode($moves),
            'player_one' =>
            [
                'player_id' => $current_game->player_one_id,
                'name' => $this->player->getPlayerNameById($current_game->player_one_id),
                'score' => $game_stats['player_wins'][$current_game->player_one_id]
            ],
            'player_two' =>
            [
                'player_id' => $current_game->player_two_id,
                'name' => $this->player->getPlayerNameById($current_game->player_two_id),
                'score' => $game_stats['player_wins'][$current_game->player_two_id]
            ]
        ];
        return $this->view->render($response, 'game.html', $args);
    }
}
