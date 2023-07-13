<?php

namespace controllers;

use DateTime;
use core\Auth;
use Exception;
use utils\Mail;
use utils\Flash;
use core\Controller;
use models\RegisterUserModel;

class RegisterUserController extends Controller
{
    protected array $datas;

    public function form()
    {
        if(Auth::isConnect()) {
           $this->redirect();
        }
        return $this->twig->display('registration.twig');
    }

    /**
     * Register a new user
     *
     * @param array $datas $_POST
     * @return void
     */
    public function register(array $datas)
    {
        try{
            $this->datas = $datas;
            $register = new RegisterUserModel();
            if ($register->loadDatas($this->datas)->validate()) {
                if ($register->registerUser()) {
                    $mail = new Mail();
                    $message = $this->twig->render("templates/mail/validation-mail.twig",["link" => $register->link,]);
                    if ($mail->mail($register->email,$message,"Confirmez votre compte",$register->f_name." ".$register->l_name,'Recopier ce lien pour valider votre compte : '.$register->link) === true) {
                        Flash::flash('success', 'Vous avez reçu un mail pour confirmer votre compte');
                        $this->redirect(REF);
                    }
                }
                throw new Exception("Merci de réessayer");
            }
            throw new Exception("Tous les champs doivent être remplis");
        }catch(Exception $e){
            Flash::flash('danger',$e->getMessage());
            $this->redirect(REF);
        }
    }

    /**
     * Validate an user account from the mail
     *
     * @param array $datas $_GET [$id,$token]
     * @return void
     */
    public function validateFromMail(array $datas) {
        try{
            $this->datas = $datas;
            $register = new RegisterUserModel();
            if ($register->confirmMail($this->datas) === true) { // Retrieve the user.
                if ($this->datas['token'] == $register->user['token']) {
                    $limit = new DateTime($register->user['send_link']);
                    $now = new DateTime(date('Y-m-d H:i:s'));
                    $diff = $limit->diff($now);
                    if ($diff->format("%H") <= 24) { // The link must be less than 24hrs.
                        if ($register->updateUser() === true) {
                            Auth::createUser($register->user); // Connect the user.
                            Flash::flash('success',"Votre compte est confirmé");
                            $this->redirect('dashboard');
                        }
                        throw new Exception('Votre compte n\'a pas était validé');
                    }
                    throw new Exception('Lien expiré');
                }
                throw new Exception('Lien non valide');
            }
            throw new Exception('Lien non valide - User');

        }catch(Exception $e) {
            Flash::flash('danger',$e->getMessage());
            $this->redirect('inscription');
        }
    }
}
