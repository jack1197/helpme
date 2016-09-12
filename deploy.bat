cd D:\home\site
rm .env
cp .env.deploy .env
php artisan key:generate
php artisan config:cache