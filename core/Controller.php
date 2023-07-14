<?php

namespace core;

use core\init;
use Twig\Extension\Session;
use Twig\Extra\Intl\IntlExtension;


abstract class Controller
{

    /**
     * Loader uses y twig to locate templates
     *
     * @var [type]
     */
    private $loader;

    /**
     * Environment uses by twig to store its configuration
     *
     * @var [type]
     */
    protected $twig;

    /**
     * SMTP username
     *
     * @var string
     */
    protected string $mail_id;

    /**
     * SMTP server to send through
     *
     * @var string
     */
    protected string $host;

    /**
     * SMTP password
     *
     * @var string
     */
    protected string $password;

    /**
     * Mail sender email
     *
     * @var string
     */
    protected string $from;

    /**
     * The email adress which will receive the moderation request
     *
     * @var string
     */
    protected string $admin;

    /**
     * List of the Authorised extension for the images
     *
     * @var array
     */
    protected array $type_auth;

    /**
     * Max size authorised for the images
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
        $init = new init();
        $init = $init->init();
        !defined('BASE') ? define('BASE', $init['base_url']) : null;
        !defined('IMAGE') ? define('IMAGE', preg_replace("#/#",DIRECTORY_SEPARATOR,ROOT.$init['image']['location'])) : null;
        !defined('ADMIN') ? define('ADMIN', 1) : null;
        !defined('REF') ? define('REF', 'referer') : null;
        $this->mail_id = $init["mail"]['user_name'];
        $this->password = $init["mail"]["password"];
        $this->host = $init["mail"]['host'];
        $this->from = $init["mail"]['from'];
        $this->admin = $init["mail"]['admin'];
        $this->type_auth = $init["image"]["type_auth"];
        $this->size = $init["image"]["size"];

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
            else {
                header("Location:".BASE.$location);
            }
        }
        die();

    }

}
