# Tot Track

## Description
- Tot Track - A baby care journal and analytics webapp written in PHP.
- Track feedings and diaper changes.
- Get analytics from the data entered.

## Tech Stack
- Tech Stack: PHP, MySQL, HTML, CSS, JS, Bootstrap, Apache 
- WebApp Architecture: Postback
- Tool/Services Used:
    - Statcounter
    - Backblaze

## Features
- Authentication and Session management
- Login "Remember Me" feature to avoid the hassle of repetitive login
- Bootstrap theme
- Enter data via a quick entry form or as a recognized text format
- DB backup: Automated MySQL DB backup to B2
- DB backup: Cleanup script to delete files on B2 that are older than 30 days

## Notes
- Currently only supports managing data for one baby.
- Coming soon: Support for multiple babies.


## Dev Environment Setup

### 1) Install XAMPP Server OR WAMP Server
- XAMPP Server Download: https://www.apachefriends.org/download.html
- WAMP Server Download: https://wampserver.aviatechno.net/?lang=en

### 2) Setup the TotTrack webapp in XAMPP Server OR WAMP Server
- Clone or download it from the git repo to your Webserver location
```
git clone <repo> tot_track
```
For XAMPP:
```
C:\xampp\htdocs\tot_track\
```

For WAMP:
```
C:\wamp64\www\tot_track\
```
- Configure Your PHP Application

    - Update Configuration Files in config/:
    - Create a copy of app.ini.example as app.ini
    ```
    [site]
    SITE_TITLE      = Tot Track
    SITE_SHORT_NAME = Tot-Track
    HOME_PAGE       = index.php
    TIMEZONE        = America/Los_Angeles
    SYS_USER        = user1
    SYS_PASS        = xyz

    [database]
    DB_HOST     = localhost
    DB_NAME     = tot_track
    DB_USERNAME = dbuserxyz
    DB_PASSWORD = dbpasswordxyz
    ```

- Check File Permissions:
    Ensure that your PHP application files have the correct permissions (especially on Linux/macOS).

### 3) Apache Configuration for Virtual Hosts:
- On Windows:
    Open the Apache configuration file httpd-vhosts.conf located at C:\xampp\apache\conf\extra\httpd-vhosts.conf.

- On Linux/macOS:
    Edit the configuration file located at /opt/lampp/etc/extra/httpd-vhosts.conf.

- Add your virtual host configuration. Here’s an example configuration:
```apache
<VirtualHost *:80>
    DocumentRoot "C:/xampp/htdocs/tot_track"
    ServerName tottrack.local
    <Directory "C:/xampp/htdocs/tot_track">
        Options Indexes FollowSymLinks
        AllowOverride All
        Require all granted
    </Directory>
    ErrorLog "logs/tot_track_error.log"
    CustomLog "logs/tot_track_access.log" common
</VirtualHost>
```

- Make sure to replace C:/xampp/htdocs/tot_track with the path to your project directory and tottrack.local with your desired domain name.

