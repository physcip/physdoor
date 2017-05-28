# Physdoor
## Setup development environment
* Add file `confg_secret.inc.php` and set serviceuser and password:
```php
<?php
	define('ldap_serviceuser', 'physdoor@physcip.uni-stuttgart.de');
	define('ldap_servicepassword', '');
?>
```
* Generate empty AccessLog database: Execute `sqlite3 door.db < door.sql` in `db` directory
* Start PHP webserver: `php -S localhost:80 -c php.ini`
* Start PHP websockets server: `php -c php_websocket.ini websocket.php`
* Open [localhost](http://localhost) in web browser
