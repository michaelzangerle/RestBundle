<?php

namespace RestBundle\Listener;

use Doctrine\Common\Annotations\Reader;
use HadesArchitect\JsonSchemaBundle\Validator\ValidatorServiceInterface;
use RestBundle\Annotations\ValidationSchema;
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
     * Checks for annotations of type @ValidationSchema.
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
                return $annotation instanceof ValidationSchema;
            }
        );

        $data = $event->getRequest()->request->all();

        /** @var ValidationSchema $validateAnnotation */
        foreach ($validateAnnotations as $validateAnnotation) {
            if (!empty($validateAnnotation->getSchema())) {
                $this->validate($validateAnnotation, $data);
            }
        }
    }

    /**
     * Get the schema from within the appropriate bundle and checks it against
     * the json content provided by the request.
     *
     * @param ValidationSchema $annotation
     * @param array $data
     *
     * @throws JsonSchemaException
     */
    public function validate(ValidationSchema $annotation, $data)
    {
        $schemaUrl = $this->fileLocator->locate($annotation->getSchema());
        $schema = json_decode(file_get_contents($schemaUrl));
        $data = json_decode(json_encode($data));

        if (!$this->validator->isValid($data, $schema)) {
            throw new JsonSchemaException($this->validator->getErrors());
        }
    }

    /**
     * Returns the called method
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
