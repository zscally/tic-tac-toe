<?php

namespace App\Controllers;

use Illuminate\Support\Facades\DB;
use \Slim\Twig as view;
use App\Models\Game;
use App\Models\Session;
use App\Models\Player;
use App\Models\Move;

class PlayerController extends Controller
{
    public $container;
    private $session, $player, $game;

    public function __construct($container)
    {
        $this->container = $container;
        $this->session = new \App\Models\Session();
        $this->game = new \App\Models\Game();
        $this->player = new \App\Models\Player();
        $this->move = new \App\Models\Move();
    }

    public function getGameMoves($request, $response, $args)
    {
        return $this->player->getMovesByGameId($game_id);
    }
