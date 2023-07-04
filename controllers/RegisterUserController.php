<?php

namespace controllers;

use DateTime;
use Exception;
use utils\Mail;
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
                        $mail = new Mail();
                        if($mail->mail($register->email,$register->f_name." ".$register->l_name,$register->message,'Recopier ce lien pour valider votre compte : '.$register->link)){
                                $_SESSION['flash'] = ['success' => 'Vous avez reçu un mail pour confirmer votre compte'];
                                $this->redirect();
                        }
                        // if($register->mail()){
                        //     $this->twig->display('registration.twig');
                        // }
                   }
                   $this->redirect();
                }
                $_SESSION['flash'] = ['register' => 'Erreur interne'];
                $this->redirect();
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
                        $_SESSION['user']['token'] = hash('md5',uniqid(true));
                        $this->redirect('dashboard');
                    }
                    $_SESSION['flash'] = ['danger' => 'Update failed'];
                    $this->redirect('inscription');
                }
                $_SESSION['flash'] = ['danger' => 'Lien expiré'];
                $this->redirect('dashboard');
            }
            $_SESSION['flash'] = ['danger' => 'Lien non valide'];
            $this->redirect('inscription');
        }
        $_SESSION['flash'] = ['danger' => 'Lien non valide - USER'];
        $this->redirect('inscription');
    }
}
