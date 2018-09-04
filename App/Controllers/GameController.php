<?php
namespace App\Controllers;

use Illuminate\Support\Facades\DB;
use \Slim\Twig as view;
use App\Models\Game;
use App\Models\Session;
use App\Models\Player;

class GameController extends Controller
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

    public function getGameStats($request, $response, $args)
    {
        $session_url = $args['session_url'];
        $gamestats = $this->gameStats($session_url);
        $body = json_encode($gamestats, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
        return $response->withStatus(200)
            ->withHeader("Content-Type", "application/json")
            ->write($body);
    }

    public function newGame($request, $response, $args)
    {
        $session_url = $args['session_url'];
        $session = $this->session->getSessionIdFromUrl($session_url);
        $current_game = $this->game->getCurrentGameBySessionId($session[0]);
        if($current_game->status == 'Complete') {
            $players = $this->player->getPlayersByList([$current_game->player_one_id, $current_game->player_two_id]);
            $game = $this->game->createGame($session[0], $players);
            $this->flash->addMessage('success', [['New game has been created!']]);
        } else {
            $this->flash->addMessage('error', [['Please finish current game before starting a new one!']]);
        }
        return $response->withRedirect('/' . $session_url);
    }

    public function gameStats($session_url)
    {
        //get the session id from URL
        $session_id = $this->session->getSessionIdFromUrl($session_url);
        $games = $this->game->getGamesBySessionId($session_id);
        $game_arr = [
            'total_games_player' => 0,
            'ties' => 0,
            'player_wins' => []
        ];
        $game_arr['total_games_played'] = count($games) - 1;
        foreach($games as $key => $game) {
            if( !isset($game_arr['player_wins'][$game->player_one_id]) )
                $game_arr['player_wins'][$game->player_one_id] = 0;
            if( !isset($game_arr['player_wins'][$game->player_two_id]) )
                $game_arr['player_wins'][$game->player_two_id] = 0;

            if( is_null($game->winner) && $game->status == 'Complete' ) {
                $game_arr['ties']++;
            } elseif ( $game->player_one_id === $game->winner ) {
                $game_arr['player_wins'][$game->player_one_id]++;
            } elseif ( $game->player_two_id === $game->winner ) {
                $game_arr['player_wins'][$game->player_two_id]++;
            }
        }
        return $game_arr;
    }

    public function saveMove($request, $response, $args) {
        if( $request->getAttribute('has_errors') )
        {
            $body = json_encode([
                'status' => 'error',
                'messages' =>  $request->getAttribute('errors'),
                'post_body' => $request->getParsedBody()
            ], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
            return $response->withStatus(200)
                ->withHeader("Content-Type", "application/json")
                ->write($body);
        }
        $post = $request->getParsedBody();
        $move = $this->move->createMove($post);
        $game = $this->game->getGameById($post['game_id']);
        //now get moves and calculate if we have a winner!
        $moves = $this->move->getMovesByGameId($post['game_id']);
        $winner = $this->checkforWinner($moves);
        $winning_player = null;
        $game_status = 'New';

        if( $winner ) {
            $game_status = 'Complete';
            $winning_player = null;
            switch($winner) {
                CASE 'X':
                    $winning_player = $game->player_one_id;
                    break;
                CASE 'O':
                    $winning_player = $game->player_two_id;
                    break;
                CASE 'tie':
                    $winning_player = null;
                    break;
            }
            //update the game
            $this->game->updateGameWinnerByGameId($post['game_id'], $winning_player, $game_status);
        }
        $response_payload = [
            'winner' => $winner,
            'winning_player' => $winning_player,
            'status' => $game_status
        ];
        $body = json_encode($response_payload, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
        return $response->withStatus(200)
            ->withHeader("Content-Type", "application/json")
            ->write($body);
    }

    private function checkforWinner($moves) {
        $winner = null;
        $player_one = null;
        $player_two = null;
        $grid = [
            null, null, null,
            null, null, null,
            null, null, null
        ];

        foreach( $moves as $move ) {
            if( $move->location == 'location1' )
                $grid[0] = $move->player_value;
            if( $move->location == 'location2' )
                $grid[1] = $move->player_value;
            if( $move->location == 'location3' )
                $grid[2] = $move->player_value;
            if( $move->location == 'location4' )
                $grid[3] = $move->player_value;
            if( $move->location == 'location5' )
                $grid[4] = $move->player_value;
            if( $move->location == 'location6' )
                $grid[5] = $move->player_value;
            if( $move->location == 'location7' )
                $grid[6] = $move->player_value;
            if( $move->location == 'location8' )
                $grid[7] = $move->player_value;
            if( $move->location == 'location9' )
                $grid[8] = $move->player_value;
        }
        //now check the grid.
        if(!is_null($grid[0]) && $grid[0] == $grid[1] && $grid[0] == $grid[2])
            $winner = $grid[0];
        if(!is_null($grid[3]) && $grid[3] == $grid[4] && $grid[3] == $grid[5])
            $winner = $grid[3];
        if(!is_null($grid[6]) && $grid[6] == $grid[7] && $grid[6] == $grid[8])
            $winner = $grid[6];

        if(!is_null($grid[0]) && $grid[0] == $grid[3] && $grid[0] == $grid[6])
            $winner = $grid[0];
        if(!is_null($grid[1]) && $grid[1] == $grid[4] && $grid[1] == $grid[7])
            $winner = $grid[1];
        if(!is_null($grid[2]) && $grid[2] == $grid[5] && $grid[2] == $grid[8])
            $winner = $grid[2];

        if(!is_null($grid[0]) && $grid[0] == $grid[4] && $grid[0] == $grid[8])
            $winner = $grid[0];
        if(!is_null($grid[6]) && $grid[6] == $grid[4] && $grid[6] == $grid[2])
            $winner = $grid[6];

        if( $winner ) {
            return $winner;
        } elseif(count($moves) >= 9) {
            return 'tie';
        } else {
            return false;
        }
    }
}
