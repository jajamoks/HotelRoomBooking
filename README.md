# HotelRoomBooking
a RESTFul hotel room booking manager using laravel and vue.js

This application allows you to;
1. Create | UPDATE | Edit  Hotel 
2. Create | UPDATE | Edit | DELETE Rooms
3. Create | UPDATE | Edit | DELETE Room-Bookings
4. Create | UPDATE | Edit | DELETE Room-Pricing
5. Create | UPDATE | Edit | DELETE Room-Types

Hotel bookings are displayed in a full-calender viewing



PostMan Collection for RESTFul API
https://www.getpostman.com/collections/b7669e010a8c17904ae3



Note:
API endpoint access is protected by Laravel PassPort hence user must be registered and login to generate a token which would be use to make API request subsequently.


##How to setup:

* Create a database locally
* Import the database file (test_db) located inside /database/test_db
* Open .env inside your project root and fill the database information. 
* Open the console and cd your project root directory
* Run composer install or php composer.phar install
* Run php artisan key:generate
* Run vendor/bin/phpunit
* Run php artisan serve
* 
#####You can now access your project at localhost:8000 :)
