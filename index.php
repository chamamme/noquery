<?php
require "vendor/autoload.php";

use Orcons\Layers\Tablet;

$config = require "config.php";

$db = new Tablet($config);

#select query
$db->table('users')
   	->select(['name','gender','age'])
   	->get()
   	
#update statement
$db->table('users')
   	  ->update(['name'=>'Chamamme'])
   	  ->where(["id = 5","gender ='male'"])
   	  ->go()
   	  
