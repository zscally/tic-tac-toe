<?php

namespace App\Controllers;

use Illuminate\Support\Facades\DB;
use \Slim\Twig as view;
use App\Models\Game;
use App\Models\Session;
use App\Models\Player;

class SessionController extends Controller
{
    /**
    * Display play for accepting two players and forward them to start a new game.
    */
    public function index($request, $response, $args)
    {
        $session_id = $args['session_id'];
        $args['page_title'] = 'Session | ' . $session_id;
        $messages = $this->flash->getMessages();
        if( ! empty( $messages ) ) {
            $args['messages'] = $messages;
        }
        return $this->view->render($response, 'game.html', $args);
    }
}
