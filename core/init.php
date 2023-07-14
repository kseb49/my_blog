<?php

namespace core;

final class Init
{

    /**
     * Parameters needed to run the application
     *
     * @return array
     */
    public function init() :array
    {
        return [
        "db" =>
            [
             "prefix" => "mysql",
             "host" => "127.0.0.1",
             "dbname" => "blog",
             "user" => "root",
             "password" => ""
            ],
        "mail" =>
            [
             "host" => "smtp.gmail.com",
             "user_name" => "kseb49@gmail.com",
             "password" => "oocqluyachbdhdqo",
             "port " => "",
             "from" => "kseb49@gmail.com",
             "admin" => "sseb01@hotmail.fr"
            ],
        "base_url" => "http://blog.test/",
        "image" =>
            [
             "type_auth" => ["jpeg", "png", "webp", "jpg"],
             "size" => "2000000",
             "location" => "/public/assets/img/"
            ]
        ];

    }


}
