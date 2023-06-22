<?php

namespace controllers;

use Exception;
use core\Controller;
use models\RegistrationModel;

class RegisterController extends Controller
{
    protected array $datas;
    protected array $ready_datas;

    public function form()
    {
        $this->twig->display('registration.twig');
    }

    public function register()
    {
        try{

            if (isset($_POST) && !empty($_POST)){
                // if (in_array("", $_POST)) {
                //     $empty_input = [];
                //     foreach ($_POST as $key => $value) {
                //         if(trim($value) == ""){
                //             $empty_input [$key] = "Le champ {$key} est obligatoire";
                //         }
                //     }
                //     $this->twig->display('registration.twig',['errors'=>$empty_input]);
    
                // } 
                $this->datas = $_POST;
                $register = new RegistrationModel();
                $register->loadDatas($this->datas);
                dd($register);
    
            }
            throw new Exception("Tous les champs doivent être remplis 33");
            die();
           
        }catch(Exception $e){
            echo $e->getMessage();
        }




    }
}
