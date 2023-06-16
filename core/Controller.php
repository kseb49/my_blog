<?php
namespace core;

use core\Db;
use PDO;
use PDOException;
use Twig\Extra\Intl\IntlExtension;

abstract class Controller
{
    private $loader;
    protected $twig;
    protected Db $initialize;
    protected PDO $connection;

    public function __construct()
    {
        $this->loader = new \Twig\Loader\FilesystemLoader(ROOT.'\views');
        $this->twig = new \Twig\Environment($this->loader, [
        'cache' => false,
        ]);
        $this->twig->addExtension(new IntlExtension());
        $this->initialize = Db::requestDb();
    }

    protected function connect():PDO {
            return $this->connection = $this->initialize->connect();
    }
    
}
