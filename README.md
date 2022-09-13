
1.CREATE DATABASE coupon_project;

2.Open the .env file of the Laravel project and initialize the values for the following information based on the database.

        DB_CONNECTION=mysql
        DB_HOST=localhost
        DB_PORT=3306
        DB_DATABASE=coupon_project
        DB_USERNAME='username'
        DB_PASSWORD='password'
        
 3. Do migration and seed

        php artisan migrate:refresh --seed 
        
 4. To start project run

        php artisan serve
        
Project should be served on http://127.0.0.1:8000;

Credential for login is: 
    
        *email = admin@gmail.com
        *password = admin;