- Edit the Main Apache Configuration File:
    - On Windows:
        - Open the Apache configuration file httpd.conf located at C:\xampp\apache\conf\httpd.conf.
        - Ensure the following line is uncommented (remove the # at the beginning if present):
    ```apache
        Include conf/extra/httpd-vhosts.conf
    ```

    - On Linux/macOS:
        - Open the Apache configuration file /opt/lampp/etc/httpd.conf.
        - Ensure the following line is uncommented:
    ```apache
        Include etc/extra/httpd-vhosts.conf
    ```

- Restart Apache:
    Windows: Go to the XAMPP Control Panel and click "Stop" and then "Start" for Apache.
    Linux/macOS: Restart Apache using:
    ```bash
    sudo /opt/lampp/lampp restart
    ```

### 4) Setup the Host file entry
- To make the virtual host work on your local machine, you need to map your custom domain to localhost in your hosts file.
- Edit the hosts File:
    - On Windows:
        - Open Notepad as an Administrator (right-click on Notepad and select "Run as administrator").
        - Open the file C:\Windows\System32\drivers\etc\hosts.
    - Add the following line:
    ```
    127.0.0.1 tottrack.local
    ```

    - On Linux/macOS:
        - Open the terminal and edit the hosts file with a text editor like nano:
    ```bash
    sudo nano /etc/hosts
    ```
    - Add the following line:
    ```
    127.0.0.1 tottrack.local
    ```

    - Save and Close the File:
        - Windows: Save the file and close Notepad.
        - Linux/macOS: Save the file and exit the editor (in nano, press Ctrl+X, then Y, and Enter).


### 5) Setup the TotTrack DB in MySQL
- Access phpMyAdmin:
    - Open a web browser and go to http://localhost/phpmyadmin.

- Create a Database:
    - Click on the "Databases" tab.
    - Enter a name for your database and click "Create."

- Import the Database (It is available in /db/tot_track.sql):
    - Select the database you just created.
    - Click on the "Import" tab.
    - Choose the SQL file and click "Go."


### 6) Verify Configuration
- Access Your Local Application:
    - Open a web browser and navigate to http://tottrack.local.
    - You should see your application if everything is set up correctly.

- Check for Errors:
    - If you encounter issues, check the Apache error log for clues.
    - Windows: Look in C:\xampp\apache\logs\error.log.
    - Linux/macOS: Look in /opt/lampp/logs/error_log. 


## Production Environment Setup

### 1) Install Apache, PHP and MySQL
```
sudo apt update
sudo apt install apache2 mysql-server php php-mysql libapache2-mod-php
sudo apt install php-xml php-mbstring php-curl php-zip
```

### 2) Configure Apache
- Create a Virtual Host Configuration:
- Create a new configuration file for your site in the Apache configuration directory:
```bash
sudo nano /etc/apache2/sites-available/tot_track.conf
```

- Add the following configuration, adjusting paths and settings as necessary:
```apache
<VirtualHost *:80>
    ServerAdmin webmaster@tottrack.xyz.com
    ServerName tottrack.xyz.com
    DocumentRoot /var/www/html/tot_track

    <Directory /var/www/html/tot_track>
        Options Indexes FollowSymLinks
        AllowOverride All
        Require all granted
    </Directory>

    ErrorLog ${APACHE_LOG_DIR}/error.log
    CustomLog ${APACHE_LOG_DIR}/access.log combined
</VirtualHost>
```

- Enable the Site and Rewrite Module:
```bash
sudo a2ensite tot_track.conf
sudo a2enmod rewrite
```

- Restart Apache:
```bash
sudo systemctl restart apache2
```

### 3) Download the TotTrack webapp to the Apache location
- Clone or download it from the git repo to your Webserver location
```
git clone <repo> tot_track
```
For Apache:
```
/var/www/html/tot_track
```

- Setup correct file permissions:
```bash
sudo chown -R www-data:www-data /var/www/html/tot_track
sudo chmod -R 755 /var/www/html/tot_track
```


### 4) Setup the TotTrack DB in MySQL
- Set Up the Database
```bash
sudo mysql_secure_installation
```
- Follow the prompts to set a root password and secure the installation.

- Create a Database and User:
- Log in to MySQL as root:
```bash
sudo mysql -u root -p
```

- Create a new database and user for your application:
```sql
CREATE DATABASE tot_track;
CREATE USER 'tot_track_user'@'localhost' IDENTIFIED BY 'mypassword';
GRANT ALL PRIVILEGES ON tot_track.* TO 'tot_track_user'@'localhost';
FLUSH PRIVILEGES;
EXIT;
```

- Import Your Database Schema (It is available in /db/tot_track.sql):
```bash
mysql -u tot_track_user -p tot_track < /var/www/html/tot_track/db/tot_track.sql
```

### 5) Secure Your Installation
- Set File Permissions:
Ensure that sensitive files are not writable by the web server:
```bash
sudo chmod 644 /var/www/html/tot_track/config
```

- Disable Directory Listing:
Ensure directory listing is disabled by setting Options -Indexes in your Apache configuration or .htaccess file.

- Setup SSL certificate:
Secure your application with SSL/TLS. You can use Let’s Encrypt for free SSL certificates.
Install Certbot:
```bash
sudo apt install certbot python3-certbot-apache
```

- Obtain and install a certificate:
```bash
sudo certbot --apache -d tottrack.xyz.com
```

### 6) Setup automated DB backup to Backblaze
- Setup the Backblaze CLI
```bash
curl -L https://f000.backblazeb2.com/file/backblazeb2cli/b2-linux-x64 -o b2
chmod +x b2
sudo mv b2 /usr/local/bin/
b2 --version
```

- Setup Crontab
```bash
crontab -e
```

- Configure crontab
```
# DB Backup: MySQL Backups to B2 bucket
5 0 * * * /opt/scripts/mysql_backup/b2_backup_tot_track.sh

# DB Backup: B2 bucket - Cleanup files older than 30 days
10 0 * * * /opt/scripts/mysql_backup/b2_cleanup_tot_track.sh
```