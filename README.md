# e-KV
Sistem Pengurusan Maklumat Pelajar untuk Kolej Vokasional Malaysia

## Pemasangan
1.  Muat turun sumber kod sistem ini.
2.  Lakukan konfigurasi yang diperlukan bagi web server.
3.  Konfigurasi fail `.env` bagi database.
4.  Jalankan arahan `composer install` pada program shell.
5.  Jalankan arahan `npm install` dan `npm run prod` pada program shell.
6.  Jalankan arahan `composer update` pada program shell.
7.  Jalankan arahan `php artisan key:generate` pada program shell.
7.  Jalankan arahan `php artisan install {password} {fullname=admin} {email=admin@site.local}` untuk melakukan pemasangan sistem ini. Untuk penerangan, jalankan arahan `php artisan help install`.
8.  Pemasangan sistem selesai!

## Keperluan
1. Akses kepada terminal / SSH
2. Web server (Apache2, NGINX)
2. MySQL / MongoDB
3. Composer
4. NodeJS (untuk NPM)
5. PHP Extensions (perlu ubah di fail php.ini)
   1. ext-dom
   2. ext-zip
   3. ext-gd
   4. Dan sebagainya.

## Lesen
Sistem ini dilesenkan menggunakan [GNU GPLv3](https://www.gnu.org/licenses/gpl-3.0.txt). Sebarang sumbangan kepada sumber kod akan dilesenkan pada terma yang sama. Setiap sumbangan disenaraikan dalam fail [CREDITS](CREDITS).
