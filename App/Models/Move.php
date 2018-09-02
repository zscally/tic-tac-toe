<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Move extends Model
{
      protected $table = 'moves';
      protected $fillable = ['game_id','location','player_id', 'player_value'];
}
