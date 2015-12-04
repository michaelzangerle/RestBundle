<?php

namespace RestBundle\Controller;


use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Routing\ClassResourceInterface;
use RestBundle\Annotations\HTTPOption;
use RestBundle\Annotations\ValidationSchema;
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
        // TODO make configurable
        $reader = $this->get('annotation_reader');
        $locator = $this->get('file_locator');

        $validationSchemaClass = 'RestBundle\\Annotations\\ValidationSchema';
        $optionClass = 'RestBundle\\Annotations\\HTTPOption';

        $response = ['OPTION' => ''];
        $className = get_class($this);
        $reflectionClass = new \ReflectionClass($className);
        foreach ($reflectionClass->getMethods() as $method) {
            /** @var HTTPOption $option */
            $option = $reader->getMethodAnnotation($method, $optionClass);
            if ($option && $option->getMethod()) {
                /** @var ValidationSchema $schemaAnotation */
                $schemaAnnotation = $reader->getMethodAnnotation($method, $validationSchemaClass);
                $schema = null;
                if ($schemaAnnotation && $schemaAnnotation->getSchema()) {
                    $schemaUrl = $locator->locate($schemaAnnotation->getSchema());
                    $schema = json_decode(file_get_contents($schemaUrl), true);
                }

                $response[$option->getMethod()] = $schema ? $schema : '';
            }
        }

        // TODO caching?

        $view = $this->view($response,200,['Allow' => implode(', ',array_keys($response))]);

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
