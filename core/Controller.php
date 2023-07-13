<?php

namespace core;

use Twig\Extension\Session;
use Twig\Extra\Intl\IntlExtension;


abstract class Controller
{

    private $loader;

    protected $twig;

    protected string $mail_id;

    protected string $host;

    protected string $password;

    protected string $from;

    protected string $admin;

    /**
     * List of the Authorised extension for the images
     *
     * @var array
     */
    protected array $type_auth;

    /**
     * Max size authorised
     *
     * @var string
     */
    protected string $size;


    public function __construct()
    {
        $this->loader = new \Twig\Loader\FilesystemLoader(ROOT . '\views');
        $this->twig = new \Twig\Environment($this->loader, ['cache' => false,]);
        $this->twig->addExtension(new IntlExtension());
        $this->twig->addExtension(new Session());
        $this->twig->addGlobal('session', $_SESSION);
        if (file_exists(PARAMS) === true) {
            $datas = json_decode(file_get_contents(PARAMS));
            !defined('BASE') ? define('BASE', $datas->base_url) : null;
            !defined('IMAGE') ? define('IMAGE', preg_replace("#/#",DIRECTORY_SEPARATOR,ROOT.$datas->image->location)) : null;
            !defined('ADMIN') ? define('ADMIN', 1) : null;
            !defined('REF') ? define('REF', 'referer') : null;
            $this->mail_id = $datas->mail->user_name;
            $this->password = $datas->mail->password;
            $this->host = $datas->mail->host;
            $this->from = $datas->mail->from;
            $this->admin = $datas->mail->admin;
            $this->type_auth = $datas->image->type_auth;
            $this->size = $datas->image->size;
        }

    }


    /**
     * Send a header Location
     *
     * @param string $location
     * @return void
     */
    public function redirect(string $location="") :void
    {

        if (!$location) {
            header("Location:".BASE);
        }
        else {
            if (isset($_SERVER['HTTP_REFERER']) && $location === REF) {
                header("Location:".$_SERVER['HTTP_REFERER']);
            }
            else{
                header("Location:".BASE.$location);
            }
        }
        die();

    }

}
