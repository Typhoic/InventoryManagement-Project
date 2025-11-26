# Docker dev helpers

This folder contains instructions to run the backend locally using Docker Compose.

Prerequisites
- Docker Desktop (Windows)

Quick start (PowerShell)

1. Copy environment example into a real `.env` in the backend folder and set DB host to `db`:

```powershell
cd "D:\0Personal Files\College\New Inventory Management\InventoryManagement-Project\backend"
cp .env.example .env
# Edit .env and set DB_HOST=db, DB_DATABASE=inventory, DB_USERNAME=inventoryuser, DB_PASSWORD=inventorypass
```

2. Start services

```powershell
cd "D:\0Personal Files\College\New Inventory Management\InventoryManagement-Project"
docker-compose up -d --build
```

3. Install PHP dependencies (first run)

```powershell
docker-compose exec app composer install
docker-compose exec app php artisan key:generate
docker-compose exec app php artisan migrate --seed
```

4. Visit
- App: http://localhost:8000
- phpMyAdmin: http://localhost:8080 (user: root, password: rootpassword)

Notes
- The `app` service mounts the `backend/` folder into the container so code edits are immediate.
- If you use a different DB password or name, update both `.env` and `docker-compose.yml` (or better: use an override `.env` for compose).
