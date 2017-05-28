# Physdoor
## Setup development environment
* Add file `confg_secret.inc.php` and set LDAP serviceuser and password as well as the password for the physdoor-relay:
```php
<?php
	define('ldap_serviceuser', 'physdoor@physcip.uni-stuttgart.de');
	define('ldap_servicepassword', '');
	define('relay_password', '');
?>
```
* Generate empty AccessLog database: Execute `sqlite3 door.db < door.sql` in `db` directory
* Start PHP webserver: `php -S localhost:80 -c php.ini`
* Start PHP websockets server: `php -c php_websocket.ini websocket.php`
* Open [localhost](http://localhost) in web browser

## Attribution
* `Sha3.php` was written by [Bruno Bierbaumer](https://github.com/0xbb), it is a [pure PHP implementation of SHA3 (Keccak)](https://github.com/0xbb/php-sha3)
