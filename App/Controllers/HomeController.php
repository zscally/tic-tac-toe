<?php

namespace App\Controllers;

use Illuminate\Support\Facades\DB;
use \Slim\Twig as view;

class HomeController extends Controller
{
    public function index($request, $response, $args) {
        return $this->view->render($response, 'index.html', $args);
    }
}
