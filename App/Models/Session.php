<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Session extends Model
{
      protected $table = 'sessions';
      protected $fillable = ['session_url','php_session','ip_address'];
}
