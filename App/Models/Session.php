<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;

class Session extends Model
{
      protected $table = 'sessions';
      protected $fillable = ['session_url','php_session','ip_address'];

      public function createSession($request, $session_url)
      {
          $session = $this->create([
              'session_url' => $session_url,
              'php_session' => session_id(),
              'ip_address' => $request->getAttribute('ip_address')
          ]);
          return $session;
      }

      public function getSessionIdFromUrl($session_url)
      {
          return $this->where('session_url', $session_url)->pluck('id')->all();
      }
}
