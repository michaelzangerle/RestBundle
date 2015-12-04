<?php

namespace UserBundle\Manager;

/**
 * Interface UserManagerInterface
 * @package UserBundle\Manager
 */
interface UserManagerInterface
{
    /**
     * Gets all users
     *
     * @return mixed
     */
    public function getAllUsers();

    /**
     * Gets a user by id
     *
     * @param $id
     *
     * @return mixed
     */
    public function getUserById($id);

    /**
     * Saves a user with the given data
     *
     * @param $id
     * @param $data
     *
     * @return mixed
     */
    public function saveUser($data, $id = null);
}
