<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;

class Player extends Model
{
      protected $table = 'players';
      protected $fillable = ['player_name'];

      public function createPlayers($player_list)
      {
          $players = [];
          foreach( $player_list as $player )
          {
              $players[] = $this->create([
                  'player_name' => $player
              ]);
          }
          return $players;
      }

      public function getPlayerNameById($id)
      {
          $player = $this->where('id', $id)->pluck('player_name')->all();
          return $player[0];
      }

}
