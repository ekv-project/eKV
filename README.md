-----------------------------------------
# THIS PROJECT IS STILL UNDER DEVELOPMENT
-----------------------------------------

# e-KV
Student Information Management System for Malaysian Vocational Colleges.


## Requirements
1. Access to terminal / SSH
2. Web server (Apache2, NGINX)
2. MySQL / MongoDB
3. Composer
4. NodeJS (for NPM)
5. PHP Extensions (enabled in php.ini)
   1. ext-dom
   2. ext-zip
   3. ext-gd
   4. ext-mysql
   4. etc
6. Appropriate access to web folder.

## Installation
1. Download the source code of this system.
2. Setup your web server.
3. Configure `.env` file for database.
4. Run `composer install`, `npm install`, `npm run prod`, `composer update` command on your shell.
5. Run `php artisan key:generate` command to generate the application key.
6. Run `php artisan install {password} {fullname=admin} {email=admin@site.local}` to install this system. For explanation,
   run `php artisan help install`.
7. System installation is completed!

## License

This system/project is licensed under [GNU GPLv3](COPYING). Each contributions to this system will
be licensed under the same terms. Contributions are listed in [CREDITS](CREDITS) and eKV website in the future.
