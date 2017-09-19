<?php
require "bootstrap.php";

//$result = $tablet->table("users")
//                    ->select(["name"])
//                    ->where(["name = 'test'"])
//                    ->get(2,4);
$result = $tablet->table("users")
                    ->update(["name"=>"Test"])
                    ->where(["name = 'hello'"])
                    ->go();

var_dump ($result);
