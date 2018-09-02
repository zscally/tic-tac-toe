<?php

namespace App\Controllers;

use Illuminate\Support\Facades\DB;
use \Slim\Twig as view;
use App\Models\Game;
use App\Models\Session;
use App\Models\Player;

class HomeController extends Controller
{
    /**
    * Display play for accepting two players and forward them to start a new game.
    */
    public function index($request, $response, $args)
    {
        $args['page_title'] = 'Tic Tac Toe';
        $messages = $this->flash->getMessages();
        if( ! empty( $messages ) ) {
            $args['messages'] = $messages;
        }
        return $this->view->render($response, 'newgame.html', $args);
    }

    /**
    * Starts a new session, allowing for multible games to be played within the
    * session.
    */
    public function startnewgame($request, $response, $args)
    {
        if( $request->getAttribute('has_errors') )
        {
            $this->flash->addMEssage('error', $request->getAttribute('errors'));
            return $response->withRedirect('/');
        }

        //get post vars
        $post = $request->getParsedBody();

        //start a new session
        $session = $this->createSession($request);

        //save players
        $players = $this->createPlayers([$post['playerOne'], $post['playerTwo']]);

        //start new Game
        $game = $this->GameController->createGame($session->id, $players);

        $this->flash->addMessage('success', [['Game session has been created!']]);
        return $response->withRedirect('/' . $session->session_url, 301);
    }

    private function createSession($request)
    {
        $session_url = $this->tiny->to(time().microtime());
        $session = Session::create([
            'session_url' => $session_url,
            'php_session' => session_id(),
            'ip_address' => $request->getAttribute('ip_address')
        ]);

        return $session;
    }

    private function createPlayers($player_list)
    {
        $players = [];
        foreach( $player_list as $player )
        {
            $players[] = Player::create([
                'player_name' => $player
            ]);
        }
        return $players;
    }
}
