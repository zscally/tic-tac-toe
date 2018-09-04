<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;

class Move extends Model
{
    protected $table = 'moves';
    protected $fillable = ['game_id','location','player_id', 'player_value'];

    /**
     * Creates a new move
     *
     * @param $move_data
     * @return mixed
     */
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

    /**
     * gets moves by game id
     *
     * @param $game_id
     * @return mixed
     */
    public function getMovesByGameId($game_id)
    {
      return $this->where('game_id', $game_id)->get();
    }

    /**
     * gets the next move by game id
     *
     * @param $game_id
     * @return null
     */
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
