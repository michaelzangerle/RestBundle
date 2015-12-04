<?php

namespace RestBundle\Annotations;

use Doctrine\Common\Annotations\Annotation;

/**
 * @Annotation
 * Annotation used for validation of http requests with a json schema
 */
class ValidationSchema
{
    /**
     * @var string
     */
    private $schema;

    /**
     * Returns the path to the json schema
     *
     * @return string
     */
    public function getSchema()
    {
        return $this->schema;
    }

    /**
     * @param array $options
     */
    public function __construct(array $options)
    {
        if (isset($options['schema'])) {
            $this->schema = $options['schema'];
        }

        unset($options['schema']);
    }
}
