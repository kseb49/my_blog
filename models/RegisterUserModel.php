<?php
namespace models;

use core\ValidateModel;

class RegisterUserModel extends ValidateModel
{

    public string $f_name;

    public string $l_name;

    public string $pseudo;

    public string $email;

    public string $password;

    public string $confirm_password;

    public array $user;

    public string $link;

    public string $message;


  public function rules() :array
  {
    return[
      'f_name' => [self::REQUEST_REQUIRED, [self::REQUEST_MIN, 2], [self::REQUEST_MAX, 50]],
      'l_name' => [self::REQUEST_REQUIRED, [self::REQUEST_MIN, 2], [self::REQUEST_MAX, 50]],
      'pseudo' => [self::REQUEST_REQUIRED, self::REQUEST_UNIQUE, [self::REQUEST_MIN, 3], [self::REQUEST_MAX, 50]],
      'email' => [self::REQUEST_EMAIL, self::REQUEST_REQUIRED, self::REQUEST_UNIQUE, [self::REQUEST_MIN, 10], [self::REQUEST_MAX, 100]],
      'password' => [self::REQUEST_REQUIRED, [self::REQUEST_MIN, 3], [self::REQUEST_MAX, 100]],
      'confirm_password' => [self::REQUEST_REQUIRED, [self::REQUEST_MATCHING, "password"]]
    ];
  }


  /**
   * Insert a user in a the db waiting for validation
   *
   * @return boolean
   */
  public function registerUser() : bool
  {
      $link = hash('md5',uniqid(true));
      $request = $this->connect()->prepare('INSERT INTO users (f_name,l_name,email,password,pseudo,role,send_link,token)
      VALUES (:prenom,:nom,:email,:password,:pseudo,:role,:link,:token)');
      $request->execute([
        "prenom" => $this->f_name,
        "nom" => $this->l_name,
        "email" => $this->email,
        "password" => password_hash($this->password,PASSWORD_DEFAULT),
        "pseudo" => $this->pseudo,
        "role" => 0,
        "link" => date('Y-m-d H:i:s',time()),
        "token" => $link
      ]);
      $request->closeCursor();
      $request = $this->connect()->prepare('SELECT * from users where pseudo = ?');
      $request->execute([$this->pseudo]);
      if ($resp = $request->fetch()) {
        $this->link = "http://blog.test/validation-mail?id={$resp['id']}&token={$link}";
        return true;
      }
      return false;

  }


  /**
   * retrieve an user by his id
   *
   * @param array $datas
   * @return boolean
   */
  public function confirmMail(array $datas) : bool
  {
      $request = $this->connect()->prepare('SELECT * from users where id = ?');
      $request->execute([$datas['id']]);
      $response = $request->fetch() ?? null;
      if (!$response) {
        return false;
      }
      $this->user = $response;
      return true;

  }


  /**
   * Update a user account to validate it
   *
   * @return boolean
   */
  public function updateUser() :bool {
    $request = $this->connect()->prepare('UPDATE users set send_link = null,token = null,confirmation_date = NOW() where id = ?');
    if($request->execute([$this->user['id']])){
      return true;
    }
    return false;

  }


}
