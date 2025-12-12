# Local setup (MySQL) — Backend

Follow these steps on Windows (PowerShell) to run the Laravel backend locally using MySQL.

Prerequisites
- PHP 8.1+ and PHP CLI in PATH
- Composer in PATH
- MySQL server installed (or MariaDB) and `mysql`/`mysqldump` client available in PATH

Quick steps (PowerShell)

1) Open PowerShell and go to backend folder:

```powershell
cd 'D:\0Personal Files\College\New Inventory Management\InventoryManagement-Project\backend'
```

2) Install PHP dependencies:

```powershell
composer install
```

3) Copy `.env.example` and update DB settings (open `.env` in an editor):

```powershell
copy .env.example .env
# Edit .env to use MySQL:
# DB_CONNECTION=mysql
# DB_HOST=127.0.0.1
# DB_PORT=3306
# DB_DATABASE=inventory
# DB_USERNAME=inventoryuser
# DB_PASSWORD=inventorypass
```

4) Create the database and user (run the SQL helper). In PowerShell run:

```powershell
# You will be prompted for the MySQL root password
mysql -u root -p < .\database\create_mysql_user.sql
```

If you prefer to run SQL manually, the file `.\database\create_mysql_user.sql` contains the commands.

5) Generate app key and run migrations + seeders:

```powershell
php artisan key:generate
php artisan migrate --seed
```

6) Serve the app:

```powershell
php artisan serve --host=127.0.0.1 --port=8000
```

Visit the API at: http://127.0.0.1:8000/api/health

7) Run tests:

```powershell
php artisan test
# or
vendor\bin\phpunit
```

Export DB snapshot (optional)

If you want to create an updated SQL dump after migrations/seeding:

```powershell
# Ensure mysqldump is in PATH
mysqldump -u inventoryuser -p inventory > ..\backend\database\database.sql
# (You will be prompted for password 'inventorypass')
```

Troubleshooting
- If `mysql`/`mysqldump` is not found, add the MySQL `bin` folder to your PATH or run via full path.
- If migrations fail, check `.env` DB credentials and that MySQL server is running.
- If you prefer not to install MySQL, you can use SQLite (see project README); tell me and I will add a small `.env` template for SQLite.
