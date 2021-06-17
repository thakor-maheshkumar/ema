sudo chmod -R 777 /var/www/html/ema-laravel

if [ -f /var/www/html/backup/.env ]; then
   sudo mv /var/www/html/backup/.env /var/www/html/ema-laravel/
fi

#sudo php artisan passport:install
cd /var/www/html/ema-laravel/
composer install
sudo php artisan migrate
sudo php artisan view:clear
sudo php artisan cache:clear
#sudo php artisan db:seed
