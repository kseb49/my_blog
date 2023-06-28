<?php

namespace controllers;

use DateTime;
use Exception;
use core\Controller;
use models\RegisterUserModel;

class RegisterUserController extends Controller
{
    protected array $datas;

    public function form()
    {
        if(isset($_SESSION['user'])  && !empty($_SESSION['user'])) {
           $this->redirect();
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
        if($register->confirmMail($this->datas)) {
            if($this->datas['token'] == $register->user['token']) {
                $limit = new DateTime($register->user['send_link']);
                $now = new DateTime(date('Y-m-d H:i:s'));
                $diff = $limit->diff($now);
                if($diff->format("%H") <= 24) {
                    if($register->updateUser()) {
                        $_SESSION['user'] = $register->user;
                        $this->redirect('dashboard');
                    }
                    $_SESSION['errors'] = ['link' => 'Update failed'];
                    $this->redirect('inscription');
                }
                $_SESSION['errors'] = ['link' => 'Lien expiré'];
                $this->redirect('dashboard');
            }
            $_SESSION['errors'] = ['link' => 'Lien non valide'];
            $this->redirect('inscription');
        }
        $_SESSION['errors'] = ['link' => 'Lien non valide - USER'];
        $this->redirect('inscription');
    }
}
