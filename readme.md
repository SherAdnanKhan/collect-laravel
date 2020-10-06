# VeVa Backend

This project uses Laravel 5.7, running Laradock to handle the docker environment.

### Laradock
	
[Read here for Laradock documentation](https://laradock.io/documentation)

To setup the Laradock for this project, in the root directory run: `chmod +x setup.sh && make setup`

Then to boot up the containers, run: `make up`

Checkout the `Makefile` for more commands.

### Setting up Laravel

[Read here for documentation](https://laravel.com/docs/5.7/installation)

Install the composer dependencies by running `composer install`

Copy `.env.example` to `.env` and then run `php artisan key:generate`. Configure the `APP_URL` key in your `.env` to match the hosts file entry for this project.

### Database Migrations & Seeding
Use the Laradock Workspace by running `make workspace` and then you can run `php artisan migrate` which will run the database migrations to create the tables. Once you've done that you should then seed the database with any data we need to be in the database, to do so run `php artisan db:seed`.

See the [Laravel documentation](https://laravel.com/docs/5.7) for more information on Migrations and Seeding 

### Laravel Nova
#### Setup a user
Use the Laradock Workspace by running `make workspace` and then inside of that bash terminal, run `php artisan nova:user` and interactively create yourself an admin user. You will then be able to login to the Nova admin panel at http://veva.local/admin (assuming veva.local is the domain you use for local development)

### xDebug (Optional)

The `php-fpm` and `workspace` containers have xDebug installed. It is reccomended to configure an xDebug client locally, this will enable you to add breakpoints to your PHP code for easier debugging.

#### Some useful links:
https://www.sitepoint.com/debugging-xdebug-sublime-text-3/
https://www.codewall.co.uk/debug-php-in-vscode-with-xdebug/
https://chrome.google.com/webstore/detail/xdebug-helper/eadndfjplgieldjbigjakmdgkmoaaaoc?hl=en

### GraphQL

It's reccomended to use an appropriate GraphQL request client, for example: https://support.insomnia.rest/article/61-graphql
