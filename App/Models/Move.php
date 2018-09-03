<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;

class Move extends Model
{
      protected $table = 'moves';
      protected $fillable = ['game_id','location','player_id', 'player_value'];

      public function createMove($move_data)
      {
          $move = $this->create([
              'game_id' => $move_data['game_id'],
              'location' => $move_data['location'],
              'player_id' => $move_data['player_id'],
              'player_value' => $move_data['player_value']
          ]);
          return $move;
      }

      public function getMovesByGameId($game_id)
      {
          return $this->where('game_id', $game_id)->get();
      }

      public function getNextMove($game_id) {
          //get current moves
          $moves = $this->getMovesByGameId($game_id);
          if( count($moves) == 0 )
          {
              return null;
          } else {
              return $this->where('game_id', $game_id)->orderBy('created_at', 'desc')->first();
          }
      }
}
