Carbon-Forum
============

A high performance open source forum software. 

Demo/Official Website
------------

* [Project's official website: www.94cb.com](http://www.94cb.com/)

Requirements
------------
* PHP version 5.3.6 or higher.
* The [__PDO_MYSQL__](http://php.net/manual/en/ref.pdo-mysql.php) PHP Package.
* MySQL version 5.0 or higher.
* The [__mod_rewrite__](http://httpd.apache.org/docs/2.2/mod/mod_rewrite.html) Apache module.

Install
------------

1. Open __config.php__

 ```php
 define('DBHost', '127.0.0.1');
 define('DBName', 'carbon');
 define('DBUser', 'root');
 define('DBPassword', '');
 ```
 
 Replace the constant value with the value that you actually use. 
2. Import the __database.sql__

3. Open the Forum, and register. The first registered users will become administrators. 

License
------------

[Apache License, Version 2.0](http://www.apache.org/licenses/LICENSE-2.0)