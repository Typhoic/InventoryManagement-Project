# Inventory Management System - Backend API

This is the Laravel backend API for the Inventory Management System. It handles all data management, database operations, and provides REST API endpoints for the frontend.

## 📋 Prerequisites

Before you start, ensure you have the following installed on your machine:

- **PHP** 8.1 or higher
- **Composer** (PHP package manager)
- **MySQL** 5.7 or higher (or MariaDB)
- **Git**

### Installation Check (Windows PowerShell)

```powershell
php -v
composer -V
mysql --version
git --version
```

If any of these commands fail, you need to install the missing software.

---

## 🚀 Quick Start

### 1. Clone the Repository

```powershell
git clone https://github.com/Typhoic/InventoryManagement-Project.git
cd InventoryManagement-Project
git checkout backend
cd backend
```

### 2. Install PHP Dependencies

```powershell
composer install
```

This will install all required PHP packages (Laravel, database drivers, etc.) into the `vendor/` folder.

### 3. Setup Environment Variables

Copy the example `.env` file and configure it for your local machine:

```powershell
copy .env.example .env
```

Now open the `.env` file in a text editor and update the database credentials:

```env
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=inventory_db
DB_USERNAME=root
DB_PASSWORD=          # Leave blank if no password, or enter your MySQL password
```

### 4. Generate Application Key

```powershell
php artisan key:generate
```

### 5. Create the Database in MySQL

```powershell
# Open MySQL command line
mysql -u root -p

# Then run (in MySQL terminal):
CREATE DATABASE inventory_db;
EXIT;
```

Or use a GUI tool like MySQL Workbench to create the database.

### 6. Run Migrations (Create Tables)

```powershell
php artisan migrate
```

This will create all the required tables in your database based on the migration files.

### 7. Seed the Database (Add Sample Data)

```powershell
php artisan db:seed
```

This will populate your database with sample data for testing and development.

### 8. Start the Development Server

```powershell
php artisan serve
```

This will start the server at: **http://127.0.0.1:8000**

---

## 📡 API Endpoints

All endpoints are prefixed with `/api/`

### Sales Endpoints

- `GET /api/sales` - Get all sales
- `GET /api/sales/{id}` - Get a specific sale
- `POST /api/sales` - Create a new sale
- `PUT /api/sales/{id}` - Update a sale
- `DELETE /api/sales/{id}` - Delete a sale
- `GET /api/sales/chart/data` - Get chart data for dashboard (sales trend)

### Inventory Endpoints

- `GET /api/inventory` - Get all inventory items
- `GET /api/inventory/{id}` - Get a specific inventory item
- `POST /api/inventory` - Create a new inventory item
- `PUT /api/inventory/{id}` - Update an inventory item
- `DELETE /api/inventory/{id}` - Delete an inventory item

### Items Endpoints

- `GET /api/items` - Get all items
- `GET /api/items/{id}` - Get a specific item
- `POST /api/items` - Create a new item
- `PUT /api/items/{id}` - Update an item
- `DELETE /api/items/{id}` - Delete an item
- `GET /api/items/low-stock` - Get items with low stock (quantity < reorder level)

### Health Check

- `GET /api/health` - Check if backend is running

---

## 🧪 Testing the API

### Using PowerShell / curl

```powershell
# Get all sales
curl http://127.0.0.1:8000/api/sales

# Get all inventory
curl http://127.0.0.1:8000/api/inventory

# Get low-stock items
curl http://127.0.0.1:8000/api/items/low-stock

# Get chart data
curl http://127.0.0.1:8000/api/sales/chart/data
```

### Using Postman

1. Download and install [Postman](https://www.postman.com/downloads/)
2. Create a new request
3. Set method to `GET`
4. Enter URL: `http://127.0.0.1:8000/api/sales`
5. Click "Send"

---

## 📊 Database Schema

### sales table
```
id (Primary Key)
product_name (String)
quantity (Integer)
price (Decimal)
sale_date (Date)
created_at (Timestamp)
updated_at (Timestamp)
```

### inventory table
```
id (Primary Key)
item_name (String)
quantity_on_hand (Integer)
reorder_level (Integer)
unit_price (Decimal)
category (String)
created_at (Timestamp)
updated_at (Timestamp)
```

### menu_items table
```
id (Primary Key)
menu_name (String)
price (Decimal)
category (String)
image_url (String)
created_at (Timestamp)
updated_at (Timestamp)
```

---

## 🔧 Useful Commands

```powershell
# Start the development server
php artisan serve

# Run migrations only
php artisan migrate

# Run seeders only
php artisan db:seed

# Create tables AND seed data (combined)
php artisan migrate --seed

# Rollback all migrations (delete all tables)
php artisan migrate:rollback

# Reset database (delete all tables and reseed)
php artisan migrate:refresh --seed

# Create a new model and migration
php artisan make:model ModelName -m

# Create a new controller
php artisan make:controller Api/ControllerName

# Create a new migration
php artisan make:migration create_table_name

# Create a new seeder
php artisan make:seeder SeederName

# List all available artisan commands
php artisan list
```

---

## 🐛 Troubleshooting

### Error: "PDO Exception: could not find driver"
- Make sure PHP extension `pdo_mysql` is installed. Check your php.ini file.

### Error: "SQLSTATE[HY000] [2002] No such file or directory"
- Your MySQL server is not running or the connection details in `.env` are wrong.
- Start your MySQL server and verify DB_HOST, DB_PORT, DB_USERNAME, DB_PASSWORD.

### Error: "Database [inventory_db] not found"
- Create the database: `CREATE DATABASE inventory_db;` in MySQL.

### Migrations won't run
- Make sure you've run `composer install` first.
- Check that `.env` file exists and has correct database credentials.

### Port 8000 already in use
- Change the port: `php artisan serve --port=8001`

---

## 🔐 CORS Configuration

The backend is configured to accept requests from the frontend at:
- `http://localhost:5500`
- `http://localhost:3000`
- `http://127.0.0.1:5500`
- `http://127.0.0.1:3000`

To add more origins, edit `config/cors.php` and add to the `allowed_origins` array.

---

## 📝 Frontend Integration

The frontend team can fetch data from these endpoints using JavaScript `fetch()` or similar HTTP clients.

**Example (Frontend JavaScript):**
```javascript
fetch('http://127.0.0.1:8000/api/sales')
  .then(response => response.json())
  .then(data => {
    console.log('Sales:', data);
    // Use data to render charts, tables, etc.
  });
```

---

## 📚 Laravel Documentation

- [Laravel Official Docs](https://laravel.com/docs)
- [Eloquent ORM](https://laravel.com/docs/eloquent)
- [API Resources](https://laravel.com/docs/eloquent-resources)

---

## 🤝 Team Workflow

### Backend Team

1. **Pull latest changes:**
   ```powershell
   git pull origin backend
   ```

2. **Create a new feature/fix:**
   ```powershell
   git checkout -b feature/your-feature-name
   ```

3. **Make changes, commit, and push:**
   ```powershell
   git add .
   git commit -m "backend: description of changes"
   git push origin feature/your-feature-name
   ```

4. **Create a Pull Request** on GitHub to merge into `backend`.

### Syncing with Frontend

To pull latest frontend changes into the backend branch:

```powershell
git fetch origin main
git merge origin/main
```

---

## 📞 Questions?

Contact the backend team or create an issue on GitHub.

---

**Created:** January 2025  
**Project:** Cal's Chicken Bowl - Inventory Management System
