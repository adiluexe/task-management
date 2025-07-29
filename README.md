# Task Management System

> **🏆 A comprehensive full-stack task management application built with Laravel 11 & Vue 3**

A modern, feature-rich task management system showcasing enterprise-level development practices with real-time updates, intuitive drag-and-drop functionality, comprehensive admin tools, and production-ready architecture.

[![Laravel Version](https://img.shields.io/badge/Laravel-11.x-FF2D20?style=flat-square&logo=laravel)](https://laravel.com)
[![Vue Version](https://img.shields.io/badge/Vue.js-3.x-4FC08D?style=flat-square&logo=vue.js)](https://vuejs.org)
[![PHP Version](https://img.shields.io/badge/PHP-8.2+-777BB4?style=flat-square&logo=php)](https://php.net)
[![TailwindCSS](https://img.shields.io/badge/TailwindCSS-3.x-06B6D4?style=flat-square&logo=tailwindcss)](https://tailwindcss.com)
[![License](https://img.shields.io/badge/license-MIT-green?style=flat-square)](LICENSE)

## 🎯 **Project Overview**

This task management system demonstrates **professional full-stack development** with modern Laravel and Vue.js, implementing industry best practices, comprehensive testing, and production-ready features. The application provides an intuitive interface for managing tasks with advanced features like real-time updates, drag-and-drop reordering, and comprehensive admin controls.

**🖼️ [📱 View Application Screenshots & Visual Tour →](SHOWCASE.md)**

## ✨ **Key Features**

### 🚀 **Core Functionality**

- **User Authentication** - Secure registration/login with Laravel Sanctum
- **Task Management** - Complete CRUD operations with priority and status tracking
- **Drag & Drop** - Intuitive task reordering with real-time backend synchronization
- **Advanced Filtering** - Filter by status, priority, and search functionality
- **Real-time Updates** - Live task updates using WebSockets
- **Responsive Design** - Mobile-first approach with modern UI/UX

### 👨‍💼 **Admin Features**

- **Admin Dashboard** - Comprehensive statistics and user management
- **User Management** - View, edit, and manage all users
- **Activity Monitoring** - Track user activities and system events
- **Task Analytics** - View task completion rates and statistics
- **Role Management** - Admin/user role assignment and permissions

### 🎨 **Enhanced UI/UX**

- **Modern Interface** - Clean, intuitive design with glassmorphism effects
- **Empty States** - Engaging empty state designs with contextual actions
- **Animations** - Smooth transitions and micro-interactions
- **Dark Mode Ready** - Modern color scheme with Tailwind CSS
- **Mobile Optimized** - Fully responsive across all devices

### 🛡️ **Security & Performance**

- **Secure Authentication** - Laravel Sanctum SPA authentication
- **Input Validation** - Comprehensive server-side validation
- **CSRF Protection** - Built-in Laravel security features
- **Rate Limiting** - API rate limiting and abuse prevention
- **Optimized Queries** - Efficient database operations with Eloquent

## 🛠️ **Tech Stack**

### **Backend (Laravel 11)**

```
├── Framework: Laravel 11.x
├── Database: MySQL 8.0+
├── Authentication: Laravel Sanctum
├── API Documentation: Swagger/OpenAPI 3.0
├── Testing: PHPUnit
├── Caching: Redis (optional)
└── Queue System: Redis/Database
```

### **Frontend (Vue 3)**

```
├── Framework: Vue 3 (Composition API)
├── State Management: Pinia
├── Routing: Vue Router 4
├── Styling: TailwindCSS 3.x
├── HTTP Client: Axios
├── Icons: Iconify Vue
├── Drag & Drop: Vue Draggable Plus
└── Build Tool: Vite
```

## 📋 **Prerequisites**

Before you begin, ensure you have the following installed:

- **PHP** >= 8.2 with extensions: BCMath, Ctype, Fileinfo, JSON, Mbstring, OpenSSL, PDO, Tokenizer, XML
- **Node.js** >= 18.x and npm
- **Composer** >= 2.0
- **MySQL** >= 8.0 or MariaDB >= 10.3
- **Git** for version control

### **Optional but Recommended**

- **Redis** >= 6.0 (for caching and queues)
- **Supervisor** (for production queue management)

## 🚀 **Installation & Setup**

### **1. Clone the Repository**

```bash
git clone https://github.com/adiluexe/task-management.git
cd task-management
```

### **2. Backend Setup (Laravel)**

Navigate to the backend directory and install dependencies:

```bash
cd backend
composer install
```

**Environment Configuration:**

```bash
cp .env.example .env
php artisan key:generate
```

**Configure your `.env` file:**

```env
# Application
APP_NAME="Task Management System"
APP_ENV=local
APP_KEY=base64:your_generated_key
APP_DEBUG=true
APP_URL=http://localhost:8000

# Database Configuration
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=task_management
DB_USERNAME=your_database_username
DB_PASSWORD=your_database_password

# Cache & Queue (Optional - Redis)
CACHE_DRIVER=file
QUEUE_CONNECTION=sync
# For Redis:
# CACHE_DRIVER=redis
# QUEUE_CONNECTION=redis
# REDIS_HOST=127.0.0.1
# REDIS_PASSWORD=null
# REDIS_PORT=6379

# API Documentation
L5_SWAGGER_GENERATE_ALWAYS=true
L5_SWAGGER_CONST_HOST=http://localhost:8000

# Session Configuration
SESSION_DRIVER=file
SESSION_LIFETIME=120

# Sanctum Configuration
SANCTUM_STATEFUL_DOMAINS=localhost:5173,127.0.0.1:5173
```

**Database Setup:**

Create your MySQL database and run migrations:

```bash
# Create database (MySQL command line)
mysql -u root -p
CREATE DATABASE task_management;
exit

# Run migrations and seeders
php artisan migrate
php artisan db:seed
```

**Generate API Documentation:**

```bash
php artisan l5-swagger:generate
```

**Start the Backend Server:**

```bash
php artisan serve
# Server will be available at http://localhost:8000
```

### **3. Frontend Setup (Vue 3)**

Navigate to the frontend directory and install dependencies:

```bash
cd ../frontend  # or cd frontend from project root
npm install
```

**Environment Configuration:**

```bash
cp .env.example .env.local
```

**Configure your `.env.local` file:**

```env
# API Configuration
VITE_API_BASE_URL=http://localhost:8000/api
VITE_APP_URL=http://localhost:8000

# Application Settings
VITE_APP_NAME="Task Management System"
```

**Start the Frontend Development Server:**

```bash
npm run dev
# Frontend will be available at http://localhost:5173
```

### **4. Access the Application**

- **Frontend Application**: http://localhost:5173
- **Backend API**: http://localhost:8000/api
- **API Documentation**: http://localhost:8000/api/documentation

### **5. Default Admin User**

The application comes with a default admin user for testing:

```
Email: admin@example.com
Password: password
```

## 📚 **API Documentation**

### **Interactive Swagger Documentation**

The application includes comprehensive, interactive API documentation powered by Swagger/OpenAPI 3.0.

**🔗 Access Documentation**: http://localhost:8000/api/documentation

### **Features:**

- 📖 **Complete Endpoint Documentation** - All routes with detailed examples
- 🧪 **Interactive Testing** - Test API endpoints directly from the browser
- 🔐 **Authentication Support** - Bearer token authentication integrated
- 📝 **Request/Response Schemas** - Detailed data structure documentation
- 🏷️ **Organized by Tags** - Logical grouping of endpoints (Auth, Tasks, Admin)

### **Key API Endpoints**

#### **Authentication**

```http
POST /api/register          # User registration
POST /api/login             # User login
POST /api/logout            # User logout
GET  /api/user              # Get authenticated user
```

#### **Task Management**

```http
GET    /api/tasks           # Get user tasks (with filtering)
POST   /api/tasks           # Create new task
GET    /api/tasks/{id}      # Get specific task
PUT    /api/tasks/{id}      # Update task
DELETE /api/tasks/{id}      # Delete task
POST   /api/tasks/reorder   # Reorder tasks
```

#### **Admin Panel**

```http
GET    /api/admin/dashboard # Admin dashboard statistics
GET    /api/admin/users     # Get all users
PUT    /api/admin/users/{id}/toggle-admin # Toggle admin status
DELETE /api/admin/users/{id} # Delete user
GET    /api/admin/activities # Get system activities
```

## 🧪 **Testing**

### **Backend Testing (PHPUnit)**

The application includes a comprehensive test suite covering all major functionality:

```bash
cd backend

# Run all tests
php artisan test

# Run with coverage (if xdebug is installed)
php artisan test --coverage

# Run specific test suites
php artisan test --testsuite=Feature  # Integration tests
php artisan test --testsuite=Unit     # Unit tests

# Run specific test categories
php artisan test --filter=Auth        # Authentication tests
php artisan test --filter=Task        # Task management tests
php artisan test --filter=Admin       # Admin functionality tests
```

**Test Coverage:**

- ✅ **Authentication Tests** - Registration, login, logout, token validation
- ✅ **Task Management Tests** - CRUD operations, validation, authorization
- ✅ **Admin Functionality Tests** - Dashboard, user management, permissions
- ✅ **API Response Tests** - JSON structure, status codes, error handling

### **Frontend Testing**

```bash
cd frontend

# Run unit tests
npm run test

# Run tests with coverage
npm run test:coverage

# Run E2E tests (if configured)
npm run test:e2e
```

## 🚀 **Development Workflow**

### **Backend Development**

```bash
cd backend

# Install new dependencies
composer require package-name

# Create new migration
php artisan make:migration create_table_name

# Create new model with migration and factory
php artisan make:model ModelName -mf

# Create new controller
php artisan make:controller ControllerName

# Create new form request
php artisan make:request RequestName

# Create new seeder
php artisan make:seeder SeederName

# Clear application cache
php artisan optimize:clear
```

### **Frontend Development**

```bash
cd frontend

# Install new dependencies
npm install package-name

# Build for production
npm run build

# Preview production build
npm run preview

# Lint and fix code
npm run lint
npm run lint:fix
```

## 📁 **Project Structure**

### **Backend Structure**

```
backend/
├── app/
│   ├── Http/
│   │   ├── Controllers/     # API Controllers
│   │   ├── Requests/        # Form Request Validation
│   │   └── Resources/       # API Resources
│   ├── Models/              # Eloquent Models
│   ├── Policies/           # Authorization Policies
│   ├── Repositories/       # Repository Pattern
│   └── Services/           # Business Logic Services
├── database/
│   ├── migrations/         # Database Migrations
│   ├── seeders/           # Database Seeders
│   └── factories/         # Model Factories
├── routes/
│   ├── api.php            # API Routes
│   └── web.php            # Web Routes
└── tests/
    ├── Feature/           # Integration Tests
    └── Unit/              # Unit Tests
```

### **Frontend Structure**

```
frontend/
├── src/
│   ├── components/        # Reusable Vue Components
│   ├── views/            # Page Components
│   ├── stores/           # Pinia Stores
│   ├── services/         # API Services
│   ├── router/           # Vue Router Configuration
│   └── assets/           # Static Assets
├── public/               # Public Assets
└── tests/                # Frontend Tests
```

## 🚀 **Production Deployment**

### **Backend Deployment**

1. **Server Requirements:**

   - PHP 8.2+ with required extensions
   - MySQL 8.0+ or MariaDB 10.3+
   - Nginx or Apache web server
   - Supervisor (for queue workers)

2. **Deployment Steps:**

   ```bash
   # Clone repository
   git clone https://github.com/adiluexe/task-management.git
   cd task-management/backend

   # Install dependencies
   composer install --optimize-autoloader --no-dev

   # Configure environment
   cp .env.example .env
   # Edit .env with production settings

   # Generate application key
   php artisan key:generate

   # Run migrations
   php artisan migrate --force

   # Cache configuration
   php artisan config:cache
   php artisan route:cache
   php artisan view:cache

   # Generate API documentation
   php artisan l5-swagger:generate

   # Set permissions
   chmod -R 755 storage bootstrap/cache
   ```

3. **Web Server Configuration (Nginx):**

   ```nginx
   server {
       listen 80;
       server_name your-domain.com;
       root /var/www/task-management/backend/public;

       index index.php;

       location / {
           try_files $uri $uri/ /index.php?$query_string;
       }

       location ~ \.php$ {
           fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
           fastcgi_index index.php;
           fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
           include fastcgi_params;
       }
   }
   ```

### **Frontend Deployment**

1. **Build for Production:**

   ```bash
   cd frontend
   npm install
   npm run build
   ```

2. **Deploy to Static Hosting:**
   - **Netlify**: Drag and drop the `dist` folder
   - **Vercel**: Connect your GitHub repository
   - **AWS S3 + CloudFront**: Upload `dist` contents to S3 bucket

## 🔧 **Configuration Options**

### **Environment Variables**

#### **Backend (.env)**

```env
# Core Application
APP_NAME="Task Management System"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://your-domain.com

# Database
DB_CONNECTION=mysql
DB_HOST=your-db-host
DB_DATABASE=your-db-name
DB_USERNAME=your-db-user
DB_PASSWORD=your-db-password

# Cache & Queue
CACHE_DRIVER=redis
QUEUE_CONNECTION=redis
REDIS_HOST=your-redis-host

# Security
SANCTUM_STATEFUL_DOMAINS=your-frontend-domain.com
SESSION_DOMAIN=.your-domain.com
```

## 🛡️ **Security Features**

- **Authentication**: Laravel Sanctum SPA authentication
- **Authorization**: Policy-based access control
- **Input Validation**: Comprehensive form request validation
- **CSRF Protection**: Built-in Laravel CSRF protection
- **XSS Prevention**: Input sanitization and output escaping
- **SQL Injection**: Eloquent ORM prevents SQL injection
- **Rate Limiting**: API rate limiting enabled
- **Secure Headers**: Security headers configured
