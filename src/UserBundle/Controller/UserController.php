<?php

namespace UserBundle\Controller;

use RestBundle\Controller\RestController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use UserBundle\Manager\UserManagerInterface;
use RestBundle\Annotations\ValidationSchema;

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
     * @ValidationSchema(schema="@UserBundle/Resources/config/schema/user/test.json")
     *
     * @param Request $request
     *
     * @return Response
     */
    public function postAction(Request $request)
    {
        // TODO error case
        $user = $this->getManager()->saveUser($request->attributes->all());

        return $this->createResponse($user, 200);
    }

    /**
     * Updates an existing user completely
     *
     * @param $id
     * @param Request $request
     *
     * @return Response
     */
    public function putAction($id, Request $request)
    {
        // TODO error case
        $user = $this->getManager()->saveUser($id, $request->attributes->all());

        return $this->createResponse($user, 200);
    }

    /**
     * Makes a partial update of an existing user
     *
     * @param $id
     * @param Request $request
     *
     * @return Response
     */
    public function patchAction($id, Request $request)
    {
        // TODO error case
        $user = $this->getManager()->saveUser($id, $request->attributes->all());

        return $this->createResponse($user, 200);
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
