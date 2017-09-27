# Fresh Installation

php -r "file_exists('.env') || copy('.env.example', '.env');"

composer update

php artisan key:generate

php artisan migrate

php artisan db:seed --class=UserSeeder
