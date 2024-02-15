# coffeeShop
Steps to install application locally
Clone this repository

### Installing API

```
cd api
```

Replace your database credentials inside .env.example

```
composer install
```

```
php artisan serve
```

API will be accessible on http://localhost:8000

### Installing React APP

```
cd react
```

```
npm install
```

To run in development mode on port 8080
```
npm run start-dev
```
To run in production mode on port 3000

```
npm run start-prod
```


APP will be accessible on http://localhost:3000 or http://localhost:8080 

### Live DEMO

Live demo is available on http://165.22.14.41:8300
Login info: -e testas@test.com -p 12345678