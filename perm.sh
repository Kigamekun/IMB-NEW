# Reset permissions
sudo chown -R www-data:www-data /var/www/html/simpol.id
sudo chmod -R 755 /var/www/html/simpol.id
sudo find /var/www/html/simpol.id -type f -exec chmod 644 {} \;

# Clear Laravel cache
cd /var/www/html/simpol.id/IMB
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Restart services
sudo systemctl restart nginx
sudo systemctl restart php8.2-fpm

# Check Nginx config
sudo nginx -t
