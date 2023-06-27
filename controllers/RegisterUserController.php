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
        if(isset($_SESSION['user'])  && !empty($_SESSION['user'])) {
            header("Location: ".BASE);
            die();
        }
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
                        if($register->mail()){
                            $this->twig->display('registration.twig');
                        }
                   };
                };
            }
            throw new Exception("Tous les champs doivent être remplis");
        }catch(Exception $e){
            echo $e->getMessage();
        }
    }

    public function validateFromMail(array $datas){
        $this->datas = $datas;
        $register = new RegisterUserModel();
        if($register->confirm($datas)) {
            session_start();
            $_SESSION['user'] = 'auth';
            $this->twig->display('dashboard');
        };
    }
}
