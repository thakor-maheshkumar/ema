sudo chmod -R 777 /var/www/html/backup

if [ -f /var/www/html/ema-laravel/.env ]; then
   sudo mv /var/www/html/ema-laravel/.env /var/www/html/backup/
fi