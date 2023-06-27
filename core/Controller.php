<?php

namespace core;


use Twig\Extra\Intl\IntlExtension;

abstract class Controller
{
    private $loader;
    protected $twig;
    protected $mail_id;
    protected $host;
    protected $password;

    public function __construct()
    {
        $this->loader = new \Twig\Loader\FilesystemLoader(ROOT . '\views');
        $this->twig = new \Twig\Environment($this->loader, [
            'cache' => false,
        ]);
        $this->twig->addExtension(new IntlExtension());
        $this->twig->addGlobal('session', $_SESSION);
        if (file_exists(PARAMS)) {
            $datas = json_decode(file_get_contents(PARAMS));
            define('BASE', $datas->base_url);
            $this->mail_id = $datas->mail->user_name;
            $this->password = $datas->mail->password;
            $this->host = $datas->mail->host;
        }
    }

    public function redirect(string $location ="")
    {
        if (!$location) {
            header("Location: " . BASE);
        }
        header("Location: " . BASE . $location);
        die();
    }
}
