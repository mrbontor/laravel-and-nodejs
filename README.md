# laravel-and-nodejs


about app:

database : mongodb

framework : laravel, expressJS

language : Javascript, PHP 7+

tempalte : adminLTE

sample of database : [services medigo](https://github.com/mrbontor/laravel-and-nodejs/services/medigo_db_sample/)

to use database, simply use this command

- mongorestore -d <database_name> <directory_backup>


for ducomentations of services, you can import file [services/medigo.postman_collection.json/](https://github.com/mrbontor/laravel-and-nodejs/services/medigo.postman_collection.json) on [POSTMAND](https://learning.postman.com/docs/postman/collections/working-with-openAPI/)


### INSTALATION


#### FRONTEND Using laravel

Install Dependencies

- composer install
- npm install

Resolve Environment

- create .env file (copy from .env.example)
- php artisan key:generate

Finishing
- composer dump-autoload

Run Service
- php artisan serve



#### BACKEND USing NODEJS

Install Dependencies

- npm install

Run Service

- node index.js

to monitor log,

- tail -f var/log/logServices


**fully ajax.**
sample image:

![image1](https://github.com/mrbontor/laravel-and-nodejs/1.jpg)

![image2](https://github.com/mrbontor/laravel-and-nodejs/2.jpg)

![image3](https://github.com/mrbontor/laravel-and-nodejs/3.jpg)

![image4](https://github.com/mrbontor/laravel-and-nodejs/4.jpg)
