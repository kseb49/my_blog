<?php
namespace core;

use core\Db;
use PDO;
use PDOException;

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
        $this->initialize = Db::requestDb();
    }

    protected function connect():PDO {
            return $this->connection = $this->initialize->connect();
    }
    
}
