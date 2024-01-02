# What is it?
Laravel and ReactJS based self-hosted link collection for your social media, with GDPR-friendly basic analytics.

# Work in progress, contributions are welcome!
This is not a deployment ready project. I do use it as my landing page, and for that matter it works, but it is still not user-friendly, for example all link administration is done through SQL.

## TODO
- [x] Write an informative dashboard 
- [ ] Write an actual link administration panel
- [ ] Write a log parser
- [ ] Write a nice version of this README
- [ ] Find a catchy name

# Prerequisites
PHP, PostgreSQL, webserver.

# Installation
After you installed PostgreSQL, and your favourite webserver with PHP, get necessary packets:
~~~
apt install composer npm php-xml php-curl php-pgsql git
~~~

Clone this repository and get the vendor files with composer and npm:
~~~
git clone https://github.com/FaultierSP/self-hosted-links
composer update
php artisan key:generate
npm install
~~~

Change the `.env.example` file name to `.env`, make necessary changes (SQL credentials, App-Name, domain names in `SESSION_DOMAIN` and `SANCTUM_STATEFUL_DOMAINS` and your texts) and build the app:
~~~
php artisan migrate
npm run build
~~~

Serve the `public` directory with your favourite server.

Create a new user with:
~~~
php artisan user:create -u UserName -e your@mail.com
~~~

Go to `yourdomainname.com/login`, login with your created credentials, you will be redirected to your new dashboard.

# Maintainance
To update all packets, in your project directory:
~~~
composer update
npm update
npm run build
~~~