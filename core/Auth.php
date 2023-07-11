<?php 

namespace core;

final class Auth
{
    public static function isAuthorize(int $role):bool {

        if(isset($_SESSION['user'])) {
            if(!$role) {
                return true;
            }
            return $_SESSION['user']['role'] == $role;
        }
        return false;
    }

    public static function getRole(int $role) :bool{
        return $_SESSION['user']['role'] == $role;
    }

    public static function isConnect() :bool{
        $connect = isset($_SESSION['user']) ?? false;
        return $connect;
    }

}