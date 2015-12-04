<?php

namespace RestBundle\Controller;


use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Routing\ClassResourceInterface;
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
        $view = $this->view(['empty for now!']);

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
