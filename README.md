Carbon-Forum
============

A high performance open source forum software. 

Demo
------------

http://www.94cb.com/

Requirements
------------
* PHP version 5.3.6 or higher.
* The [PDO_MYSQL](http://php.net/manual/en/ref.pdo-mysql.php) PHP Package.
* MySQL version 5.0 or higher.
* The [mod_rewrite](http://httpd.apache.org/docs/2.2/mod/mod_rewrite.html) Apache module.

Install
------------

1. Open config.php
```php
define('DBHost', '127.0.0.1');
define('DBName', 'carbon');
define('DBUser', 'root');
define('DBPassword', '');
```
Replace the constant value with the value that you actually use. 
2. Import the database.sql
3. Open the Forum, and register. 
The first registered users will become administrators. 