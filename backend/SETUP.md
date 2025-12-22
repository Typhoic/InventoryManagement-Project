# Backend Setup & Run Instructions

## Prerequisites
Make sure you have installed:
- **PHP 8.1+** (check: `php -v`)
- **Composer** (check: `composer -v`)
- **MySQL Server** (running on localhost:3306)

---

## Quick Setup (Windows PowerShell)

### Step 1: Copy `.env` file
```powershell
cd backend
Copy-Item .env.example .env
```

### Step 2: Install dependencies
```powershell
composer install
```

### Step 3: Generate app key
```powershell
php artisan key:generate
```

### Step 4: Create MySQL database
Open MySQL CLI or use a GUI tool (MySQL Workbench, phpMyAdmin, etc.) and run:
```sql
CREATE DATABASE inventory_management;
CREATE USER 'inventory_user'@'localhost' IDENTIFIED BY 'password123';
GRANT ALL PRIVILEGES ON inventory_management.* TO 'inventory_user'@'localhost';
FLUSH PRIVILEGES;
```

Or if you prefer root access, update `.env`:
```
DB_USERNAME=root
DB_PASSWORD=
```

### Step 5: Update `.env` with correct DB credentials
Edit `backend/.env` and update:
```
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=inventory_management
DB_USERNAME=root
DB_PASSWORD=yourpassword
```

### Step 6: Run migrations and seeders
```powershell
php artisan migrate --seed
```

This will:
- Create all tables (orders, menu_items, ingredients, etc.)
- Seed sample data (ingredients, ingredient groups, menu items)

### Step 7: Start the development server
```powershell
php artisan serve --port=8000
```

You should see:
```
   INFO  Server running on [http://127.0.0.1:8000].
```

---

## Testing the API

### Health check
```bash
curl http://127.0.0.1:8000/api/health
```

Expected response:
```json
{"status":"ok","message":"Backend is running"}
```

### Get all menu items
```bash
curl http://127.0.0.1:8000/api/menu-items
```

### Get all ingredients
```bash
curl http://127.0.0.1:8000/api/ingredients
```

### Create an order (POST)
```bash
curl -X POST http://127.0.0.1:8000/api/orders \
  -H "Content-Type: application/json" \
  -d '{
    "channel": "dine_in",
    "total_amount": 25000,
    "status": "completed",
    "items": [
      {
        "menu_item_id": 1,
        "quantity": 1,
        "price": 25000
      }
    ]
  }'
```

---

## Available API Endpoints

**Menu Items:**
- `GET /api/menu-items` — List all menu items
- `POST /api/menu-items` — Create menu item
- `GET /api/menu-items/{id}` — Get single menu item
- `PUT /api/menu-items/{id}` — Update menu item
- `DELETE /api/menu-items/{id}` — Delete menu item

**Orders:**
- `GET /api/orders` — List all orders
- `POST /api/orders` — Create order with items
- `GET /api/orders/{id}` — Get single order
- `PUT /api/orders/{id}` — Update order status
- `DELETE /api/orders/{id}` — Delete order

**Ingredients:**
- `GET /api/ingredients` — List all ingredients
- `POST /api/ingredients` — Create ingredient
- `GET /api/ingredients/{id}` — Get single ingredient
- `GET /api/ingredients/low-stock` — Get low stock ingredients
- `PUT /api/ingredients/{id}` — Update ingredient stock
- `DELETE /api/ingredients/{id}` — Delete ingredient

---

## Troubleshooting

### "php: command not found"
Install PHP or add it to your system PATH.

### "composer: command not found"
Install Composer from https://getcomposer.org/

### "SQLSTATE[HY000]: General error: 1030 Got error..."
MySQL server is not running. Start MySQL:
```powershell
mysql -u root -p
```

### "Specified key was too long; max key length is 767 bytes"
Update `backend/config/database.php`, find the MySQL section and add:
```php
'mysql' => [
    ...
    'engine' => 'InnoDB',
    ...
]
```

### Migration errors
If migrations fail, reset and try again:
```powershell
php artisan migrate:reset
php artisan migrate --seed
```

---

## Frontend Setup

In `frontend/`, you'll find static HTML/CSS files. The frontend team should:
1. Open `frontend/index.html` (or equivalent) in a browser
2. Use JavaScript to fetch data from `http://127.0.0.1:8000/api/*` endpoints

Example fetch call:
```javascript
fetch('http://127.0.0.1:8000/api/menu-items')
  .then(res => res.json())
  .then(data => console.log(data))
  .catch(err => console.error(err));
```

---

## Next Steps

- Backend team reviews and approves the API controllers
- Frontend team builds the dashboard UI using these endpoints
- Test daily sales tracking (once requirement is finalized)
