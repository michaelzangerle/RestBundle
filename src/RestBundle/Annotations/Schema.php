<?php

namespace RestBundle\Annotations;

use Doctrine\Common\Annotations\Annotation;

/**
 * @Annotation
 *
 * Annotation used for validation of http requests with a json schema
 */
class Schema
{
    /**
     * @var string
     */
    private $pathToSchema;

    /**
     * Returns the path to the json schema
     *
     * @return string
     */
    public function getPathToSchema()
    {
        return $this->pathToSchema;
    }

    /**
     * @param array $options
     */
    public function __construct(array $options)
    {
        if (isset($options['path'])) {
            $this->pathToSchema = $options['path'];
        }

        unset($options['path']);
    }
}
