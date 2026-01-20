# E-Commerce Inventory Management System Using Laravel

A comprehensive, full-stack e-commerce inventory management system built with Laravel 11, featuring a multi-application architecture with an API service, backend dashboard, and frontend store.

![Laravel](https://img.shields.io/badge/Laravel-11.31-red?style=flat-square&logo=laravel)
![PHP](https://img.shields.io/badge/PHP-8.2+-blue?style=flat-square&logo=php)
![License](https://img.shields.io/badge/License-MIT-green?style=flat-square)

## 📋 Table of Contents

- [Project Overview](#project-overview)
- [Architecture](#architecture)
- [Features](#features)
- [Tech Stack](#tech-stack)
- [Project Structure](#project-structure)
- [Installation](#installation)
- [Configuration](#configuration)
- [Running the Applications](#running-the-applications)
- [Database](#database)
- [API Documentation](#api-documentation)
- [Testing](#testing)
- [Contributing](#contributing)
- [License](#license)

## 🎯 Project Overview

This is a complete e-commerce inventory management solution designed for small to medium-sized businesses. It allows companies to manage their products, inventory, orders, and customer relationships through an intuitive interface while providing a customer-facing storefront.

The system is built with a modern microservices-inspired architecture using three separate Laravel applications:

1. **API Application** - RESTful API backend with authentication and business logic
2. **Backend Application** - Admin dashboard for managing products, inventory, and orders
3. **Frontend Application** - Customer-facing e-commerce store

## 🏗️ Architecture

```
┌─────────────────────────────────────────────────────────┐
│         E-Commerce Inventory Management System          │
└──────────────────┬──────────────────────────────────────┘
                   │
       ┌───────────┼───────────┐
       │           │           │
   ┌───▼────┐ ┌───▼────┐ ┌───▼────┐
   │   API   │ │ Backend│ │Frontend│
   │  App    │ │  App   │ │  App   │
   └────┬────┘ └────┬────┘ └────┬───┘
        │           │           │
        └───────────┼───────────┘
                    │
          ┌─────────▼──────────┐
          │   Shared Storage   │
          │   (Files & Images) │
          └────────────────────┘
```

### Key Components

- **Shared Storage**: Central repository for uploaded files, product images, and assets
- **Authentication**: Laravel Sanctum for API token-based authentication
- **Database**: Single shared database across all applications
- **Cache**: Redis support for caching and sessions

## ✨ Features

### Core Features
- **Multi-tenant Support**: Different companies with isolated inventory and orders
- **Role-based Access Control**: Platform owners, company admins, company staff, and customers
- **Product Management**: Create, update, and manage product catalog
- **Inventory Management**: Real-time stock tracking and updates
- **Order Management**: Complete order lifecycle management
- **Customer Management**: Customer profiles and order history
- **Subscription Plans**: Support for subscription-based models

### API Features
- RESTful endpoints for all operations
- Token-based authentication with Sanctum
- Request validation and error handling
- Comprehensive audit logging
- Rate limiting and security headers

### Admin Dashboard
- User-friendly interface for inventory management
- Real-time stock level monitoring
- Order processing and fulfillment
- Customer and company management
- Sales analytics and reporting

### Customer Store
- Browse products by category
- Shopping cart functionality
- Secure checkout process
- Order tracking
- Account management

## 🛠️ Tech Stack

### Backend
- **Framework**: Laravel 11.31
- **PHP Version**: 8.2+
- **Database**: MySQL/PostgreSQL
- **Cache**: Redis (via Predis)
- **API Security**: Laravel Sanctum
- **Testing**: PHPUnit 11.0+

### Frontend
- **Build Tool**: Vite
- **Styling**: Tailwind CSS
- **CSS Processing**: PostCSS
- **Package Manager**: NPM/Composer

### Development Tools
- **Code Quality**: Laravel Pint
- **Error Handling**: Nunomaduro Collision
- **Database Seeding**: Faker & Factory patterns
- **Testing**: PHPUnit with Mockery

## 📁 Project Structure

```
.
├── api-app/                    # RESTful API Application
│   ├── app/
│   │   ├── Http/              # Controllers, Requests, Resources
│   │   ├── Models/            # Eloquent Models
│   │   ├── Services/          # Business Logic
│   │   └── Providers/         # Service Providers
│   ├── routes/
│   │   └── api.php            # API Routes
│   ├── database/
│   │   ├── migrations/        # Database Migrations
│   │   ├── factories/         # Model Factories
│   │   └── seeders/          # Database Seeders
│   └── tests/                 # API Tests
│
├── back-app/                   # Admin Backend Application
│   ├── app/
│   │   ├── Http/              # Controllers, Middleware
│   │   ├── Models/            # Eloquent Models
│   │   └── View/              # View Composers
│   ├── resources/
│   │   ├── css/               # Styles
│   │   ├── js/                # JavaScript
│   │   └── views/             # Blade Templates
│   ├── routes/
│   │   ├── web.php            # Web Routes
│   │   └── auth.php           # Auth Routes
│   └── tests/                 # Feature Tests
│
├── front-app/                  # Customer Frontend Application
│   ├── app/
│   ├── resources/
│   │   ├── css/
│   │   ├── js/
│   │   └── views/             # Blade Templates
│   ├── routes/
│   │   └── web.php
│   └── vite-project/          # Vite Configuration
│
├── shared_storage/            # Shared Files & Assets
│   └── public/                # Publicly Accessible Files
│
└── README.md                  # This File
```

### Core Models

| Model | Purpose |
|-------|---------|
| **User** | Base user model for authentication |
| **Company** | Merchant/seller company entities |
| **CompanyAdmin** | Admin users for companies |
| **CompanyStaff** | Staff members managing inventory |
| **Customer** | End customers |
| **Product** | Product catalog |
| **Stock** | Inventory levels per product |
| **Order** | Customer orders |
| **OrderItem** | Individual items in an order |
| **Category** | Product categories |
| **PlanSubscription** | Subscription plan records |
| **AuditLog** | System audit trail |

## 🚀 Installation

### Prerequisites
- PHP 8.2 or higher
- Composer
- Node.js (14+) and npm
- MySQL or PostgreSQL
- Redis (optional, for caching)

### Step 1: Clone the Repository

```bash
git clone https://github.com/longmanngithub/E-Commerce-Inventory-Management-System-Using-Laravel.git
cd E-Commerce-Inventory-Management-System-Using-Laravel
```

### Step 2: Install Dependencies

Install PHP and Node dependencies for each application:

```bash
# API Application
cd api-app
composer install
npm install
cd ..

# Backend Application
cd back-app
composer install
npm install
cd ..

# Frontend Application
cd front-app
composer install
npm install
cd ..
```

### Step 3: Environment Configuration

Copy the `.env.example` file to `.env` in each application directory:

```bash
# API App
cd api-app
cp .env.example .env
php artisan key:generate

# Backend App
cd ../back-app
cp .env.example .env
php artisan key:generate

# Frontend App
cd ../front-app
cp .env.example .env
php artisan key:generate
```

## ⚙️ Configuration

### Database Setup

Update the `.env` file in each application with your database credentials:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=ecommerce_inventory
DB_USERNAME=root
DB_PASSWORD=
```

**Important**: All three applications share the same database.

### Run Migrations

```bash
# From the api-app directory (creates all necessary tables)
php artisan migrate

# You can also seed the database with sample data
php artisan db:seed
```

### Storage Configuration

Configure the shared storage location in `config/filesystems.php` for all applications to use the same storage path.

### Cache Configuration (Optional)

If using Redis for caching:

```env
CACHE_DRIVER=redis
REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379
```

## 🏃 Running the Applications

### Development Mode

Each application runs on a different port:

**Terminal 1 - API Server (Port 8000)**
```bash
cd api-app
php artisan serve
```

**Terminal 2 - Backend Dashboard (Port 8001)**
```bash
cd back-app
php artisan serve --port=8001
```

**Terminal 3 - Frontend Store (Port 8002)**
```bash
cd front-app
php artisan serve --port=8002
```

**Terminal 4 - Vite Dev Server (for hot reload)**
```bash
cd front-app
npm run dev
```

### Production Deployment

```bash
# Build assets for production
npm run build

# Clear caches
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Optimize for production
php artisan optimize
```

## 🗄️ Database

### Database Diagram Overview

**Users & Authentication**
- `users` - All system users
- `personal_access_tokens` - Sanctum API tokens

**Company Management**
- `companies` - Merchant companies
- `company_admins` - Company administrators
- `company_staff` - Company employees

**Product Catalog**
- `products` - Product definitions
- `categories` - Product categories
- `stocks` - Inventory levels

**Orders & Transactions**
- `orders` - Customer orders
- `order_items` - Order line items
- `subscription_orders` - Subscription purchases

**System**
- `audit_logs` - Activity tracking
- `user_invitations` - Pending user invites
- `plan_subscriptions` - Subscription records

### Key Relationships

```
Company
  ├── CompanyAdmin (1:N)
  ├── CompanyStaff (1:N)
  ├── Product (1:N)
  └── Order (1:N)

Product
  ├── Stock (1:1)
  ├── Category (N:1)
  └── OrderItem (1:N)

Order
  ├── Customer (N:1)
  ├── OrderItem (1:N)
  └── SubscriptionOrder (1:N)
```

## 📚 API Documentation

### Base URL
```
http://localhost:8000/api
```

### Authentication
All protected API endpoints require a Bearer token:
```
Authorization: Bearer YOUR_ACCESS_TOKEN
```

### Key Endpoints

#### Authentication
- `POST /api/login` - User login
- `POST /api/logout` - User logout
- `POST /api/register` - User registration

#### Products
- `GET /api/products` - List all products
- `GET /api/products/{id}` - Get product details
- `POST /api/products` - Create new product (Admin)
- `PUT /api/products/{id}` - Update product (Admin)
- `DELETE /api/products/{id}` - Delete product (Admin)

#### Orders
- `GET /api/orders` - List user's orders
- `POST /api/orders` - Create new order
- `GET /api/orders/{id}` - Get order details
- `PUT /api/orders/{id}` - Update order status (Admin)

#### Inventory
- `GET /api/stocks` - Get stock levels
- `PUT /api/stocks/{id}` - Update stock quantity (Admin)

For complete API documentation, refer to the API routes in `api-app/routes/api.php`.

## 🧪 Testing

### Run Tests

```bash
# API Application
cd api-app
php artisan test

# Backend Application
cd ../back-app
php artisan test

# Frontend Application
cd ../front-app
php artisan test
```

### Test Structure
- Unit Tests: `tests/Unit/`
- Feature Tests: `tests/Feature/`
- Test Factories: `database/factories/`
- Test Seeders: `database/seeders/`

## 📝 Database Migrations

Migrations are automatically run during setup. To create new migrations:

```bash
php artisan make:migration create_table_name
php artisan migrate
```

## 🔐 Security Features

- **Token-based Authentication**: Sanctum for API security
- **CSRF Protection**: Enabled for web routes
- **Password Hashing**: BCrypt hashing for passwords
- **Rate Limiting**: Built-in rate limiting on API
- **Input Validation**: Request validation on all inputs
- **Audit Logging**: Track all system modifications
- **Role-Based Access Control**: Fine-grained permissions

## 🤝 Contributing

Contributions are welcome! Please follow these guidelines:

1. Fork the repository
2. Create a feature branch: `git checkout -b feature/YourFeature`
3. Commit changes: `git commit -m 'Add YourFeature'`
4. Push to branch: `git push origin feature/YourFeature`
5. Submit a Pull Request

## 📄 License

This project is licensed under the MIT License - see the LICENSE file for details.

## 👨‍💻 Author

**Longman** - [GitHub Profile](https://github.com/longmanngithub)

## 🆘 Support

For issues, questions, or suggestions:
- Open an issue on [GitHub Issues](https://github.com/longmanngithub/E-Commerce-Inventory-Management-System-Using-Laravel/issues)
- Check existing documentation in each application's README.md
- Review Laravel documentation at [laravel.com](https://laravel.com/docs)

## 📦 Version History

- **v1.0.0** - Initial release with API, Backend, and Frontend applications

---

**Made with ❤️ using Laravel**
