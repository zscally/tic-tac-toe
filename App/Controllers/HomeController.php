<?php

namespace App\Controllers;

use Illuminate\Support\Facades\DB;
use \Slim\Twig as view;

class HomeController extends Controller
{
    public function index($request, $response, $args)
    {
        $args['page_title'] = 'Tic Tac Toe';
        return $this->view->render($response, 'newgame.html', $args);
    }

    public function startnewgame($request, $response, $args)
    {
        if( ! $request->getAttribute('has_errors'))
        {
            //start new Game

            //setup unique game ID

            //save players and game into DB

            //move them to the playing page.
        } else {
            $errors = $request->getAttribute('errors');
            var_dump($errors);
        }
    }
}
