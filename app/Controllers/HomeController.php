<?php

namespace App\Controllers;

use Valitron\Validator;
use App\Controllers\Controller;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class HomeController extends Controller
{
    /**
     * Render the home page
     *
     * @param Request $request
     * @param Response $response
     * @param [type] $args
     * @return void
     */
    public function index(Request $request, Response $response, $args)
    {
        return $this->c->get('view')->render($response, 'home/index.twig', [
            'errors' => $this->c->get('flash')->getFirstMessage('errors')
        ]);
    }

    public function store(Request $request, Response $response, $args)
    {
        $data = $this->validate($request, [
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        dump($data);

        // Perform action

        return $response->withHeader('Location', '/')->withStatus(302);
    }
}
