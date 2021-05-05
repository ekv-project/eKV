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
2. Setup your web server (Apache/Nginx) to suit Laravel app.
3. Copy `.env.example` file and rename to `.env`. Configure the `.env` file for database connection.
4. Run `composer install`, `npm install`, `npm run prod`, `composer update` command on your shell to install necessary dependencies and compile the assests.
5. Upload the files to your web server.
6. Go to your `/install` to proceed the installation.
The script will add a new `admin` user, migrate the database and generate the application key.
7. System installation is completed!

## License

This system/project is licensed under [GNU GPLv3](COPYING). Each contributions to this system will
be licensed under the same terms. Contributions are listed in [CREDITS](CREDITS) and eKV website in the future.
