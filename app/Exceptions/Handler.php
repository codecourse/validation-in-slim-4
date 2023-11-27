<?php

namespace App\Exceptions;

use Throwable;
use Slim\Flash\Messages;
use Slim\Psr7\Factory\ResponseFactory;
use App\Exceptions\ValidationException;
use Psr\Http\Message\ServerRequestInterface as Request;

class Handler
{
    protected $responseFactory;

    protected $flash;

    public function __construct(ResponseFactory $responseFactory, Messages $flash)
    {
        $this->responseFactory = $responseFactory;
        $this->flash = $flash;
    }

    public function __invoke(Request $request, Throwable $exception)
    {
        if ($exception instanceof ValidationException) {
            $this->flash->addMessage('errors', $exception->getErrors());
    
            return $this->responseFactory
                ->createResponse()
                ->withHeader('Location', '/')
                ->withStatus(302);
        }
    
        throw $exception;
    }
}