<?php

namespace App\Controllers;

use Illuminate\Support\Facades\DB;
use \Slim\Twig as view;

class HomeController extends Controller
{
    public function index($request, $response, $args)
    {
        $args['page_title'] = 'Tic Tac Toe';
        $messages = $this->flash->getMessages();
        if( ! empty( $messages ) ) {
            $args['messages'] = $messages;
        }
        return $this->view->render($response, 'newgame.html', $args);
    }

    public function startnewgame($request, $response, $args)
    {
        if( $request->getAttribute('has_errors') )
        {
            $this->flash->addMEssage('error', $request->getAttribute('errors'));
            return $response->withRedirect('/');
        }

            //start new Game

            //setup unique game ID

            //save players and game into DB

            //move them to the playing page.

    }
}
