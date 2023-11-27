<?php

namespace App\Controllers;

use DI\Container;
use Valitron\Validator;
use App\Exceptions\ValidationException;
use Psr\Http\Message\ServerRequestInterface as Request;

abstract class Controller
{
    /**
     * The container instance.
     *
     * @var \Interop\Container\ContainerInterface
     */
    protected $c;

    /**
     * Set up controllers to have access to the container.
     *
     * @param \Interop\Container\ContainerInterface $container
     */
    public function __construct(Container $container)
    {
        $this->c = $container;
    }

    /**
     * Undocumented function
     *
     * @param Request $request
     * @param array $rules
     * @return void
     */
    public function validate(Request $request, array $rules = [])
    {
        $validator = new Validator(
            $params = $request->getParsedBody()
        );

        $validator->mapFieldsRules($rules);

        if (!$validator->validate()) {
            throw new ValidationException(
                $validator->errors()
            );
        }

        return $params;
    }
}
