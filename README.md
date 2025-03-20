<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## About This Starter Kit

This is a starter kit for Laravel, providing a robust foundation for your Laravel projects. It includes a variety of components to help you get started quickly.

## Components

The following components are included in this starter kit:

### Required Packages
- `php`: ^8.4
- `laravel/framework`: ^11.31
- `laravel/tinker`: ^2.10.1

### Development Packages
- `barryvdh/laravel-debugbar`: ^3.15
- `barryvdh/laravel-ide-helper`: ^3.5
- `fakerphp/faker`: ^1.23
- `larastan/larastan`: ^2.0
- `laravel/pail`: ^1.2.2
- `laravel/pint`: ^1.13
- `laravel/sail`: ^1.41
- `mockery/mockery`: ^1.6
- `nunomaduro/collision`: ^8.6
- `peckphp/peck`: ^0.1.2
- `pestphp/pest`: ^3.0
- `pestphp/pest-plugin-laravel`: ^3.0
- `pestphp/pest-plugin-type-coverage`: ^3.2
- `rector/rector`: ^1.2

## Installation

To install this repository from GitHub and set it up with Composer and Artisan, follow these steps:

1. Clone the repository:
    ```sh
    git clone git@github.com:Grazulex/start-kit-laravel.git
    cd start-kit-laravel
    ```

2. Install Composer dependencies:
    ```sh
    composer install
    ```

3. Copy the example environment file and generate an application key:
    ```sh
    cp .env.example .env
    php artisan key:generate
    ```

4. Create the database file:
    ```sh
    touch database/database.sqlite
    ```

5. Run the database migrations:
    ```sh
    php artisan migrate
    ```

You are now ready to start developing with this Laravel starter kit.

## Composer Scripts

The following scripts are available in the `composer.json` file:

- `dev`: Starts the development server and other services.
    ```sh
    composer dev
    ```
- `lint`: Runs Pint to lint the code.
    ```sh
    composer lint
    ```
- `spelling`: Runs Peck to check for spelling errors.
    ```sh
    composer spelling
    ```
- `review`: Runs Rector to review the code.
    ```sh
    composer review
    ```
- `test:lint`: Tests the code linting.
    ```sh
    composer test:lint
    ```
- `test:rector`: Tests the code with Rector in dry-run mode.
    ```sh
    composer test:rector
    ```
- `test:types`: Runs PHPStan to analyze the code.
    ```sh
    composer test:types
    ```
- `test:type-coverage`: Runs Pest to check type coverage.
    ```sh
    composer test:type-coverage
    ```
- `test:unit`: Runs unit tests with Pest.
    ```sh
    composer test:unit
    ```
- `clean`: Runs lint, spelling, and review scripts.
    ```sh
    composer clean
    ```
- `test`: Runs all test scripts.
    ```sh
    composer test
    ```

## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

- [Simple, fast routing engine](https://laravel.com/docs/routing).
- [Powerful dependency injection container](https://laravel.com/docs/container).
- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
- Database agnostic [schema migrations](https://laravel.com/docs/migrations).
- [Robust background job processing](https://laravel.com/docs/queues).
- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework.

You may also try the [Laravel Bootcamp](https://bootcamp.laravel.com), where you will be guided through building a modern Laravel application from scratch.

If you don't feel like reading, [Laracasts](https://laracasts.com) can help. Laracasts contains thousands of video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

## Laravel Sponsors

We would like to extend our thanks to the following sponsors for funding Laravel development. If you are interested in becoming a sponsor, please visit the [Laravel Partners program](https://partners.laravel.com).

### Premium Partners

- **[Vehikl](https://vehikl.com/)**
- **[Tighten Co.](https://tighten.co)**
- **[WebReinvent](https://webreinvent.com/)**
- **[Kirschbaum Development Group](https://kirschbaumdevelopment.com)**
- **[64 Robots](https://64robots.com)**
- **[Curotec](https://www.curotec.com/services/technologies/laravel/)**
- **[Cyber-Duck](https://cyber-duck.co.uk)**
- **[DevSquad](https://devsquad.com/hire-laravel-developers)**
- **[Jump24](https://jump24.co.uk)**
- **[Redberry](https://redberry.international/laravel/)**
- **[Active Logic](https://activelogic.com)**
- **[byte5](https://byte5.de)**
- **[OP.GG](https://op.gg)**

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
