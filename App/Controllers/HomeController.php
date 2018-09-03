<?php

namespace App\Controllers;

use Illuminate\Support\Facades\DB;
use \Slim\Twig as view;
use \App\Models\Game;
use \App\Models\Session;
use \App\Models\Player;

class HomeController extends Controller
{
    public $container, $tiny;

    private $session, $player, $game;

    public function __construct($container)
    {
        $this->container = $container;
        $this->session = new \App\Models\Session();
        $this->game = new \App\Models\Game();
        $this->player = new \App\Models\Player();
        $this->tiny = $container->tiny;
    }

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
        return $this->view->render($response, 'start.html', $args);
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
        $post = $request->getParsedBody();

        $session_url = $this->tiny->to(time().microtime());
        $session = $this->session->createSession($request, $session_url);

        $players = $this->player->createPlayers([$post['playerOne'], $post['playerTwo']]);

        $game = $this->game->createGame($session->id, $players);

        $this->flash->addMessage('success', [['Game session has been created!']]);

        return $response->withRedirect('/' . $session->session_url, 301);
    }
}
