<?php

namespace UserBundle\Controller;

use RestBundle\Controller\RestController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use UserBundle\Manager\UserManagerInterface;

use RestBundle\Annotations\Schema;
use RestBundle\Annotations\Option;

class UserController extends RestController
{
    /**
     * Gets all users from the database
     * @Option(method="GET")
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
     * @Option(method="GET")
     *
     * @param $id
     *
     * @return Response
     */
    public function getAction($id)
    {
        $user = $this->getManager()->getUserById($id);
        if (!$user) {
            return $this->createResponse(null, 404);
        }

        return $this->createResponse($user);
    }

    /**
     * Creates a new user
     * @Option(method="POST")
     * @Schema(path="@UserBundle/Resources/config/schema/user/post.json")
     *
     * @param Request $request
     *
     * @return Response
     */
    public function postAction(Request $request)
    {
        // TODO error case
        $user = $this->getManager()->saveUser($request->request->all());

        return $this->createResponse($user, 200);
    }

    /**
     * Updates an existing user completely
     * @Option(method="PUT")
     * @Schema(path="@UserBundle/Resources/config/schema/user/put.json")
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
     * @Option(method="PATCH")
     * @Schema(path="@UserBundle/Resources/config/schema/user/patch.json")
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
     * Deletes a user by id
     * @Option(method="DELETE")
     *
     * @param $id
     *
     * @return Response
     */
    public function deleteAction($id)
    {
        // TODO error case
        $this->getManager()->deleteUser($id);

        return $this->createResponse(null, 204);
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
