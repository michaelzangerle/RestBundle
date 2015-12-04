<?php

namespace UserBundle\Controller;

use RestBundle\Controller\RestController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use UserBundle\Manager\UserManagerInterface;

class UserController extends RestController
{
    /**
     * Gets all users from the database
     *
     * @return Response
     */
    public function cgetAction()
    {
        $users = $this->getManager()->getAllUsers();

        return $this->createResponse($users);
    }

    /**
     * Returns a user specified by a given id
     *
     * @param $id
     *
     * @return Response
     */
    public function getAction($id)
    {
        $user = $this->getManager()->getUserById($id);
        if (!$user) {
            return $this->createResponse($user, 404);
        }

        return $this->createResponse($user);
    }

    /**
     * Creates a new user
     *
     * @param Request $request
     */
    public function postAction(Request $request)
    {

    }

    /**
     * Updates an existing user completely
     *
     * @param $id
     * @param Request $request
     */
    public function putAction($id, Request $request)
    {

    }

    /**
     * Makes a partial update of an existing user
     *
     * @param $id
     * @param Request $request
     */
    public function patchAction($id, Request $request)
    {

    }

    /**
     * Returns the manager used for the business logic
     *
     * @return UserManagerInterface
     */
    protected function getManager()
    {
        return $this->get('user.userManager');
    }
}
