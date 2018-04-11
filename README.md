# Physdoor
## Development environment setup
* Create secret configuration file and set LDAP serviceuser and password as well as the password for the [physdoor-relay](https://github.com/physcip/physdoor-relay):
```
cp server/conf_secret.inc.php.example server/conf_secret.inc.php
```
* Generate empty AccessLog database: Execute `sqlite3 door.db < door.sql` in `server/db` directory
* Unfortunately, using the PHP's builtin webserver is not possible, since it runs in only one single-threaded process and therefore doesn't support long polling. Therefore, you will need to setup Apache / nginx / Caddy or another webserver. For simplicity, these are the instructions for [Caddy](https://caddyserver.com/).
* Install Caddy, `php-fpm` and `php-sqlite`
* Edit `/etc/php/php.ini` and uncomment / add the following two lines:
```
extension=sqlite3
extension=ldap
```
* Increase the value for `pm.max_children` in `/etc/php/php-fpm.d/www.conf` (or similar). By long-polling, physdoor potentially spawns a lot of PHP child proccesses, but they don't do much other than sleep and don't consume much CPU time. `30` is a good value.
* Start `php-fpm`, e.g. using `systemctl start php-fpm`
* Make sure the `server/db` directory is writable by the PHP process, e.g. execute `sudo chown -R http server/db`
* Start Caddy by running `caddy` in this directory. A Caddyfile is provided. You may need to execute caddy as root to bind to port 80.
* Open [localhost](http://localhost) in web browser

## Announcements
Physdoor can also be used to display announcements to users (e.g. scheduled maintenances, breaking changes). Just create a new announcement from the template:
```
cp client/announcements.md.example client/announcements.md
```
Then edit `client/announcements.md` to add your announcement or leave the file empty (except for comments) if you want the announcement box to disappear. Announcements are refreshed every 5 minutes (see `UPDATE_ANNOUNCEMENTS_MINUTES` in `client/common.js`).

## Attribution
* `Sha3.php` was written by [Bruno Bierbaumer](https://github.com/0xbb), it is a [pure PHP implementation of SHA3 (Keccak)](https://github.com/0xbb/php-sha3)
