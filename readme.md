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
sudo apt-get update
sudo apt-get install nginx
sudo ufw allow 'Nginx HTTP'

Install PHP:
sudo apt-get install php curl unzip php-pear php-fpm php-dev php-zip php-curl php-xmlrpc php-gd php-mysql php-mbstring php-xml git
sudo systemctl restart nginx

Install MariaDB:
sudo apt install mariadb-server
sudo mysql_secure_installation

// connect to mariadb and create database;
mysql -u username -p 
create database maxinero;

Install Composer:
sudo curl -s https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer

Install Maxinero:
cd /var/www/html/
git clone https://github.com/obissick/maxinero.git
chmod -R 777 storage/
php artisan key:generate
cp .env.example .env

// edit .env with database info
nano .env
DB_DATABASE=maxinero
DB_USERNAME=your_database_username
DB_PASSWORD=your_database_password
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
