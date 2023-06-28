<?php

namespace core;

use Exception;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\PHPMailer;

abstract class ValidateModel extends Model
{
    
    protected const REQUEST_REQUIRED = 'required';
    protected const REQUEST_MIN = "min";
    protected const REQUEST_MAX = "max";
    protected const REQUEST_EMAIL ='is_email';
    protected const REQUEST_UNIQUE = "unique";
    protected const REQUEST_MATCHING = 'match';
    
    abstract protected function rules();
    
    /**
    * Load the datas incoming from a form
    *
    * @param array $datas
    * @return void
    */
    public function loadDatas(array $datas) {
        foreach($datas as $key => $value){
            if (!property_exists($this,$key)){
                throw new Exception("Le champ {$key} est invalide");
            }
            $this->$key = htmlspecialchars($value);
        }
        return $this;
    }
    
    public function validate():bool {
        foreach ($this->rules() as $input_name => $data) {
            $value = $this->$input_name;
            foreach ($data as $rules) {
                $rule = $rules;
                if(is_array($rule)) { 
                    $rule = $rules[0];
                }
                if($rule === self::REQUEST_REQUIRED && !$value) {
                    throw new Exception("Le champ {$input_name} est obligatoire");
                }
                if($rule === self::REQUEST_MIN && strlen($value)<$rules[1]) {
                    throw new Exception("Le champ {$input_name} ne peut être inferieur à {$rules[1]} caracteres");
                }
                if($rule === self::REQUEST_MAX && strlen($value)>$rules[1]) {
                    throw new Exception("Le champ {$input_name} ne peut être superieur à {$rules[1]} caracteres");
                }
                if($rule === self::REQUEST_EMAIL && !filter_var($value,FILTER_VALIDATE_EMAIL)) {
                    throw new Exception("Le champ {$input_name} doit être de type email");
                }
                if($rule === self::REQUEST_MATCHING && $value !== $this->{$rules[1]}) {
                    throw new Exception("Les champs {$input_name} et {$rules[1]} doivent être identiques");
                }
                if($rule === self::REQUEST_UNIQUE) {
                    $request = $this->connect()->prepare('SELECT * from users where '.$input_name.' = ?');
                    $request->execute([$value]);
                    if($resp = $request->fetch()) {
                        throw new Exception("Il existe deja un utilisateur avec ces identifiants");
                    }
                    $request->closeCursor();
                }
            }
        }
        return true;
    }
    
    public function mail() {
        $mail = new PHPMailer(true);
        try{
            // $mail->SMTPDebug = SMTP::DEBUG_SERVER;                      //Enable verbose debug output
            $mail->isSMTP();                                            //Send using SMTP
            $mail->Host       = 'smtp.gmail.com';                       //Set the SMTP server to send through
            $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
            $mail->Username   = 'kseb49@gmail.com';                     //SMTP username
            $mail->Password   = 'qtmwsgeaozonjzny';                     //SMTP password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
            $mail->Port       = 465;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`
            $mail->setFrom('kseb49@gmail.com', 'Sébastien');
            $mail->addReplyTo('kseb49@gmail.com', 'Admin blog');
            $mail->addAddress($this->email, $this->f_name.' '.$this->l_name);
            $mail->Subject = 'Confirmez votre Email';
            $mail->isHTML(true);
            $mail->Body = $this->message;
            $mail->AltBody = 'Suivez ce lien pour valider votre compte : '.$this->link;
            if(!$mail->send()) {
                throw new Exception($mail->ErrorInfo);
            }
            return true;
        }
        catch(Exception $e) {
            echo $e->getMessage();
        }
    }
}
