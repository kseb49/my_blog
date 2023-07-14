<?php

namespace core;

final class Auth
{

    /**
     * Create the user session
     *
     * @param array $user
     * @return void
     */
    public static function createUser(array $user)
    {
        $_SESSION['user'] = $user;
        $_SESSION['user']['token'] = hash('md5',uniqid(true));

    }


    /**
     * Check for an active connexion
     *
     * @return boolean
     */
    public static function isConnect() :bool
    {
        $connect = isset($_SESSION['user']) ?? false;
        return $connect;

    }


    /**
     * Check the permission
     *
     * @param integer $role
     * @return boolean
     */
    public static function isAuthorize(int $role):bool
    {
        if (isset($_SESSION['user'])) {
            if (!$role) {
                return true;
            }
            return $_SESSION['user']['role'] == $role;
        }
        return false;

    }


    /**
     * Confirmed a role
     *
     * @param integer $role
     * @return boolean
     */
    public static function hasRole(int $role) :bool
    {
        return $_SESSION['user']['role'] == $role;

    }


    /**
     * Undocumented function
     *
     * @param string $token
     * @return boolean
     */
    public static function checkToken(string $token) :bool
    {
        return $token === $_SESSION['user']['token'];

    }


    /**
     * Destroy the user session
     *
     * @return void
     */
    public static function destroy()
    {
        unset($_SESSION['user']);

    }


}
