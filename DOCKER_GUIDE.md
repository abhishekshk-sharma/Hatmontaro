# Docker Deployment & Setup Guide for Hatmontaro

This project includes a production-ready, multi-stage Docker environment with PHP 8.2 FPM, Nginx, Node.js (asset building), MySQL 8.0, and Redis.

---

## 📁 Docker Structure

* `Dockerfile`: Multi-stage build (Node.js builds Vite assets, PHP 8.2 Alpine with Nginx & Supervisor runs the app).
* `docker-compose.yml`: Coordinates the `app`, `db` (MySQL 8), and `redis` containers.
* `.dockerignore`: Excludes local `vendor`, `node_modules`, `.git`, and cache files from the build context.
* `docker/nginx/default.conf`: Production Nginx configuration with Laravel routing and 64M upload limit.
* `docker/php/local.ini`: PHP upload, memory limits (512M), and OPcache optimizations.
* `docker/supervisord.conf`: Process manager running Nginx and PHP-FPM together.
* `docker/entrypoint.sh`: Automates key generation, storage linking, and permission setup on startup.

---

## 🚀 Quick Start with Docker Compose

### 1. Build and Start All Containers
```bash
docker compose up -d --build
```

### 2. View Container Status
```bash
docker compose ps
```

### 3. Run Database Migrations & Seeders
```bash
# Run migrations
docker compose exec app php artisan migrate --force

# (Optional) Seed sample data
docker compose exec app php artisan db:seed --force
```

### 4. Access the Application
* **Storefront**: [http://localhost:8000](http://localhost:8000)
* **Admin Panel**: [http://localhost:8000/admin/login](http://localhost:8000/admin/login)
* **MySQL Database**: `localhost:3307` (user: `hatmontaro_user`, password: `hatmontaro_secret`, db: `cloth_ai`)

---

## 🛠️ Helpful Docker Commands

| Action | Command |
|---|---|
| **Stop containers** | `docker compose down` |
| **Stop & delete volumes** | `docker compose down -v` |
| **View logs** | `docker compose logs -f app` |
| **Access container bash** | `docker compose exec app bash` |
| **Clear Laravel cache in container** | `docker compose exec app php artisan optimize:clear` |
| **Run Artisan command** | `docker compose exec app php artisan <command>` |

---

## 🐳 Building Standalone Docker Image

If you are deploying to AWS ECS, DigitalOcean App Platform, GCP Cloud Run, or Kubernetes:

```bash
docker build -t hatmontaro:latest .
docker run -p 8000:80 --env-file .env hatmontaro:latest
```
