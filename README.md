# GorKa Team Sample Laravel API

---
## Deploy on production

### 1. Install php, Apache, Nginx (and setup for using it as reverse proxy), Supervisor and Composer on the host


### 2. Create a MySQL DB and a user for the project
**Login to MySQL as a root**
```bash
mysql -u root -p
```

**Execute the specified commands to create a DB and a user for it**
```mysql
CREATE DATABASE `laravel_sample_app` CHARACTER SET utf8 COLLATE utf8_general_ci;
CREATE USER 'laravel_sample_app'@'localhost' IDENTIFIED BY '<password>';
GRANT ALL PRIVILEGES ON `laravel_sample_app`.* TO 'laravel_sample_app'@'localhost';
FLUSH PRIVILEGES;
```


### 3. Clone the project from the repository and go to the project folder
```bash
cd /var/www/
git clone git@gitlab.com:krek95/laravel-sample-api.git
cd laravel-sample-api
```


### 4. Install Composer dependencies
```bash
composer install
```


### 5. Setting up Laravel environment
**Copying the env file from the example**
```bash
cp .env.example .env
```

**Generate app key**
```bash
php artisan key:generate
```

**Setting up common params**
```env
APP_URL=your-site.domain
APP_SUPPORT_EMAIL=noreply@your-site.domain
```

**Setting up data for working with the database**
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=laravel_sample_app
DB_USERNAME=laravel_sample_app
DB_PASSWORD=<password>
```

**Setting up email params**
```env
MAIL_MAILER=smtp
MAIL_HOST=<host>
MAIL_PORT=<port>
MAIL_USERNAME=<password>
MAIL_PASSWORD="<password>"
MAIL_ENCRYPTION=ssl
MAIL_FROM_ADDRESS="${APP_SUPPORT_EMAIL}"
MAIL_FROM_NAME="${APP_NAME}"
```


### 6. Execute DB migrations and actions
```bash
php artisan migrate:refresh && php artisan actions && php artisan optimize:clear
```


### 7. Setting up Supervisor
**Copying the configuration files for Supervisor**
```bash
cp ./etc/Supervisor/* /etc/supervisor/conf.d/
```

**Restart Supervisor to initialize new configs**
```bash
supervisorctl reread
supervisorctl update
supervisorctl status
```


### 8. Setting up cron
**Execute the specified command and, after selecting the editor, paste the contents of the ./etc/Crontab/laravel-sample-api-cron file into it**
```bash
crontab -u www-data -e
```


### 9. Setting up Nginx and Apache
**Copying configuration files**
```bash
cp ./etc/Apache/laravel-sample-api.conf /etc/apache2/sites-available
cp ./etc/Nginx/laravel-sample-api.conf /etc/nginx/sites-available
```

Customize if needed. For example, if several sites will be through the Nginx proxy, then we change the port from 8080 to some other one in the configs (for example, 8081). After that, add it to the /etc/apache2/ports.conf file.

**Create a links to configuration files**
```bash
ln -s /etc/apache2/sites-available/laravel-sample-api.conf /etc/apache2/sites-enabled/laravel-sample-api.conf
ln -s /etc/nginx/sites-available/laravel-sample-api.conf /etc/nginx/sites-enabled/laravel-sample-api.conf
```

**Restarting Nginx and Apache**
```bash
systemctl restart apache2
systemctl restart nginx
```


### 10. Finishing app setup
```bash
php artisan optimize:clear
php artisan gk:supervisor restart
chown -R www-data:www-data ./
```


### 11. Trying to access the API
```
http://sample.gorka.biz.ua/api
```
