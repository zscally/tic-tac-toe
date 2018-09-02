<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Game extends Model
{
      protected $table = 'games';
      protected $fillable = ['session_id', 'player_one_id', 'player_two_id',
      'first_move', 'winner', 'status'];
}
