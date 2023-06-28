<?php
  namespace models;
  
  use Exception;
  use core\ValidateModel;
  
  class RegisterUserModel extends ValidateModel
  {
    protected string $f_name;
    protected string $l_name;
    protected string $pseudo;
    protected string $email;
    protected string $password;
    protected string $confirm_password;
    public array $user;
    public string $link;
    public string $message;
    
    public function rules() :array {
      return[
        'f_name'=>[self::REQUEST_REQUIRED,[self::REQUEST_MIN,2],[self::REQUEST_MAX,50]],
        'l_name'=>[self::REQUEST_REQUIRED,[self::REQUEST_MIN,2],[self::REQUEST_MAX,50]],
        'pseudo'=>[self::REQUEST_REQUIRED,self::REQUEST_UNIQUE,[self::REQUEST_MIN,3],[self::REQUEST_MAX,50]],
        'email'=>[self::REQUEST_EMAIL,self::REQUEST_REQUIRED,self::REQUEST_UNIQUE,[self::REQUEST_MIN,10],[self::REQUEST_MAX,100]],
        'password'=>[self::REQUEST_REQUIRED,[self::REQUEST_MIN,3],[self::REQUEST_MAX,100]],
        'confirm_password'=>[self::REQUEST_REQUIRED,[self::REQUEST_MATCHING,"password"]]
      ];
    }
    
    public function registerUser() : bool {
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
        $this->message = '<!doctype html>
        <html xmlns="http://www.w3.org/1999/xhtml" xmlns:v="urn:schemas-microsoft-com:vml" xmlns:o="urn:schemas-microsoft-com:office:office">
        
        <head>
        <title>
        </title>
        <!--[if !mso]><!-->
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <!--<![endif]-->
        <meta http-equiv="Content-Type" content="text/html; charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <style type="text/css">
        #outlook a {
          padding: 0;
        }
        
        body {
          margin: 0;
          padding: 0;
          -webkit-text-size-adjust: 100%;
          -ms-text-size-adjust: 100%;
        }
        
        table,
        td {
          border-collapse: collapse;
          mso-table-lspace: 0pt;
          mso-table-rspace: 0pt;
        }
        
        img {
          border: 0;
          height: auto;
          line-height: 100%;
          outline: none;
          text-decoration: none;
          -ms-interpolation-mode: bicubic;
        }
        
        p {
          display: block;
          margin: 13px 0;
        }
        </style>
        <!--[if mso]>
        <noscript>
        <xml>
        <o:OfficeDocumentSettings>
        <o:AllowPNG/>
        <o:PixelsPerInch>96</o:PixelsPerInch>
        </o:OfficeDocumentSettings>
        </xml>
        </noscript>
        <![endif]-->
        <!--[if lte mso 11]>
        <style type="text/css">
        .mj-outlook-group-fix { width:100% !important; }
        </style>
        <![endif]-->
        <!--[if !mso]><!-->
        <link href="https://fonts.googleapis.com/css?family=Ubuntu:300,400,500,700" rel="stylesheet" type="text/css">
        <style type="text/css">
        @import url(https://fonts.googleapis.com/css?family=Ubuntu:300,400,500,700);
        </style>
        <!--<![endif]-->
        <style type="text/css">
        @media only screen and (min-width:480px) {
          .mj-column-per-100 {
            width: 100% !important;
            max-width: 100%;
          }
        }
        </style>
        <style media="screen and (min-width:480px)">
        .moz-text-html .mj-column-per-100 {
          width: 100% !important;
          max-width: 100%;
        }
        </style>
        <style type="text/css">
        noinput.mj-menu-checkbox {
          display: block !important;
          max-height: none !important;
          visibility: visible !important;
        }
        
        @media only screen and (max-width:480px) {
          .mj-menu-checkbox[type="checkbox"]~.mj-inline-links {
            display: none !important;
          }
          
          .mj-menu-checkbox[type="checkbox"]:checked~.mj-inline-links,
          .mj-menu-checkbox[type="checkbox"]~.mj-menu-trigger {
            display: block !important;
            max-width: none !important;
            max-height: none !important;
            font-size: inherit !important;
          }
          
          .mj-menu-checkbox[type="checkbox"]~.mj-inline-links>a {
            display: block !important;
          }
          
          .mj-menu-checkbox[type="checkbox"]:checked~.mj-menu-trigger .mj-menu-icon-close {
            display: block !important;
          }
          
          .mj-menu-checkbox[type="checkbox"]:checked~.mj-menu-trigger .mj-menu-icon-open {
            display: none !important;
          }
        }
        </style>
        </head>
        
        <body style="word-spacing:normal;">
        <div style="">
        <!--[if mso | IE]><table align="center" border="0" cellpadding="0" cellspacing="0" class="" style="width:600px;" width="600" bgcolor="#808080" ><tr><td style="line-height:0px;font-size:0px;mso-line-height-rule:exactly;"><![endif]-->
        <div style="background:#808080;background-color:#808080;margin:0px auto;max-width:600px;">
        <table align="center" border="0" cellpadding="0" cellspacing="0" role="presentation" style="background:#808080;background-color:#808080;width:100%;">
        <tbody>
        <tr>
        <td style="direction:ltr;font-size:0px;padding:20px 0;text-align:center;">
        <!--[if mso | IE]><table role="presentation" border="0" cellpadding="0" cellspacing="0"><tr><td class="" style="vertical-align:top;width:600px;" ><![endif]-->
        <div class="mj-column-per-100 mj-outlook-group-fix" style="font-size:0px;text-align:left;direction:ltr;display:inline-block;vertical-align:top;width:100%;">
        <table border="0" cellpadding="0" cellspacing="0" role="presentation" style="vertical-align:top;" width="100%">
        <tbody>
        <tr>
        <td align="center" style="font-size:0px;word-break:break-word;">
        <!--[if !mso><!-->
        <input type="checkbox" id="72837ba9f398d627" class="mj-menu-checkbox" style="display:none !important; max-height:0; visibility:hidden;" />
        <!--<![endif]-->
        <div class="mj-menu-trigger" style="display:none;max-height:0px;max-width:0px;font-size:0px;overflow:hidden;">
        <label for="72837ba9f398d627" class="mj-menu-label" style="display:block;cursor:pointer;mso-hide:all;-moz-user-select:none;user-select:none;color:#ffffff;font-size:30px;font-family:Ubuntu, Helvetica, Arial, sans-serif;text-transform:uppercase;text-decoration:none;line-height:30px;padding:10px;" align="center">
        <span class="mj-menu-icon-open" style="mso-hide:all;"> &#9776; </span>
        <span class="mj-menu-icon-close" style="display:none;mso-hide:all;"> &#8855; </span>
        </label>
        </div>
        <div class="mj-inline-links" style="">
        <!--[if mso | IE]><table role="presentation" border="0" cellpadding="0" cellspacing="0" align="center"><tr><td style="padding:15px 10px;" class="" ><![endif]-->
        <a class="mj-link" href="'.$this->link.'" target="_blank" style="display:inline-block;color:#ffffff;font-family:Ubuntu, Helvetica, Arial, sans-serif;font-size:13px;font-weight:normal;line-height:22px;text-decoration:none;text-transform:uppercase;padding:15px 10px;"> Cliquez sur ce lien pour valider votre compte. </a>
        <!--[if mso | IE]></td></tr></table><![endif]-->
        </div>
        </td>
        </tr>
        </tbody>
        </table>
        </div>
        <!--[if mso | IE]></td></tr></table><![endif]-->
        </td>
        </tr>
        </tbody>
        </table>
        </div>
        <!--[if mso | IE]></td></tr></table><![endif]-->
        </div>
        </body>
        
        </html>';
        return true;
      }
      throw new Exception("Erreur de traitement");
    }
    
    public function confirmMail(array $datas) : bool {
      $request = $this->connect()->prepare('SELECT * from users where id = ?');
      $request->execute([$datas['id']]);
      $response = $request->fetch() ?? null;
      if(!$response) {
        $_SESSION['errors'] = ['link' => 'Utilisateur inconnu'];
        return false;
      }
      $this->user = $response;
      return true;
    }
    
    public function updateUser() :bool {
      $request = $this->connect()->prepare('UPDATE users set send_link = null,token = null,confirmation_date = NOW() where id = ?');
      if($request->execute([$this->user['id']])){
        return true;
        die();
      }
      return false;
    }
  }