<?php

namespace UserBundle\Manager;

use UserBundle\Entity\User;

/**
 * Interface UserManagerInterface
 * @package UserBundle\Manager
 */
interface UserManagerInterface
{
    /**
     * Gets all users
     *
     * @return User[]
     */
    public function getAllUsers();

    /**
     * Gets a user by id
     *
     * @param $id
     *
     * @return User
     */
    public function getUserById($id);

    /**
     * Saves a user with the given data
     *
     * @param $id
     * @param $data
     *
     * @return User
     */
    public function saveUser($data, $id = null);

    /**
     * Deletes a user by id
     *
     * @param $id
     */
    public function deleteUser($id);
}
