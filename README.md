# Task - SaaS - Backend

Backend for the given task of SaaS Inventory.

## Requirements

- PHP: v8.3.*
- Laravel: v11.*
- MariaDB: v10.11.*
- or MySQL: v10.11.*
- or SQLite: v3.4*.* / v3.3*.*

## Installation

1. Clone the the repository from **github** or **download zip**

```bash
git clone git@github.com:saddamhshovon/ictask-saas-backend.git
```

2. Enter into the directory

```bash
cd ictask-saas-backend
```

3. Copy the **.env.example** file as **.env**

```bash
cp .env.example .env
```

4. Set the database credentials in .env file

```env
DB_CONNECTION=mariadb
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=saas_db
DB_USERNAME=root
DB_PASSWORD=
```

5. Run composer install

```bash
composer install
```

6. Generate the app key

```bash
php artisan key:gen
```

7. Run migration (and seeders if necessary)

```bash
php artisan migrate --seed
```

8. Link the storage folder with public folder

```bash
php artisan storage:link
```

9. Serve the application

```bash
php artisan serve
```

**Enjoy**

*@saddamhshovon*