<?php
require "vendor/autoload.php";

use NoQuery\Builder;

$config = require "config.php";

$db = new Builder($config);

#select query
$das = $db->table('users')
   	->select(['name','email'])
   	->get();
   	var_dump($das);
#update statement
$db->table('users')
   	  ->update(['name'=>'Chamamme'])
   	  ->where(["id = 5","email = 'andrew@mail.com'"])
   	  ->go();

