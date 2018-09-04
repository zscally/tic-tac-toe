<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;

class Session extends Model
{
    protected $table = 'sessions';
    protected $fillable = ['session_url','php_session','ip_address'];

    /**
     * creates a session
     *
     * @param $request
     * @param $session_url
     * @return mixed
     */
    public function createSession($request, $session_url)
    {
      $session = $this->create([
          'session_url' => $session_url,
          'php_session' => session_id(),
          'ip_address' => $request->getAttribute('ip_address')
      ]);
      return $session;
    }

    /**
     * gets a session from session url
     *
     * @param $session_url
     * @return mixed
     */
    public function getSessionFromSessionUrl($session_url)
    {
      return $this->where('session_url', $session_url)->first();
    }

    /**
     * gets session id from session url
     *
     * @param $session_url
     * @return mixed
     */
    public function getSessionIdFromUrl($session_url)
    {
      return $this->where('session_url', $session_url)->pluck('id')->all();
    }
}
