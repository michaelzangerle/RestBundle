<?php

namespace RestBundle\Controller;

use Doctrine\Common\Annotations\Reader;
use ReflectionMethod;
use Symfony\Component\Config\FileLocator;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Routing\ClassResourceInterface;
use RestBundle\Annotations\Option;
use RestBundle\Annotations\Schema;
use Symfony\Component\HttpFoundation\Response;

/**
 * Abstract controller which provides option action and generates it's response from the given json schema
 *
 * Class RestController
 * @package RestBundle\Controller
 */
abstract class RestController extends FOSRestController implements ClassResourceInterface
{
    /**
     * Returns the manager used for the business logic
     *
     * @return Object
     */
    protected abstract function getManager();

    /**
     * Provides information on the supported http methods of the api
     */
    public function optionsAction()
    {
        /** @var Reader $reader */
        $reader = $this->get('annotation_reader');

        /** @var FileLocator $locator */
        $locator = $this->get('file_locator');

        $response = ['OPTIONS' => ''];
        $className = get_class($this);
        $reflectionClass = new \ReflectionClass($className);

        foreach ($reflectionClass->getMethods() as $method) {
            if ($method->isPublic()) {
                /** @var Option $option */
                $option = $reader->getMethodAnnotation($method, Option::class);

                if ($option && $option->getMethod()) {
                    $response[$option->getMethod()] = $this->getSchema(
                        $reader,
                        $locator,
                        $method
                    );
                }
            }
        }

        $view = $this->view($response, 200, ['Allow' => implode(', ', array_keys($response))]);

        return $this->handleView($view);
    }

    /**
     * Creates a response for given data and with status code
     *
     * @param $data
     * @param $statusCode
     *
     * @return Response
     */
    public function createResponse($data, $statusCode = 200)
    {
        $view = $this->view($data, $statusCode);

        return $this->handleView($view);
    }

    /**
     * Retrieves the schema from the annotation if available
     *
     * @param Reader $reader
     * @param FileLocator $locator
     * @param ReflectionMethod $method
     *
     * @return string
     */
    private function getSchema(Reader $reader, FileLocator $locator, ReflectionMethod $method)
    {
        /** @var Schema $schemaAnnotation */
        $schemaAnnotation = $reader->getMethodAnnotation($method, Schema::class);
        $schema = '';
        if ($schemaAnnotation && $schemaAnnotation->getPathToSchema()) {
            $schemaUrl = $locator->locate($schemaAnnotation->getPathToSchema());
            $schema = json_decode(file_get_contents($schemaUrl), true);
        }

        return $schema;
    }
}
