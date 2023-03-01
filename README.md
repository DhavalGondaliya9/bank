# Title

### Requirements

- PHP 8.1
- Other [Laravel requirements](https://laravel.com/docs/10.x/deployment#server-requirements)

### Installation

- Clone the repo:
`git clone [REPO_URL] [DIRECTORY_NAME]`

- Create `.env` file from the example file:
`php -r "file_exists('.env') || copy('.env.example', '.env');"`

- Setup .env variables (Especially SANCTUM_STATEFUL_DOMAINS should be set. The value should be the domain name without 'http://' and a trailing slash. And `LIST_OF_WEB_APP_URLS_AND_KEYS` should be updated in all the sites.)

- Install the dependencies: `composer install`

- Generate Key: `php artisan key:generate`

- DB migrate: `php artisan migrate`
