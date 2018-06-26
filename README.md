# Orcons-db 
Orcons-db is a query builder for ADODB library. It is aimed at making database interactions easier with less codes.
## Installation
 	 >  composer install orcons-db 
	 
## Configuration
	orcons-db requires a configuration array. A typical configuration looks like;
	> $config = [
	    	'driver' 	=> 'mysqli',

		'server' 	=> "localhost",

		'username' 	=> "root",

		'password' 	=> "",

		'port' 		=> "3306",

		'database' 	=> "omvc",

		'debug' 	=> true

	];

## Usage 
It all starts with an instance of  Tablet class which requires a configuration array variable.  
<code> $db = new Tablet( $config ) </code>
Now we are ready to interact with our database. 

## Methods 
|Name  |Params |Description | Example |
|------- |--------- |------ |------|
|<code> table </code> |  <code> table  </code>(string) | Tells orcons-db the database table to interact with. | table('users')  |
|select|columns (array) | Performs a select query. | <code> select(['name','age']) </code>|
|<code> where </code>
