<?php

namespace RestBundle\Controller;


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
     * @Option(method="OPTIONS")
     * @return Object
     */
    protected abstract function getManager();

    /**
     * Provides information on the supported http methods of the api
     */
    public function optionsAction()
    {
        // TODO make configurable
        $reader = $this->get('annotation_reader');
        $locator = $this->get('file_locator');

        $validationSchemaClass = 'RestBundle\\Annotations\\Schema';
        $optionClass = 'RestBundle\\Annotations\\Option';

        $response = ['OPTIONS' => ''];
        $className = get_class($this);
        $reflectionClass = new \ReflectionClass($className);


        // TODO check if method is public?

        foreach ($reflectionClass->getMethods() as $method) {
            if ($method->isPublic()) {
                /** @var Option $option */
                $option = $reader->getMethodAnnotation($method, $optionClass);

                if ($option && $option->getMethod()) {
                    /** @var Schema $schemaAnnotation */
                    $schemaAnnotation = $reader->getMethodAnnotation($method, $validationSchemaClass);
                    $schema = null;
                    if ($schemaAnnotation && $schemaAnnotation->getPathToSchema()) {
                        $schemaUrl = $locator->locate($schemaAnnotation->getPathToSchema());
                        $schema = json_decode(file_get_contents($schemaUrl), true);
                    }

                    $response[$option->getMethod()] = $schema ? $schema : '';
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
}
