<?php

namespace utils;

use Exception;
use core\Controller;
use PHPMailer\PHPMailer\PHPMailer;

class Mail extends Controller
{

    public function mail(string $adress, ?string $name,string $message,?string $alt) {
        $mail = new PHPMailer(true);
        try{
            // $mail->SMTPDebug = SMTP::DEBUG_SERVER;                   //Enable verbose debug output
            $mail->isSMTP();                                            //Send using SMTP
            $mail->Host       = $this->host;                       //Set the SMTP server to send through
            $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
            $mail->Username   = $this->mail_id;                     //SMTP username
            $mail->Password   = $this->password;                     //SMTP password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
            $mail->Port       = 465;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`
            $mail->setFrom($this->from);
            $mail->addReplyTo($this->from);
            $mail->addAddress($adress, $name);
            $mail->Subject = 'Confirmez votre Email';
            $mail->isHTML(true);
            $mail->Body = $message;
            $mail->AltBody = $alt;
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
