# Job Application Tracker

A web application for tracking job applications, companies, vacancies, and communications built with Yii2 framework.

## About

This application helps manage the job search process by tracking:
- **Companies** - potential employers with contact information and status
- **Vacancies** - job openings at companies with interview dates
- **Persons** - contact people at companies
- **Interactions** - communication history and interview results

## Requirements

- PHP >= 7.4
- MySQL/MariaDB
- Composer

## Installation

### 1. Install Dependencies

```bash
composer install
```

### 2. Configure Database

Create a database and configure connection:

```bash
# Create config/db-local.php
cp config/db.php config/db-local.php
```

Edit `config/db-local.php` with your database credentials:

```php
return [
    'class' => 'yii\db\Connection',
    'dsn' => 'mysql:host=localhost;dbname=your_database_name',
    'username' => 'your_username',
    'password' => 'your_password',
    'charset' => 'utf8mb4',
];
```

### 3. Run Migrations

```bash
./yii migrate
```

### 4. Configure Web Server

Point your web server document root to the `web/` directory.

For development, you can use PHP built-in server:

```bash
php -S localhost:8080 -t web/
```

Then access the application at `http://localhost:8080`

## Default Login

- Username: `admin`
- Password: `admin`

*Change these credentials after first login*

## Development

### Running Tests

```bash
vendor/bin/codecept run
```

### Creating Migrations

```bash
./yii migrate/create migration_name
```

## License

BSD-3-Clause