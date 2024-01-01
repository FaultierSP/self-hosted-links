# What is it?

Laravel and ReactJS based self-hosted link collection for your social media, with GDPR-friendly basic analytics.

# Prerequisites

PHP, PostgreSQL, webserver.

# Installation

Get necessary packets:
~~~
apt install composer npm php-xml php-curl php-pgsql git
~~~

Clone this repository and get the vendor files with composer and npm:
~~~
git clone https://github.com/FaultierSP/NAME
composer update
php artisan key:generate
npm install
~~~

Change the `.env.example` file name to `.env`, make necessary changes (SQL credentials, App-Name) and build the app:

~~~
php artisan migrate
npm run build
~~~

Serve the `public` directory with your favourite server.

# Maintainance

To update all packets, in your project directory:

~~~
composer update
npm update
npm run build
~~~