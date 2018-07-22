

# Orcons-db 
Orcons-db is a query builder for ADODB library. It is aimed at making database interactions easier with less codes.
## Installation
 	 >  composer install orcons-db 
	 
## Configuration
	orcons-db requires a configuration array. A typical configuration looks like

```php
	
	 $config = [
	    	'driver' 	=> 'mysqli',

		'server' 	=> "localhost",

		'username' 	=> "root",

		'password' 	=> "",

		'port' 		=> "3306",

		'database' 	=> "omvc",

		'debug' 	=> true

	];
```
## Usage 
It all starts with an instance of  Tablet class which requires a configuration array variable.  
```php  
$db = new  Orcons\Layers\Tablet( $config ) 
```
Now we are ready to interact with our database. 

## Methods 

|Name  |Params (Type) |Description | Example |
|------- |--------- |------ |------|
|<code> table </code> |  <code> table  </code>(string) | Tells orcons-db the database table to interact with. | table('users')  |
| <code>select</code> |columns (array) | Performs a select query. | <code> select(['name','age']) </code>|
| <code> update </code> |args (array) | Performs an <code> UPDATE </code> statement . | <code> update(['name'=>'Andrew','age'=>10]) </code>|
|<code> where </code> | conditions (array)| Adds an <code> where </code> clause to sql statement | <code> where(["name = 'Chamamme'])</code>|
|<code> orWhere </code> | conditions (array)| Adds an <code> OR </code> clause to sql statement | <code> orWhere([age = 25])</code>|
|<code> whereIn </code> | column (string) , conditions (array)| Adds an <code> WHERE IN </code> clause to sql statement | <code> whereIn('age',[ 25 , 6 , 8 ])</code>|
|<code> whereNotIn </code> | column (string) , conditions (array)| Adds an <code> WHERE NOT IN </code> clause to sql statement | <code> whereNotIn('age',[ 25 , 6 , 8 ]) </code>|
|<code> whereBetween </code> | column (string) , conditions (array)| Adds an <code> BETWEEN </code> clause to sql statement | <code> whereBetween('age',[ 18 ,19 ]) </code>|
|<code> orWhereIn </code> | conditions (array)| Adds an <code> OR IN </code> clause to sql statement | <code> orWhereIn(['name'=>'Andrew','age'=>25]) </code>|
|<code> get </code> | limit (int), offset (int)| Executes the sql statement  | <code>  get()</code> or <code> get(0,10) </code>|
|<code> run </code> |  | Executes the sql statement  | <code>  run() </code> |
|<code> toSql </code> |  | returns the final sql statment  | <code>  toSql() </code> |
### Sample
 ```php 
 
 use Orcons\Layers\Tablet;
 
$config = [
	    'driver' 	=> "mysqli",
		'server' 	=> "localhost",
		'username' 	=> "root",
		'password' 	=> "",
		'port' 		=> "3306",
		'database' 	=> "omvc",
		'debug' 	=> true
	];
	
	$db = new Tablet( $config ) 
	
	#select query
	$db->table('users')
		->select(['name','gender','age'])
		->get()
		
	#update statement
	$db->table('users')
		  ->update(['name'=>'Chamamme'])
		  ->where(["id = 5","gender ='male'"])
		  ->go()
		  
```
	
