<?php 

namespace models;

use Exception;
use core\Model;
use core\ValidateModel;

class RegisterUserModel extends ValidateModel
{
    protected string $f_name;
    protected string $l_name;
    protected string $pseudo;
    protected string $email;
    protected string $password;
    protected string $confirm_password;
    public string $link;

    public function rules(){
        return[
            'f_name'=>[self::REQUEST_REQUIRED,[self::REQUEST_MIN,2],[self::REQUEST_MAX,50]],
            'l_name'=>[self::REQUEST_REQUIRED,[self::REQUEST_MIN,2],[self::REQUEST_MAX,50]],
            'pseudo'=>[self::REQUEST_REQUIRED,self::REQUEST_UNIQUE,[self::REQUEST_MIN,3],[self::REQUEST_MAX,50]],
            'email'=>[self::REQUEST_EMAIL,self::REQUEST_REQUIRED,self::REQUEST_UNIQUE,[self::REQUEST_MIN,10],[self::REQUEST_MAX,100]],
            'password'=>[self::REQUEST_REQUIRED,[self::REQUEST_MIN,3],[self::REQUEST_MAX,100]],
            'confirm_password'=>[self::REQUEST_REQUIRED,[self::REQUEST_MATCHING,"password"]]
        ];
    }

    public function registerUser() {
        $link = hash('md5',uniqid(true));
        $request = $this->connect()->prepare('INSERT INTO users (f_name,l_name,email,password,pseudo,role,send_link,token)
        VALUES (:prenom,:nom,:email,:password,:pseudo,:role,:link,:token)');
        $request->execute([
        "prenom"=>$this->f_name,
        "nom"=>$this->l_name,
        "email"=>$this->email,
        "password"=>password_hash($this->password,PASSWORD_DEFAULT),
        "pseudo"=>$this->pseudo, 
        "role"=>0,
        "link"=>date('Y-m-d H:i:s',time()),
        "token"=>$link
       ]);
       $request->closeCursor();
       $request = $this->connect()->prepare('SELECT * from users where pseudo = ?');
       $request->execute([$this->pseudo]);
       if($resp = $request->fetch()){
            $this->link = "http://blog.test/validation-mail?id={$resp['id']}&token={$link}";
            return true;
        }
       throw new Exception("Erreur de traitement");
    }
        
    
        

}
