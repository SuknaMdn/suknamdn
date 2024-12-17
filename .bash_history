mysql -h db -u root -p
php artisan migrate --seed
mysql -h db -u root -pphp -d memory_limit=1024M artisan migrate --seed
php -d memory_limit=1024M artisan migrate --seed
docker-compose down
exit
exit
