-----------------------------------------
<!-- ALL-CONTRIBUTORS-BADGE:START - Do not remove or modify this section -->
[![All Contributors](https://img.shields.io/badge/all_contributors-4-orange.svg?style=flat-square)](#contributors-)
<!-- ALL-CONTRIBUTORS-BADGE:END -->
# THIS PROJECT IS STILL UNDER DEVELOPMENT
-----------------------------------------

# eKV
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
4. Run `composer update`, `npm update`, `composer install`, `npm install` to update/upgrade and install necessary dependencies.
5. Run `npm run prod` to and compile the assests.
6. Generate the application key with `php artisan key:generate` command. This key will be stored in `.env` file and be used for encryption.
7. (Optional) For production environment, set the `APP_DEBUG` value to `false` to prevent sensitive config to be exposed to the user.
8. Upload the files to your web server.
9. Go to your `/install` to proceed the installation.
The script will add a new `admin` user, migrate the database and generate the application key.
10. System installation is completed!

## License

This system/project is licensed under [GNU GPLv3](COPYING). Each contributions to this system will
be licensed under the same terms.
## Contributors ✨

Thanks goes to these wonderful people ([emoji key](https://allcontributors.org/docs/en/emoji-key)):

<!-- ALL-CONTRIBUTORS-LIST:START - Do not remove or modify this section -->
<!-- prettier-ignore-start -->
<!-- markdownlint-disable -->
<table>
  <tbody>
    <tr>
      <td align="center"><a href="https://www.hanisirfan.xyz"><img src="https://avatars.githubusercontent.com/u/66242389?v=4?s=100" width="100px;" alt="Muhammad Hanis Irfan Mohd Zaid"/><br /><sub><b>Muhammad Hanis Irfan Mohd Zaid</b></sub></a><br /><a href="#maintenance-hanisirfan" title="Maintenance">🚧</a> <a href="https://github.com/ekv-project/eKV/commits?author=hanisirfan" title="Code">💻</a> <a href="#ideas-hanisirfan" title="Ideas, Planning, & Feedback">🤔</a> <a href="#design-hanisirfan" title="Design">🎨</a> <a href="https://github.com/ekv-project/eKV/commits?author=hanisirfan" title="Documentation">📖</a></td>
      <td align="center"><a href="https://github.com/amihadi"><img src="https://avatars.githubusercontent.com/u/95136371?v=4?s=100" width="100px;" alt="amihadi"/><br /><sub><b>amihadi</b></sub></a><br /><a href="#maintenance-amihadi" title="Maintenance">🚧</a></td>
      <td align="center"><a href="https://github.com/GenericNominalUser"><img src="https://avatars.githubusercontent.com/u/67431218?v=4?s=100" width="100px;" alt="GenericNominalUser"/><br /><sub><b>GenericNominalUser</b></sub></a><br /><a href="https://github.com/ekv-project/eKV/commits?author=GenericNominalUser" title="Code">💻</a></td>
      <td align="center"><a href="http://farhanhelmy.xyz"><img src="https://avatars.githubusercontent.com/u/59960562?v=4?s=100" width="100px;" alt="Farhan Helmy"/><br /><sub><b>Farhan Helmy</b></sub></a><br /><a href="https://github.com/ekv-project/eKV/commits?author=farhan-helmy" title="Code">💻</a></td>
    </tr>
  </tbody>
  <tfoot>
    
  </tfoot>
</table>

<!-- markdownlint-restore -->
<!-- prettier-ignore-end -->

<!-- ALL-CONTRIBUTORS-LIST:END -->

This project follows the [all-contributors](https://github.com/all-contributors/all-contributors) specification. Contributions of any kind welcome!
