<?php

namespace RestBundle\Exceptions;

use HadesArchitect\JsonSchemaBundle\Error\Error;
use Symfony\Component\HttpKernel\Exception\HttpException;

class JsonSchemaException extends HttpException
{
    /**
     * JsonSchemaException constructor.
     *
     * @param Error[] $errors
     */
    public function __construct(array $errors)
    {
        $messages = [];
        foreach ($errors as $error) {
            $messages[] = $error->getProperty().': '.$error->getViolation();
        }

        parent::__construct(400, json_encode($messages));
    }
}
