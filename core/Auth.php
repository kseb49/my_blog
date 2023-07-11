<?php 

namespace core;

final class Auth
{
    /**
     * Check the permission
     *
     * @param integer $role
     * @return boolean
     */
    public static function isAuthorize(int $role):bool {

        if(isset($_SESSION['user'])) {
            if(!$role) {
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
    public static function getRole(int $role) :bool{
        return $_SESSION['user']['role'] == $role;
    }

    /**
     * Check for an active connexion
     *
     * @return boolean
     */
    public static function isConnect() :bool{
        $connect = isset($_SESSION['user']) ?? false;
        return $connect;
    }

}