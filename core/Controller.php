<?php
namespace core;

abstract class Controller
{
    private $loader;
    protected $twig;

    public function __construct()
    {
        $this->loader = new \Twig\Loader\FilesystemLoader(ROOT.'\views');
        $this->twig = new \Twig\Environment($this->loader, [
        'cache' => false,
        ]);
    }
    
}
