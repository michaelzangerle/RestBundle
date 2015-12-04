<?php

namespace UserBundle\Manager;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityNotFoundException;
use UserBundle\Entity\User;
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
     * @return User[]
     */
    public function getAllUsers()
    {
        return $this->repository->findAll();
    }

    /**
     * @param $id
     *
     * @return User
     */
    public function getUserById($id)
    {
        return $this->repository->find($id);
    }

    /**
     * Saves a user with the given data
     *
     * @param $data
     *
     * @param $id
     *
     * @return User
     * @throws EntityNotFoundException
     */
    public function saveUser($data, $id = null)
    {
        $user = $this->getUser($id);

        if (array_key_exists('username', $data)) {
            $user->setUsername($data['username']);
        }

        if (array_key_exists('firstname', $data)) {
            $user->setUsername($data['firstname']);
        }

        if (array_key_exists('lastname', $data)) {
            $user->setUsername($data['lastname']);
        }

        if (array_key_exists('email', $data)) {
            $user->setUsername($data['email']);
        }

        if (array_key_exists('website', $data)) {
            $user->setUsername($data['website']);
        }

        if (!$id) {
            $this->em->persist($user);
        }
        $this->em->flush();

        return $user;
    }

    /**
     * Deletes a user
     *
     * @param $id
     *
     * @throws EntityNotFoundException
     */
    public function deleteUser($id)
    {
        $user = $this->repository->find($id);
        if (!$user) {
            throw new EntityNotFoundException();
        }

        $this->em->remove($user);
        $this->em->flush();
    }

    /**
     * Gets a user for to create or update entity
     *
     * @param $id
     *
     * @return User
     * @throws EntityNotFoundException
     */
    private function getUser($id)
    {
        if (!$id) {
            return new User();
        } else {
            $user = $this->repository->find($id);
            if (!$user) {
                throw new EntityNotFoundException();
            }

            return $user;
        }
    }
}
