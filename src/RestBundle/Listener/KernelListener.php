<?php

namespace RestBundle\Listener;

use Doctrine\Common\Annotations\Reader;
use HadesArchitect\JsonSchemaBundle\Validator\ValidatorServiceInterface;
use RestBundle\Annotations\Schema;
use RestBundle\Exceptions\JsonSchemaException;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;

class KernelListener
{
    /**
     * @var Reader
     */
    private $annotationReader;

    /**
     * @var FileLocator
     */
    private $fileLocator;

    /**
     * @var ValidatorServiceInterface
     */
    private $validator;

    /**
     * @param Reader $annotationReader
     * @param FileLocator $fileLocator
     * @param ValidatorServiceInterface $validator
     */
    public function __construct(
        Reader $annotationReader,
        FileLocator $fileLocator,
        ValidatorServiceInterface $validator
    ) {
        $this->annotationReader = $annotationReader;
        $this->fileLocator = $fileLocator;
        $this->validator = $validator;
    }

    /**
     * Verifies the request body if a schema has been defined with @Schema
     *
     * @param FilterControllerEvent $event
     */
    public function onKernelController(FilterControllerEvent $event)
    {
        $method = $this->getCalledMethod($event);
        $annotations = $this->annotationReader->getMethodAnnotations($method);

        $validateAnnotations = array_filter(
            $annotations,
            function ($annotation) {
                return $annotation instanceof Schema;
            }
        );

        $data = $event->getRequest()->request->all();

        /** @var Schema $validateAnnotation */
        foreach ($validateAnnotations as $validateAnnotation) {
            if (!empty($validateAnnotation->getPathToSchema())) {
                $this->validate($validateAnnotation, $data);
            }
        }
    }

    /**
     * Validates the received data with the given json schema
     *
     * @param Schema $annotation
     * @param array $data
     *
     * @throws JsonSchemaException
     */
    public function validate(Schema $annotation, $data)
    {
        $schemaUrl = $this->fileLocator->locate($annotation->getPathToSchema());
        $schema = json_decode(file_get_contents($schemaUrl));
        $data = json_decode(json_encode($data));

        if (!$this->validator->isValid($data, $schema)) {
            throw new JsonSchemaException($this->validator->getErrors());
        }
    }

    /**
     * Creates the method needed from the class- and method name
     *
     * @param FilterControllerEvent $event
     *
     * @return \ReflectionMethod
     */
    private function getCalledMethod(FilterControllerEvent $event)
    {
        list($object, $method) = $event->getController();
        $className = get_class($object);
        $reflectionClass = new \ReflectionClass($className);

        return $reflectionClass->getMethod($method);
    }
}
