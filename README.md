# bouncer
The Dim Sum Labs payment/door/membership system! Allows members to register their (cash) payment and receive a password.
This password can be used to open the door. Once you open the door, your device may access members' Wi-Fi.

## Structure
There are three entry points:
* `index.php` opens the door if the MAC is known, else redirects to `index.html` for PIN, which POSTs to `open.php` and redirects to `welcomeback.html`
* `submit.html` for submitting a new payment, POSTs to `submit.php` and finally redirects to `welcome.html`
* `admin.php` for payment verification; calls `verify.php` using XHR

### The system's workflow
1. Pay money in envelope, write name on it
1. Scan the QR code at the box
1. Fill in name/email and payment
1. Get email with PIN for door
1. Go to http://door/ to enter PIN, optionally swipe Octopus

## SQL
The SQLite DB schema create script can be found in `db.sql`.

## To-Do
* Cronjob for membership reminders DONE: `checkpaid.php`
* Need "Access denied" page DONE: `accessdenied.html`
* Cronjob for DB backups DONE: see `/etc/cron.daily/backupbouncer`
* Push-to-deploy DONE: `git push pi@door:/var/www/html`

## Prerequisites
* libapache2-mod-php5
* php5-sqlite

## Installation
1. `git clone` into `/var/www/html` (for example)
1. Install the prerequisites shown above
1. Create folder `/var/bouncer` and make it owned by `www-data`
1. Make sure the www folder has `AllowOverride All` enabled
1. Add `SetEnv SMTP_PASSWORD whatever` to `/etc/apache2/sites-enabled/000-default` (or whichever you're using)
1. Restart apache
