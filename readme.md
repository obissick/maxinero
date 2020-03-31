# Maxinero
UI for administering MariaDB MaxScale server.

# Install

## Requirements:
    Nginx
    MariaDB
    PHP >= 7.1.3 	
    OpenSSL PHP Extension 	
    PDO PHP Extension 	
    Mbstring PHP Extension 
    Tokenizer PHP Extension
    XML PHP Extension
    Ctype PHP Extension
    JSON PHP Extension
    BCMath PHP Extension

Ubuntu:
```
Install Nginx:
$ sudo apt-get update
$ sudo apt-get install nginx
$ sudo ufw allow 'Nginx HTTP'

Install PHP:
$ sudo apt-get install software-properties-common
$ sudo add-apt-repository ppa:ondrej/php
$ sudo apt-get update
$ sudo apt-get install curl unzip git php7.2 php7.2-mysql php7.2-fpm php7.2-mbstring php7.2-xml php7.2-curl
$ sudo systemctl restart nginx

Install MariaDB:
$ sudo apt-get install software-properties-common
$ sudo apt-key adv --recv-keys --keyserver hkp://keyserver.ubuntu.com:80 0xF1656F24C74CD1D8
$ sudo add-apt-repository "deb [arch=amd64,arm64,ppc64el] http://mariadb.mirror.liquidtelecom.com/repo/10.4/ubuntu $(lsb_release -cs) main"
$ sudo apt update
$ sudo apt -y install mariadb-server mariadb-client
$ sudo mysql_secure_installation

// connect to mariadb and create database;
$ mysql -u root -p 
GRANT ALL PRIVILEGES ON *.* TO 'maxuser'@'localhost' IDENTIFIED BY 'password';
MariaDB [(none)]> create database maxinero;

Install Composer:
$ sudo curl -s https://getcomposer.org/installer | php
$ sudo mv composer.phar /usr/local/bin/composer

Install Maxinero:
$ cd /var/www/html/
$ git clone https://github.com/obissick/maxinero.git
$ cd maxinero
$ chmod -R 777 storage/
$ cp .env.example .env

// edit .env with database info
nano .env
APP_NAME=maxinero
APP_ENV=local
APP_KEY=
APP_DEBUG=true
APP_URL=http://10.0.0.35

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=maxinero
DB_USERNAME=maxuser
DB_PASSWORD=password

// Install dependencies
$ composer update
$ composer dump-autoload
$ php artisan config:clear
$ php artisan key:generate
$ php artisan migrate
$ php artisan db:seed

// disable SSL
$ nano var/www/html/maxinero/app/Providers/AppServiceProvider.php
//comment out this line to use without SSL
 //URL::forceScheme('https');

Edit Nginx config:
$ nano /etc/nginx/conf.d/maxinero.conf

// /etc/nginx/conf.d/maxinero.conf file
server {
    listen 80;
    listen [::]:80;

    root /var/www/html/maxinero/public;
    index index.php index.html index.htm index.nginx-debian.html;

    server_name host/ip;

    location / {
    try_files $uri $uri/ /index.php$is_args$args;
    }

    location ~ \.php$ {
      try_files $uri /index.php =404;
      fastcgi_pass unix:/var/run/php/php7.2-fpm.sock;
      fastcgi_index index.php;
      fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
      include fastcgi_params;
    }

    location ~ /\.ht {
        deny all;
    }
}
sudo systemctl reload nginx
```
## Screenshots

### Dashboard
The dashboard shows the current connected users and info about the current maxscale threads.
![Alt text](/screenshots/dash.png?raw=true "Dashboard.")

### Maxscale Info
This page shows the current settings of the current selected maxscale server.
![Alt text](/screenshots/maxscaleinfo.png?raw=true "Maxscale info.")

### Maxscale Log
This shows the current maxscale log settings, user can also flush the log from here.
![Alt text](/screenshots/log.png?raw=true "Flush log.")

### Services & Monitors
This page shows the configures Maxscale services and monitors. Click on a services to view listeners and other information.
![Alt text](/screenshots/services_monitors.png?raw=true "Services & Monitors.")

### DB Servers
Here users can find the database servers that are currently configured with Maxscale. From here users can add, edit, or remove servers. The state of the server can also be controlled from here by clicking the state dropdown.
![Alt text](/screenshots/dbservers.png?raw=true "DB servers.")

### Maxscale Users
Shows maxscale unix and created users.
![Alt text](/screenshots/max_users.png?raw=true "Maxscale users.")

### Maxscale Servers
Allows users to switch between maxscale servers.
![Alt text](/screenshots/max_servers.png?raw=true "Maxscale servers.")
