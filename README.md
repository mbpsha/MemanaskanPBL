# WebRunning - Laravel 12 Event Registration System

A comprehensive running event registration platform built with **Laravel 12**, **Vue 3**, **Inertia.js**, and **Tailwind CSS**.

## 🚀 Features

### User Features
- ✅ Browse and search running events
- ✅ Filter events by status, date, and location
- ✅ View detailed event information
- ✅ Register for events with custom categories
- ✅ Upload payment proof
- ✅ Track registration and payment status
- ✅ View personal dashboard with all registrations

### Admin Features
- ✅ Comprehensive admin dashboard with statistics
- ✅ Manage events (CRUD operations)
- ✅ View and verify participant registrations
- ✅ Approve/reject payment proofs
- ✅ Export participant lists
- ✅ Generate registration certificates

### Technical Features
- ✅ Laravel 12 with latest features
- ✅ Vue 3 with Composition API
- ✅ Inertia.js for SPA experience
- ✅ Tailwind CSS for beautiful UI
- ✅ File upload with validation
- ✅ Role-based access control
- ✅ Email notifications
- ✅ PDF and Excel exports

## 📋 Requirements

- PHP 8.2 or higher
- Composer 2.5+
- Node.js 18+ and NPM
- MySQL 8.0+ or MariaDB 10.3+
- XAMPP/WAMP/LAMP (for local development)

## 🛠️ Installation

### 1. Clone or Navigate to Project

```bash
cd c:\xampp\htdocs\WebRunning
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

The `.env` file should already be configured. If not, copy from `.env.example`:

```bash
copy .env.example .env
```

Update database credentials in `.env`:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=webrunning
DB_USERNAME=root
DB_PASSWORD=
```

### 5. Generate Application Key

```bash
php artisan key:generate
```

### 6. Create Database

Create a MySQL database named `webrunning`:

```sql
CREATE DATABASE webrunning CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

Or use phpMyAdmin to create the database.

### 7. Run Migrations and Seeders

```bash
php artisan migrate:fresh --seed
```

This will create all tables and seed with sample data:
- Admin user: `admin@webrunning.com` / `password`
- Test user: `user@webrunning.com` / `password`
- 3 sample events

### 8. Create Storage Link

```bash
php artisan storage:link
```

### 9. Build Frontend Assets

For development:
```bash
npm run dev
```

For production:
```bash
npm run build
```

### 10. Start Development Server

```bash
php artisan serve
```

The application will be available at: `http://localhost:8000`

## 👤 Default Credentials

### Admin Account
- **Email**: admin@webrunning.com
- **Password**: password

### Test User Account
- **Email**: user@webrunning.com
- **Password**: password

## 📂 Project Structure

```
WebRunning/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── DashboardController.php
│   │   │   ├── EventController.php
│   │   │   ├── PaymentProofController.php
│   │   │   └── Admin/
│   │   │       └── DashboardController.php
│   │   ├── Middleware/
│   │   │   └── AdminMiddleware.php
│   │   └── Requests/
│   │       ├── RegistrationRequest.php
│   │       └── PaymentProofRequest.php
│   └── Models/
│       ├── User.php
│       ├── Event.php
│       ├── Registration.php
│       └── PaymentProof.php
├── database/
│   ├── migrations/
│   └── seeders/
│       └── DatabaseSeeder.php
├── resources/
│   ├── js/
│   │   ├── Pages/
│   │   │   ├── Events/
│   │   │   │   ├── Index.vue
│   │   │   │   └── Show.vue
│   │   │   └── Dashboard.vue
│   │   └── Layouts/
│   │       └── AuthenticatedLayout.vue
│   └── css/
│       └── app.css
└── routes/
    └── web.php
```

## 🎯 Usage Guide

### For Users

1. **Register an Account**
   - Click "Register" on the homepage
   - Fill in your details
   - Verify your email (if email verification is enabled)

2. **Browse Events**
   - Navigate to "Events" page
   - Use search and filters to find events
   - Click "View Details" to see event information

