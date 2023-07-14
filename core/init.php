<?php

namespace core;

final class init
{
    public function init(){
        return [
        "db"  =>
            [
            "prefix"  =>  "mysql",
            "host"  =>  "127.0.0.1",
            "dbname" => "blog",
            "user"  =>  "root",
            "password" =>  ""
            ], 
        "mail " =>
        [
            "host"  =>  "smtp.gmail.com",
            "user_name"  =>  "kseb49@gmail.com",
            "password" =>  "oocqluyachbdhdqo",
            "port " =>  "",
            "from" =>  "kseb49@gmail.com",
            "admin" =>  "sseb01@hotmail.fr"
            ],
        "base_url"  =>  "http://blog.test/",
        "image"  =>
            [
            "type_auth"  =>  ["jpeg", "png", "webp", "jpg"],
            "size"  =>  "2000000",
            "location"  =>  "/public/assets/img/"
            ]
        ];
    }
}

