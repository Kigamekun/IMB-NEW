#!/bin/bash

# Stop and remove Nginx completely
echo "Stopping and removing Nginx..."
sudo systemctl stop nginx
sudo systemctl disable nginx
sudo apt-get remove -y nginx nginx-common nginx-core
sudo apt-get --purge remove -y nginx nginx-common nginx-core
sudo apt-get autoremove -y
sudo apt-get autoclean

# Remove Nginx configuration files
sudo rm -rf /etc/nginx
sudo rm -rf /var/log/nginx
sudo rm -rf /var/www/html/default

# Install Apache and required modules
echo "Installing Apache..."
sudo apt update
sudo apt install -y apache2 libapache2-mod-php8.2

# Create Apache configuration file
echo "Creating Apache configuration..."
sudo tee /etc/apache2/sites-available/simpol.hastasejahtera.online.conf << 'EOL'

<VirtualHost *:80>
    ServerName domain-anda.com
    DocumentRoot /var/www/html/simpol.id

    # Konfigurasi untuk aplikasi utama pada root "/"
    <Directory /var/www/html/simpol.id>
        AllowOverride All
        Require all granted
    </Directory>

    # Konfigurasi khusus untuk aplikasi SIMPOL di subdirektori "/simpol"
    Alias /simpol /var/www/html/simpol.id/simpol
    <Directory /var/www/html/simpol.id/simpol>
        AllowOverride All
        Require all granted
    </Directory>

    # Konfigurasi untuk aplikasi Laravel 8 pada subdirektori "/imb"
    Alias /imb /var/www/html/simpol.id/IMB/public
    <Directory /var/www/html/simpol.id/IMB/public>
        AllowOverride All
        Require all granted
    </Directory>

    # Konfigurasi untuk menangani PHP 5.6 menggunakan PHP-FPM (untuk SIMPOL)
    <FilesMatch "\.php$">
        SetHandler "proxy:unix:/var/run/php/php5.6-fpm.sock|fcgi://localhost"
    </FilesMatch>

    # Konfigurasi untuk menangani PHP 7.4 menggunakan PHP-FPM (untuk Laravel)
    <Directory /var/www/html/simpol.id/IMB/public>
        <FilesMatch "\.php$">
            SetHandler "proxy:unix:/var/run/php/php8.2-fpm.sock|fcgi://localhost"
        </FilesMatch>
    </Directory>

    ErrorLog ${APACHE_LOG_DIR}/error.log
    CustomLog ${APACHE_LOG_DIR}/access.log combined
</VirtualHost>

EOL

# Enable site and required modules
echo "Enabling Apache modules and site..."
sudo a2ensite simpol.hastasejahtera.online.conf
sudo a2enmod ssl
sudo a2enmod rewrite

# Set correct permissions
echo "Setting permissions..."
sudo chown -R www-data:www-data /var/www/html/simpol.id
sudo chmod -R 777 /var/www/html/simpol.id

# Create Laravel .htaccess file
echo "Creating Laravel .htaccess..."
sudo tee /var/www/html/simpol.id/IMB/public/.htaccess << 'EOL'
<IfModule mod_rewrite.c>
    <IfModule mod_negotiation.c>
        Options -MultiViews -Indexes
    </IfModule>

    RewriteEngine On
    RewriteBase /imb/

    # Handle Authorization Header
    RewriteCond %{HTTP:Authorization} .
    RewriteRule .* - [E=HTTP_AUTHORIZATION:%{HTTP:Authorization}]

    # Send Requests To Front Controller...
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteRule ^ index.php [L]
</IfModule>
EOL

# Test Apache configuration
echo "Testing Apache configuration..."
sudo apache2ctl configtest

# Restart Apache
echo "Starting Apache..."
sudo systemctl restart apache2
sudo systemctl enable apache2

# Open firewall ports if UFW is active
echo "Configuring firewall..."
sudo ufw allow 80
sudo ufw allow 443

# Verify Nginx is completely removed
echo "Verifying Nginx removal..."
dpkg -l | grep nginx

# Display status
echo "Apache configuration completed!"
echo "Checking Apache status..."
sudo systemctl status apache2

# Display logs for verification
echo "Checking logs..."
sudo tail -n 20 /var/log/apache2/error.log
