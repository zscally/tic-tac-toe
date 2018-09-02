<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Game extends Model
{
      protected $table = 'games';
      protected $fillable = ['session_id', 'player_one_id', 'player_two_id',
      'first_move', 'winner', 'status'];

      /**
      * Creates a game for a given session id
      */
      public function createGame($session_id, $players)
      {
          $first_player = array_rand($players);
          $game = $this->create([
              'session_id' => $session_id,
              'player_one_id' => $players[0]->id,
              'player_two_id' => $players[1]->id,
              'first_move' => $players[$first_player]->id,
              'status' => 'New'
          ]);

          return $game;
      }
}
