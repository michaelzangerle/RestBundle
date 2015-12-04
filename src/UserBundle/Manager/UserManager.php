<?php

namespace UserBundle\Manager;

use Doctrine\ORM\EntityManagerInterface;
use UserBundle\Repository\UserRepository;

/**
 * Manager responsible for handling user business logic
 *
 * Class UserManager
 */
class UserManager implements UserManagerInterface
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * @var UserRepository
     */
    private $repository;

    /**
     * UserManager constructor.
     *
     * @param EntityManagerInterface $em
     * @param UserRepository $repository
     */
    public function __construct(EntityManagerInterface $em, UserRepository $repository)
    {
        $this->em = $em;
        $this->repository = $repository;
    }

    /**
     * @return mixed
     */
    public function getAllUsers()
    {
        return $this->repository->findAll();
    }

    /**
     * @param $id
     *
     * @return mixed
     */
    public function getUserById($id)
    {
        return $this->repository->find($id);
    }

    /**
     * Saves a user with the given data
     *
     * @param $id
     * @param $data
     *
     * @return mixed
     */
    public function saveUser($id, $data)
    {

    }
}
