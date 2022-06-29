<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400"></a></p>

<p align="center">
<a href="https://travis-ci.org/laravel/framework"><img src="https://travis-ci.org/laravel/framework.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

> ### Example Laravel Api codebase containing (CRUD, jwt-auth, advanced patterns and more) [](https://github.com/himanshuvishwakarma-kiwi/laravel-api) spec and API.

# Getting started
## Installation

Please check the official laravel installation guide for server requirements before you start. [Official Documentation](https://laravel.com/docs/8.x/installation)

Clone the repository

    git clone https://github.com/himanshuvishwakarma-kiwi/laravel-api.git

Switch to the repo folder

    cd laravel-api

Install all the dependencies using composer

    composer install or composer update

Copy the example env file and make the required configuration changes in the .env file

    cp .env.example .env

Generate a new application key

    php artisan key:generate

Generate a new JWT authentication secret key

    php artisan jwt:secret 

Run the database migrations (**Set the database connection in .env before migrating**)

    php artisan migrate

## Docker

To install with [Docker](https://www.digitalocean.com/community/tutorials/how-to-install-and-use-docker-on-ubuntu-18-04), run following steps

Switch to the repo folder

    cd laravel-api

Run the docker build
    
    sudo docker-compose up --build

The api can be accessed at [http://localhost:8085/api](http://localhost:8085/api).

## Environment variables

- `.env` - Environment variables can be set in this file

***Note*** : You can quickly set the database information and Mail information in env file and have the application fully working.

# Testing API

The api can now be accessed at

    http://localhost:8085/api

Request headers

|   **Key**         | **Value**         |
|------------------	|------------------	|
| Content-Type     	| application/json 	|
| Authorization    	| Bearer {Token}    |

# Authentication
 
This applications uses JSON Web Token (JWT) to handle authentication. The token is passed with each request using the `Authorization` header with `Bearer Token` scheme. The JWT authentication middleware handles the validation and authentication of the token.

--------------------------

you can access api documentation url [http://localhost:8085/api/documentation](http://localhost:8085/api/documentation).

