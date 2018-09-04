<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;

class Game extends Model
{
    protected $table = 'games';
    protected $fillable = ['session_id', 'player_one_id', 'player_two_id',
    'first_move', 'winner', 'status'];

    /**
    * Creates a game for a given session id
    * @param $session_id
    * @param $players
    * @return mixed
    */
    public function createGame($session_id, $players)
    {
      $first_player = array_rand([0, 1]);
      $game = $this->create([
          'session_id' => $session_id,
          'player_one_id' => $players[0]->id,
          'player_two_id' => $players[1]->id,
          'first_move' => $players[$first_player]->id,
          'winner' => null,
          'status' => 'New'
      ]);

      return $game;
    }

    /**
     * Gets a game session by session id
     *
    * @param $session_id
    * @return mixed
    */
    public function getGamesBySessionId($session_id)
    {
      return $this->where('session_id', $session_id)->get();
    }

    /**
     * gets a game by game id
     *
     * @param $game_id
     * @return mixed
     */
    public function getGameById($game_id)
    {
      return $this->where('id', $game_id)->first();
    }

    /**
     * gets the current game by session id
     *
     * @param $session_id
     * @return mixed
     */
    public function getCurrentGameBySessionId($session_id)
    {
      return $this->where('session_id', $session_id)->orderBy('created_at', 'desc')->first();
    }

    /**
     * Updates the winner by game id
     *
     * @param $game_id
     * @param $winning_player
     * @param $status
     * @return mixed
     */
    public function updateGameWinnerByGameId($game_id, $winning_player, $status)
    {
      return $this->where('id', $game_id)
             ->update(['winner' => $winning_player, 'status' => $status]);
    }
}
