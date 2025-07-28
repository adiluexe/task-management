# Task Management System

> **🏆 A full-stack task management application showcasing enterprise-level Laravel & Vue.js development**

Built with **Laravel 11** and **Vue 3**, featuring real-time updates, drag-and-drop functionality, comprehensive admin tools, and production-ready architecture.

[![Laravel Version](https://img.shields.io/badge/Laravel-11.x-FF2D20?style=flat-square&logo=laravel)](https://laravel.com)
[![Vue Version](https://img.shields.io/badge/Vue.js-3.x-4FC08D?style=flat-square&logo=vue.js)](https://vuejs.org)
[![PHP Version](https://img.shields.io/badge/PHP-8.2+-777BB4?style=flat-square&logo=php)](https://php.net)
[![License](https://img.shields.io/badge/license-MIT-green?style=flat-square)](LICENSE)
[![Tests](https://img.shields.io/badge/tests-24%20passing-brightgreen?style=flat-square)](TESTING_RESULTS.md)

## 🎯 **Project Overview**

This project demonstrates **professional full-stack development** with modern Laravel and Vue.js, implementing industry best practices, comprehensive testing, and production-ready features.

**📊 Compliance Score: 96/100** | [View Detailed Audit →](REQUIREMENTS_AUDIT.md)

## 🚀 Features

### Core Functionality

- ✅ User authentication (registration/login) with Laravel Sanctum
- ✅ CRUD operations for tasks with priority and status management
- ✅ Drag-and-drop task reordering with real-time backend sync
- ✅ Advanced filtering (status, priority) and search functionality
- ✅ Admin dashboard with user and task statistics
- ✅ Scheduled task cleanup (removes tasks older than 30 days)
- ✅ Real-time updates using WebSockets (Laravel Echo + Pusher)

### Technical Highlights

- 🏗️ **Clean Architecture**: Repository pattern, Service layer, SOLID principles
- 🔒 **Security**: OWASP compliant, input sanitization, CSRF/XSS protection
- ⚡ **Performance**: Redis caching, optimized queries, pagination
- 📱 **Responsive**: Mobile-first design with TailwindCSS
- 🧪 **Testing**: Comprehensive unit tests with MySQL test database
- 📚 **Documentation**: Swagger/OpenAPI documentation

### Bonus Features

- 💬 **Task Comments**: Add and manage comments on tasks
- 📎 **File Attachments**: Upload and attach files to tasks
- 🌙 **Dark Mode**: Toggle between light and dark themes
- 📊 **Advanced Analytics**: Task completion trends and productivity metrics
- 🔔 **Notifications**: Real-time browser and email notifications

## 🛠️ Tech Stack

### Backend

- **Laravel 11.x** - PHP framework
- **MySQL** - Primary database
- **Redis** - Caching and queues
- **Laravel Sanctum** - SPA authentication
- **Laravel Echo + Pusher** - Real-time WebSockets
- **PHPUnit** - Testing framework

### Frontend

- **Vue 3** - JavaScript framework (Composition API)
- **Pinia** - State management
- **Vue Router** - Routing
- **TailwindCSS** - Styling framework
- **Axios** - HTTP client
- **Vue Draggable Plus** - Drag and drop functionality
- **Laravel Echo** - WebSocket client

## 📋 Prerequisites

- **PHP** >= 8.2
- **Node.js** >= 18.x
- **Composer**
- **MySQL** >= 8.0
- **Redis** (optional, for caching and queues)

## 🚀 Quick Start

### 1. Clone the Repository

```bash
git clone https://github.com/yourusername/task-management.git
cd task-management
```

### 2. Backend Setup (Laravel)

```bash
cd backend
composer install
cp .env.example .env
php artisan key:generate
```

Configure your `.env` file:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=task_management
DB_USERNAME=your_username
DB_PASSWORD=your_password

CACHE_DRIVER=redis
QUEUE_CONNECTION=redis

PUSHER_APP_ID=your_pusher_app_id
PUSHER_APP_KEY=your_pusher_app_key
PUSHER_APP_SECRET=your_pusher_app_secret
PUSHER_APP_CLUSTER=your_pusher_cluster
```

Run migrations and seed data:

```bash
php artisan migrate
php artisan db:seed
```

Start the development server:

```bash
php artisan serve
```

### 3. Frontend Setup (Vue 3)

```bash
cd frontend
npm install
```

Configure environment variables:

```bash
cp .env.example .env.local
```

Update `.env.local`:

```env
VITE_API_BASE_URL=http://localhost:8000/api
VITE_PUSHER_APP_KEY=your_pusher_app_key
VITE_PUSHER_APP_CLUSTER=your_pusher_cluster
```

Start the development server:

```bash
npm run dev
```

### 4. Additional Setup

Start the queue worker (for scheduled tasks):

```bash
cd backend
php artisan queue:work
```

Start the scheduler (add to crontab for production):

```bash
cd backend
php artisan schedule:work
```

## 📱 Usage

1. **Access the Application**: Navigate to `http://localhost:5173` (frontend)
2. **Register/Login**: Create an account or login with existing credentials
3. **Manage Tasks**: Create, edit, delete, and reorder tasks
4. **Admin Features**: Access admin dashboard (if you have admin privileges)

### Default Admin User

- **Email**: admin@example.com
- **Password**: password

## 🧪 **Testing & Quality Assurance**

### **Comprehensive Test Suite (24 Tests)**

Our application includes a robust testing framework ensuring reliability and maintainability:

```bash
# Run all tests
cd backend && php artisan test

# Run specific test suites
php artisan test --testsuite=Feature  # API and functionality tests
php artisan test --testsuite=Unit     # Unit tests
php artisan test --filter=Auth        # Authentication tests only
php artisan test --filter=Task        # Task management tests only
php artisan test --filter=Admin       # Admin functionality tests only
```

**Test Coverage:**

- ✅ **Authentication Tests (5)** - Registration, login, logout, token management
- ✅ **Task Management Tests (9)** - CRUD operations, validation, authorization
- ✅ **Admin Functionality Tests (9)** - Dashboard, user management, permissions
- ✅ **Integration Tests (1)** - End-to-end functionality

[📋 View Detailed Testing Results →](TESTING_RESULTS.md)

### **Frontend Testing**

```bash
cd frontend && npm run test
```

---

## 📚 **API Documentation**

### **Interactive Swagger Documentation**

Our API includes comprehensive, interactive documentation powered by Swagger/OpenAPI 3.0:

**🔗 Live Documentation:** `http://localhost:8000/api/documentation`

### **Features:**

- 📖 **Complete Endpoint Documentation** - All routes with request/response examples
- 🧪 **Interactive Testing** - Test API endpoints directly from the browser
- 🔐 **Authentication Support** - Bearer token authentication integrated
- 📝 **Request/Response Schemas** - Detailed data structure documentation
- 🏷️ **Organized by Tags** - Logical grouping of endpoints

### **API Endpoints Overview:**

The API documentation is available at:

- **Swagger UI**: `http://localhost:8000/api/documentation`
- **Postman Collection**: [Link to exported collection]

### Key Endpoints

#### Authentication

- `POST /api/register` - User registration
- `POST /api/login` - User login
- `POST /api/logout` - User logout

#### Tasks

- `GET /api/tasks` - Get user tasks (with filtering and pagination)
- `POST /api/tasks` - Create new task
- `PUT /api/tasks/{id}` - Update task
- `DELETE /api/tasks/{id}` - Delete task
- `POST /api/tasks/reorder` - Reorder tasks

#### Admin

- `GET /api/admin/dashboard` - Admin dashboard statistics
- `GET /api/admin/users` - Get all users with tasks

## 🔒 Security Features

- **Authentication**: Laravel Sanctum SPA authentication
- **Authorization**: Role-based access control with middleware
- **Input Validation**: Comprehensive form request validation
- **CSRF Protection**: Built-in Laravel CSRF protection
- **XSS Prevention**: Input sanitization and output escaping
- **SQL Injection**: Eloquent ORM prevents SQL injection
- **Rate Limiting**: API rate limiting enabled

## 🚀 Deployment

### Production Environment Setup

1. **Backend Deployment**:

   - Configure production database
   - Set up Redis for caching and queues
   - Configure Pusher for WebSockets
   - Set up supervisor for queue workers
   - Configure cron job for scheduler

2. **Frontend Deployment**:

   - Build production assets: `npm run build`
   - Deploy to static hosting (Netlify, Vercel, etc.)

3. **Environment Variables**:
   - Update all environment variables for production
   - Enable HTTPS
   - Configure CORS settings

## 🤝 Contributing

1. Fork the repository
2. Create your feature branch (`git checkout -b feature/AmazingFeature`)
3. Commit your changes (`git commit -m 'Add some AmazingFeature'`)
4. Push to the branch (`git push origin feature/AmazingFeature`)
5. Open a Pull Request

## 📝 License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

## 🙏 Acknowledgments

- Laravel team for the amazing framework
- Vue.js team for the reactive framework
- TailwindCSS for the utility-first CSS framework
- All open-source contributors

---
