<?php
namespace App\Controllers;

use Illuminate\Support\Facades\DB;
use \Slim\Twig as view;
use App\Models\Game;

class GameController extends Controller
{


    /**
    * Creates a game for a given session id
    */
    public function createGame($session_id, $players)
    {
        $first_player = array_rand($players);
        $data = [
            'session_id' => $session_id,
            'player_one_id' => $players[0]->id,
            'player_two_id' => $players[1]->id,
            'first_move' => $players[$first_player]->id,
            'status' => 'New'
        ];
        $game = Game::create($data);

        return $game;
    }
}
