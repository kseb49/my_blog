<?php 

namespace models;

use core\Model;

class RegistrationModel extends Model
{
    protected string $f_name;
    protected string $l_name;
    protected string $pseudo;
    protected string $email;
    protected string $password;
    protected string $confirm_password;

    public function rules(){
        return[
            'f_name'=>[self::REQUEST_REQUIRED,[self::REQUEST_MIN,2],[self::REQUEST_MAX,50]],
            'l_name'=>[self::REQUEST_REQUIRED,[self::REQUEST_MIN,2],[self::REQUEST_MAX,50]],
            'pseudo'=>[self::REQUEST_REQUIRED,self::REQUEST_UNIQUE,[self::REQUEST_MIN,3],[self::REQUEST_MAX,50]],
            'email'=>[self::REQUEST_EMAIL,self::REQUEST_REQUIRED,self::REQUEST_UNIQUE,[self::REQUEST_MIN,10],[self::REQUEST_MAX,100]],
            'password'=>[self::REQUEST_REQUIRED,[self::REQUEST_MIN,6],[self::REQUEST_MAX,100]],
            'confirm_password'=>[self::REQUEST_REQUIRED,[self::REQUEST_MATCHING,"password"]]
        ];
    }

    public function registerUser(){
       $request = $this->connect->prepare('INSERT INTO users (f_name,l_name,email,password,pseudo,role,confirmation_date,send_link,token) values(:prenom,:nom,:email,:password,:pseudo,:role,:confirmation_date,NOW(),:token');
       $request->execute([
        ":prenom"=>$this->f_name,
        ":nom"=>$this->l_name,
        ":email"=>$this->email,
        ":password"=>password_hash($this->password,PASSWORD_DEFAULT),
        ":role"=>0,
        ":pseudo"=>$this->pseudo,
        ":token"=>$this->pseudo,

       ])
    }
        
    

}
