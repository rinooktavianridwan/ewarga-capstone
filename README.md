# Ewarga - Installation Guide

## Prerequisites
Before you begin, make sure you have the following installed on your system:

- **PHP** (>= 8.1)
- **Composer** (PHP package manager)
- **MySQL** (or any supported database)
---

## Installation Steps

### 1. Clone the Repository (If Using Git)
```bash
git clone https://github.com/CAPSTONE-FILKOM/ewarga.git
cd ewarga
```

### 2. Install Dependencies
```bash
composer install
```

### 3. Create Environment File
```bash
cp .env.example .env
```
Modify the `.env` file to configure database and other settings.

### 4. Generate Application Key
```bash
php artisan key:generate
```

### 5. Configure Database
Edit the `.env` file with your database details:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_database
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

### 6. Run Migrations
```bash
php artisan migrate
```

### 7. Seed the Database (Optional)
If your project includes seeders, run:
```bash
php artisan db:seed
```

### 8. Set Permissions
Ensure `storage` and `bootstrap/cache` have the correct permissions:
```bash
chmod -R 775 storage bootstrap/cache
```

### 9. Start Development Server
```bash
php artisan serve
```
Your Laravel app is now running at `http://127.0.0.1:8000`.

---

## Additional Commands

### Run Queue (If Using Jobs)
```bash
php artisan queue:work
```

### Run Scheduler (If Using Scheduled Tasks)
```bash
php artisan schedule:work
```

### Clear Cache (If Needed)
```bash
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
```

---
Your Laravel application is now ready! 🚀 Happy coding!

