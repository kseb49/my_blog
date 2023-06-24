<?php

namespace controllers;

use Exception;
use core\Controller;
use models\RegisterUserModel;

class RegisterUserController extends Controller
{
    protected array $datas;

    public function form()
    {
        $this->twig->display('registration.twig');
    }

    public function register()
    {
        try{

            if (isset($_POST) && !empty($_POST)){
                $this->datas = $_POST;
                $register = new RegisterUserModel();
                if($register->loadDatas($this->datas)->validate()){
                   if($register->registerUser()){
                    $register->mail($register->link);
                   };
                };
            }
            throw new Exception("Tous les champs doivent être remplis");
            die();
           
        }catch(Exception $e){
            echo $e->getMessage();
        }




    }
}
