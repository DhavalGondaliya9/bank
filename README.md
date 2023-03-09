# Bank Reconciliation

### Requirements

- PHP 8.1
- Other [Laravel requirements](https://laravel.com/docs/10.x/deployment#server-requirements)

### Installation

- Clone the repo:
`git clone [REPO_URL] [DIRECTORY_NAME]`

- Create `.env` file from the example file:
`php -r "file_exists('.env') || copy('.env.example', '.env');"`

- Install the dependencies: `composer install`

- Generate Key: `php artisan key:generate`

- DB migrate: `php artisan migrate`
