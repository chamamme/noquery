<?php
use ODB\Tablet;

require "vendor/autoload.php";
$config = [
    'driver' => 'mysqli',
    'server' => "localhost",
    'username' => "root",
    'password' => "",
    'port' => "3306",
    'database' => "omvc",
    'debug' => true
];

$tablet = new Tablet($config);

$result = $tablet->table("users")
                    ->select(["name"])
                    ->where(["name = 'test'"])
                    ->get(2,4);

var_dump ($result);
