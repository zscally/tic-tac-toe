<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;

class Player extends Model
{
    protected $table = 'players';
    protected $fillable = ['player_name'];

    /**
     * Creates a player
     *
     * @param $player_list
     * @return array
     */
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

    /**
     * Gets a player by ID
     *
     * @param $id
     * @return mixed
     */
    public function getPlayerNameById($id)
    {
      $player = $this->where('id', $id)->pluck('player_name')->all();
      return $player[0];
    }

    /**
     * gets a player by an array of players
     *
     * @param $player_id_list
     * @return mixed
     */
    public function getPlayersByList($player_id_list)
    {
     return $this->whereIn('id', $player_id_list)->get();
    }

}
