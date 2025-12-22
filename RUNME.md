# Complete Setup Guide: Cal's Chicken Bowl Inventory System

## Prerequisites
- **XAMPP** installed (with Apache, MySQL, PHP 8.2)
- **Git** installed
- Project folder cloned/located at: `D:\0Personal Files\College\New Inventory Management\InventoryManagement-Project`

---

## Step-by-Step: Running the Application

### **Step 1: Start XAMPP & MySQL**
1. Open **XAMPP Control Panel**
2. Click **Start** for **MySQL** (wait for it to show "Running" in green)
3. Close the XAMPP panel (MySQL will keep running in background)

### **Step 2: Create Database & User (One-time only)**
1. Open **phpMyAdmin** in browser: http://localhost/phpmyadmin
2. Go to **SQL** tab and run:
```sql
CREATE DATABASE inventory_management;
CREATE USER 'inventory_user'@'localhost' IDENTIFIED BY 'password123';
GRANT ALL PRIVILEGES ON inventory_management.* TO 'inventory_user'@'localhost';
FLUSH PRIVILEGES;
```

### **Step 3: Start Backend Server**
1. Open **PowerShell**
2. Navigate to backend folder:
```powershell
cd 'D:\0Personal Files\College\New Inventory Management\InventoryManagement-Project\backend'
```
3. Start the server:
```powershell
& 'C:\xampp\php\php.exe' -S 127.0.0.1:8000 -t public
```
4. You should see: `Listening on http://127.0.0.1:8000`
5. **Keep this terminal open** (don't close it)

### **Step 4: Start Frontend Server**
1. Open a **new PowerShell** window
2. Navigate to project root:
```powershell
cd 'D:\0Personal Files\College\New Inventory Management\InventoryManagement-Project'
```
3. Start the server:
```powershell
& 'C:\xampp\php\php.exe' -S 127.0.0.1:3000
```
4. You should see: `Listening on http://127.0.0.1:3000`
5. **Keep this terminal open** (don't close it)

### **Step 5: Open Dashboard**
1. Open your browser (Chrome, Firefox, Edge, etc.)
2. Go to: **http://127.0.0.1:3000/ccb_project.html**
3. You should see the Cal's Chicken Bowl dashboard with:
   - Sales Order section
   - Inventory management
   - Graphs and charts

---

## What's Running Where

| Component | URL | Port | Server |
|-----------|-----|------|--------|
| **Dashboard** | http://127.0.0.1:3000/ccb_project.html | 3000 | Frontend |
| **API Health** | http://127.0.0.1:8000/api/health | 8000 | Backend (Laravel) |
| **Menu Items** | http://127.0.0.1:8000/api/menu-items | 8000 | Backend |
| **Orders** | http://127.0.0.1:8000/api/orders | 8000 | Backend |
| **Ingredients** | http://127.0.0.1:8000/api/ingredients | 8000 | Backend |
| **Database** | localhost:3306 | 3306 | MySQL (XAMPP) |

---

## Troubleshooting

### Backend won't start
- Make sure MySQL is running in XAMPP
- Confirm you're in the `backend` folder when starting the server
- Use XAMPP's PHP: `C:\xampp\php\php.exe` (not system PHP)

### Frontend not showing data
- Check that backend server is running on port 8000
- Open browser console (F12) to see API errors
- The frontend will call `http://127.0.0.1:8000/api/` endpoints

### Database connection error
- Check MySQL is running in XAMPP Control Panel
- Verify database was created in phpMyAdmin
- Confirm `.env` file has correct credentials:
  ```
  DB_HOST=127.0.0.1
  DB_DATABASE=inventory_management
  DB_USERNAME=inventory_user
  DB_PASSWORD=password123
  ```

### Port already in use
- If port 8000 or 3000 is busy, use a different port:
  ```powershell
  & 'C:\xampp\php\php.exe' -S 127.0.0.1:8001 -t public
  ```

---

## To Stop Everything

1. **Close both PowerShell terminals** (stops frontend and backend servers)
2. **Open XAMPP Control Panel** and click **Stop** for MySQL
3. Done!

---

## Next Steps

- **Modify Dashboard**: Edit `ccb_project.html`, `app.js`, and `styles/` files
- **Add API Features**: Add endpoints in `backend/app/Http/Controllers/Api/`
- **Push Changes**: `git add .` → `git commit -m "message"` → `git push origin backend`