3. **Register for an Event**
   - On the event detail page, click "Register Now"
   - Fill in the registration form
   - Select your category and T-shirt size
   - Provide emergency contact information
   - Accept terms and conditions
   - Submit registration

4. **Upload Payment Proof**
   - Go to your Dashboard
   - Find your registration
   - Click "Upload Payment Proof"
   - Upload screenshot/photo of payment
   - Fill in payment details
   - Submit for verification

5. **Track Status**
   - View your dashboard to see all registrations
   - Check payment verification status
   - Download registration certificate (when approved)

### For Admins

1. **Access Admin Dashboard**
   - Login with admin credentials
   - Navigate to `/admin/dashboard`

2. **View Statistics**
   - See total participants, events, and payments
   - View recent registrations
   - Monitor events needing attention

3. **Manage Events**
   - Create new events
   - Edit existing events
   - Set registration periods
   - Define categories and pricing

4. **Verify Payments**
   - View pending payment proofs
   - Approve or reject payments
   - Add verification notes

5. **Manage Participants**
   - View all registrations
   - Assign BIB numbers
   - Export participant lists
   - Generate certificates

## 🔧 Development Commands

```bash
# Start development server
php artisan serve

# Start Vite dev server (in separate terminal)
npm run dev

# Run migrations
php artisan migrate

# Seed database
php artisan db:seed

# Fresh migration with seeding
php artisan migrate:fresh --seed

# Clear caches
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan cache:clear

# Run tests
php artisan test

# Build for production
npm run build
```

## 📧 Email Configuration

For development, emails are logged to `storage/logs/laravel.log`.

For production, update `.env`:

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=your_username
MAIL_PASSWORD=your_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="noreply@webrunning.com"
MAIL_FROM_NAME="${APP_NAME}"
```

## 🔒 Security

- All routes are protected with appropriate middleware
- Admin routes require `admin` role
- File uploads are validated (type, size)
- CSRF protection enabled
- SQL injection prevention via Eloquent ORM
- XSS protection via Vue.js escaping

## 🚀 Deployment

### Production Checklist

1. Set `APP_ENV=production` in `.env`
2. Set `APP_DEBUG=false`
3. Configure production database
4. Set up proper mail service
5. Configure file storage (S3 recommended)
6. Run `composer install --optimize-autoloader --no-dev`
7. Run `npm run build`
8. Run `php artisan config:cache`
9. Run `php artisan route:cache`
10. Run `php artisan view:cache`
11. Set up queue workers
12. Configure SSL certificate
13. Set up automated backups

## 📝 API Documentation

### Public Routes
- `GET /` - Welcome page
- `GET /events` - List all events
- `GET /events/{slug}` - Event details

### Authenticated Routes
- `GET /dashboard` - User dashboard
- `POST /events/{event}/register` - Register for event
- `POST /registrations/{registration}/payment-proof` - Upload payment proof
- `GET /payment-proofs/{paymentProof}` - View payment proof

### Admin Routes
- `GET /admin/dashboard` - Admin dashboard
- (More admin routes to be implemented)

## 🐛 Troubleshooting

### Database Connection Error
- Ensure MySQL is running
- Check database credentials in `.env`
- Verify database exists

### Storage Permission Error
```bash
chmod -R 775 storage
chmod -R 775 bootstrap/cache
```

### Vite Not Running
```bash
npm install
npm run dev
```

### Class Not Found Error
```bash
composer dump-autoload
```

## 📄 License

This project is open-sourced software licensed under the MIT license.

## 👥 Support

For issues and questions:
- Create an issue on GitHub
- Contact: admin@webrunning.com

## 🎉 Credits

Built with:
- [Laravel 12](https://laravel.com)
- [Vue 3](https://vuejs.org)
- [Inertia.js](https://inertiajs.com)
- [Tailwind CSS](https://tailwindcss.com)
- [Heroicons](https://heroicons.com)

---

**Happy Running! 🏃‍♂️🏃‍♀️**
