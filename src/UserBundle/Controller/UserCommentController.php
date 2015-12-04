<?php

namespace UserBundle\Controller;

use RestBundle\Controller\RestController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use UserBundle\Manager\UserManagerInterface;
use RestBundle\Annotations\Schema;
use RestBundle\Annotations\Option;

class UserCommentController extends RestController
{
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
