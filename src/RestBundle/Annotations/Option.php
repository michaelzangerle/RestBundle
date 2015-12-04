<?php

namespace RestBundle\Annotations;

use Doctrine\Common\Annotations\Annotation;

/**
 * @Annotation
 *
 * Annotation used for generating the options response and allow header
 */
class Option
{
    /**
     * @var string
     */
    private $method;

    /**
     * Returns the http method
     *
     * @return string
     */
    public function getMethod()
    {
        return $this->method;
    }

    /**
     * @param array $options
     */
    public function __construct(array $options)
    {
        if (isset($options['method']) && $this->isMethodValid($options['method'])) {
            $this->method = strtoupper($options['method']);
        } else {
            // throw exception or ignore?
        }

        unset($options['method']);
    }

    /**
     * Checks if the given HTTP method is a valid one
     *
     * @param $method
     *
     * @return bool
     */
    public function isMethodValid($method)
    {
        // list could be extended
        switch (strtoupper($method)) {
            case 'GET':
            case 'PUT':
            case 'POST':
            case 'DELETE':
            case 'PATCH':
            case 'OPTIONS':
            case 'HEAD':
                return true;
        }

        return false;
    }
}
