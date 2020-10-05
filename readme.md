# VeVa Backend

This project uses Laravel 5.7, running Laradock to handle the docker environment.

## Getting Setup

When you first pull this project run `git submodule update --init --recursive` and then on run `git submodule update --remote --recursive` to update the submodules.

### Laradock
	
[Read here for documentation](https://laradock.io/documentation)

#### Mac
In the root run: `cp ./veva-laradock/env-example-mac ./veva-laradock/.env`

#### Windows (Not bash)
_If using bash, use same command as mac but use 'env-example-windows' file_
In the root run: `copy veva-laradock/env-example-windows veva-laradock/.env`

Then to run Laradock: `make up`. This will boot up the workspace environment as well as Redis, MySQL and the Nginx and PHP-FPM services.


### Setting up Laravel

[Read here for documentation](https://laravel.com/docs/5.7/installation)

Install the composer dependencies by running `composer install`

Copy `.env.example` to `.env` and then run `php artisan key:generate`. Configure the `APP_URL` key in your `.env` to match the hosts file entry for this project.

### Laravel Nova

#### Setup a user
Use the Laradock Workspace by running `make workspace` and then inside of that bash terminal, run `php artisan nova:user` and interactively create yourself an admin user.

### xDebug

The `php-fpm` and `workspace` containers have xDebug installed. It is reccomended to configure an xDebug client locally, this will enable you to add breakpoints to your PHP code for easier debugging.

#### Some useful links:
https://www.sitepoint.com/debugging-xdebug-sublime-text-3/
https://www.codewall.co.uk/debug-php-in-vscode-with-xdebug/
https://chrome.google.com/webstore/detail/xdebug-helper/eadndfjplgieldjbigjakmdgkmoaaaoc?hl=en

### GraphQL

It's reccomended to use an appropriate GraphQL request client, for example: https://support.insomnia.rest/article/61-graphql
