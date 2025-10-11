# Laravel Application

## Prerequisites

Before you begin, ensure you have the following installed on your system:

- PHP >= 8.1
- Composer
- Node.js & NPM
- MySQL/PostgreSQL/SQLite (or your preferred database)
- Web server (Apache/Nginx) or use Laravel's built-in server

## Installation

Follow these steps to set up the application:

### 1. Clone the Repository

```bash
git clone <repository-url>
cd <project-directory>
```

### 2. Install PHP Dependencies

```bash
composer install
```

### 3. Install Node Dependencies

```bash
npm install
```

### 4. Environment Configuration

Copy the `.env.example` file to `.env`:

```bash
cp .env.example .env
```

Update the `.env` file with your database credentials:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_database_name
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

### 5. Generate Application Key

```bash
php artisan key:generate
```

### 6. Run Database Migrations

```bash
php artisan migrate
```

### 7. Seed the Database

```bash
php artisan db:seed
```

### 8. Build Assets (Optional)

For development:
```bash
npm run dev
```

For production:
```bash
npm run build
```

### 9. Start the Development Server

```bash
php artisan serve
```

The application will be available at `http://localhost:8000`

## Additional Commands

### Clear Application Cache

```bash
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
```

### Create Storage Link

```bash
php artisan storage:link
```

## Troubleshooting

- **Permission Issues**: Ensure the `storage` and `bootstrap/cache` directories are writable:
  ```bash
  chmod -R 775 storage bootstrap/cache
  ```

- **Database Connection Issues**: Verify your database credentials in the `.env` file and ensure the database exists.

## License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

## Support

For issues and questions, please open an issue in the repository.