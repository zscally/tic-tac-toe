<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Player extends Model
{
      protected $table = 'players';
      protected $fillable = ['player_name'];
}
