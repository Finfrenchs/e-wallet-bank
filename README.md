# EasyPay E-Wallet Backend

<p align="center">
  <img src="https://img.shields.io/badge/Laravel-10.x-FF2D20?style=for-the-badge&logo=laravel&logoColor=white" alt="Laravel">
  <img src="https://img.shields.io/badge/PHP-8.1+-777BB4?style=for-the-badge&logo=php&logoColor=white" alt="PHP">
  <img src="https://img.shields.io/badge/MySQL-8.0-4479A1?style=for-the-badge&logo=mysql&logoColor=white" alt="MySQL">
  <img src="https://img.shields.io/badge/JWT-Auth-000000?style=for-the-badge&logo=json-web-tokens&logoColor=white" alt="JWT">
</p>

## 📱 About EasyPay

**EasyPay** is a comprehensive digital wallet backend system built with Laravel, providing secure financial transaction services including top-ups, transfers, mobile data purchases, and tipping features. The system includes both a RESTful API for mobile applications and a modern admin dashboard for management.

### Key Highlights
- 🔐 Secure JWT-based authentication
- 💳 Integrated payment gateway (Midtrans)
- 📊 Real-time transaction monitoring
- 🎨 Modern admin dashboard with AdminLTE
- 📱 RESTful API for mobile apps
- 💰 Multi-payment method support
- 📈 Comprehensive analytics and reporting

---

## ✨ Features

### For Users (Mobile API)
- **User Management**
  - User registration and authentication
  - Profile management with KTP verification
  - Email validation
  - PIN-based transaction security

- **Wallet Operations**
  - Virtual wallet with card number
  - Balance inquiries
  - PIN management and updates
  - Transaction history

- **Transactions**
  - **Top-up**: Add balance via multiple payment methods (BNI, BCA, BRI)
  - **Transfer**: Send money to other users via username or card number
  - **Data Plans**: Purchase mobile internet packages
  - **Tips**: Send tips to other users
  - Real-time transaction status tracking

- **Payment Methods**
  - Multiple bank virtual accounts (BNI, BCA, BRI)
  - Secure payment processing via Midtrans
  - Payment webhook integration

### For Administrators (Web Dashboard)
- **Dashboard Analytics**
  - Total users statistics
  - Total transactions count
  - Revenue tracking
  - Pending transactions monitoring
  - Interactive charts (Pie, Bar, Line)
  - Monthly revenue trends
  - Transaction categorization by type and status

- **Transaction Management**
  - Complete transaction listing
  - Advanced filtering and search
  - Transaction detail viewing
  - Status-based categorization
  - Export to Excel and PDF
  - Print functionality

- **Modern UI/UX**
  - Responsive design
  - AdminLTE 3.x integration
  - Real-time date/time display
  - Notification system
  - User-friendly navigation

---

## 🛠️ Technology Stack

### Backend
- **Framework**: Laravel 10.x
- **Language**: PHP 8.1+
- **Database**: MySQL 8.0
- **Authentication**: JWT (tymon/jwt-auth)

### Admin Dashboard
- **Template**: AdminLTE 3.x
- **CSS Framework**: Bootstrap 4
- **JavaScript**: jQuery
- **Charts**: Chart.js
- **Tables**: DataTables

### Third-Party Services
- **Payment Gateway**: Midtrans
- **Payment Methods**: Virtual Account (BNI, BCA, BRI)

### Development Tools
- Composer for dependency management
- Artisan CLI for Laravel commands
- Database migrations and seeders

---

## 📋 Prerequisites

Before you begin, ensure you have the following installed:
- PHP >= 8.1
- Composer
- MySQL >= 8.0
- Node.js & NPM (for frontend assets)
- Git

---

## 🚀 Installation

### 1. Clone the Repository
```bash
git clone <repository-url>
cd e-wallet-bank
```

### 2. Install Dependencies
```bash
# Install PHP dependencies
composer install

# Install Node dependencies (if needed)
npm install
```

### 3. Environment Configuration
```bash
# Copy environment file
cp .env.example .env

# Generate application key
php artisan key:generate

# Generate JWT secret
php artisan jwt:secret
```

### 4. Configure Environment Variables
Edit `.env` file with your configuration:

```env
APP_NAME=EasyPay
APP_ENV=local
APP_KEY=base64:...
APP_DEBUG=true
APP_URL=http://localhost:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=easypay_db
DB_USERNAME=root
DB_PASSWORD=

# Midtrans Configuration
MIDTRANS_SERVER_KEY=your_midtrans_server_key
MIDTRANS_CLIENT_KEY=your_midtrans_client_key
MIDTRANS_IS_PRODUCTION=false
MIDTRANS_IS_SANITIZED=true
MIDTRANS_IS_3DS=true

# JWT Configuration
JWT_SECRET=your_jwt_secret
JWT_TTL=60
```

### 5. Database Setup
```bash
# Create database
mysql -u root -p
CREATE DATABASE easypay_db;
exit;

# Run migrations
php artisan migrate

# Seed database (optional)
php artisan db:seed
```

### 6. Storage Link
```bash
# Create symbolic link for storage
php artisan storage:link
```

### 7. Start Development Server
```bash
php artisan serve
```

The application will be available at `http://localhost:8000`

---

## 📚 Database Schema

### Main Tables

#### users
- id, name, email, username, password
- profile_picture, ktp, verified
- timestamps

#### wallets
- id, user_id, card_number, balance, pin
- timestamps

#### transactions
- id, user_id, transaction_type_id, payment_method_id
- product_id, amount, transaction_code
- description, status
- timestamps

#### transaction_types
- id, name, code, action, thumbnail
- timestamps

#### payment_methods
- id, name, code, status, thumbnail
- timestamps, soft deletes

#### transfer_histories
- id, sender_id, receiver_id, transaction_code
- timestamps

#### data_plans
- id, operator_card_id, name, price, thumbnail
- timestamps

---

## 🔌 API Documentation

### Base URL
```
http://localhost:8000/api
```

### Authentication
All protected endpoints require JWT token in header:
```
Authorization: Bearer {token}
```

### Public Endpoints

#### Register
```http
POST /api/register
Content-Type: application/json

{
  "name": "John Doe",
  "email": "john@example.com",
  "password": "password123",
  "pin": "123456"
}
```

#### Login
```http
POST /api/login
Content-Type: application/json

{
  "email": "john@example.com",
  "password": "password123"
}
```

#### Check Email Existence
```http
POST /api/is-email-exist
Content-Type: application/json

{
  "email": "john@example.com"
}
```

### Protected Endpoints

#### Get User Profile
```http
GET /api/users
Authorization: Bearer {token}
```

#### Update User Profile
```http
PUT /api/users
Authorization: Bearer {token}
Content-Type: application/json

{
  "name": "John Doe Updated",
  "username": "johndoe",
  "email": "john@example.com"
}
```

#### Get User by Username
```http
GET /api/users/{username}
Authorization: Bearer {token}
```

#### Get Wallet
```http
GET /api/wallets
Authorization: Bearer {token}
```

#### Update PIN
```http
PUT /api/wallets
Authorization: Bearer {token}
Content-Type: application/json

{
  "old_pin": "123456",
  "new_pin": "654321"
}
```

#### Top-up
```http
POST /api/top_ups
Authorization: Bearer {token}
Content-Type: application/json

{
  "amount": 100000,
  "pin": "123456",
  "payment_method_code": "bni_va"
}
```

#### Transfer
```http
POST /api/transfers
Authorization: Bearer {token}
Content-Type: application/json

{
  "amount": 50000,
  "pin": "123456",
  "send_to": "username_or_card_number"
}
```

#### Purchase Data Plan
```http
POST /api/data_plans
Authorization: Bearer {token}
Content-Type: application/json

{
  "data_plan_id": 1,
  "phone_number": "081234567890",
  "pin": "123456"
}
```

#### Get Transactions
```http
GET /api/transactions?limit=10
Authorization: Bearer {token}
```

#### Get Payment Methods
```http
GET /api/payment_methods
Authorization: Bearer {token}
```

#### Get Operator Cards
```http
GET /api/operator_cards
Authorization: Bearer {token}
```

#### Get Transfer History
```http
GET /api/transfer_histories
Authorization: Bearer {token}
```

#### Get Tips
```http
GET /api/tips
Authorization: Bearer {token}
```

#### Logout
```http
POST /api/logout
Authorization: Bearer {token}
```

### Webhook Endpoint

#### Payment Webhook (Midtrans)
```http
POST /api/webhooks
Content-Type: application/json

{
  "order_id": "TOPUP-ABC123",
  "transaction_status": "settlement",
  "gross_amount": "100000"
}
```

---

## 🎨 Admin Dashboard

### Access
```
URL: http://localhost:8000/admin/login
Default Email: admin@easypay.com
Default Password: password
```

### Features

#### Dashboard Page
- **Statistics Cards**: Total Users, Transactions, Revenue, Pending Transactions
- **Charts**:
  - Transactions by Status (Pie Chart)
  - Transactions by Type (Bar Chart)
  - Monthly Revenue (Line Chart)
- **Recent Transactions**: Last 10 transactions with quick view
- All data updates in real-time

#### Transaction Management
- **Statistics**: Success, Pending, Failed, Total counts
- **Advanced Table**:
  - DataTables integration
  - Search and filtering
  - Sorting by any column
  - Pagination
  - Export to Excel/PDF
  - Print functionality
- **Detail View**: Modal with complete transaction information
- **Responsive Design**: Works on all devices

#### Navigation
- **Sidebar**:
  - Dashboard
  - Transactions
  - Settings
  - Logout
- **Header**:
  - Current date/time
  - Notifications
  - User profile dropdown
  - Fullscreen toggle

---

## 💳 Payment Integration

### Midtrans Configuration

1. **Register**: Sign up at [Midtrans](https://midtrans.com/)

2. **Get Credentials**:
   - Server Key
   - Client Key

3. **Configure Environment**:
```env
MIDTRANS_SERVER_KEY=your_server_key
MIDTRANS_CLIENT_KEY=your_client_key
MIDTRANS_IS_PRODUCTION=false
```

4. **Webhook Setup**:
   - Set notification URL to: `https://yourdomain.com/api/webhooks`
   - Configure in Midtrans Dashboard > Settings > Configuration

### Supported Payment Methods
- BNI Virtual Account
- BCA Virtual Account
- BRI Virtual Account

### Transaction Flow
1. User initiates top-up request
2. System creates transaction with "pending" status
3. Midtrans generates payment URL
4. User completes payment
5. Webhook updates transaction to "success"
6. User wallet balance updated

---

## 🧪 Testing

### Run Tests
```bash
# Run all tests
php artisan test

# Run specific test
php artisan test --filter=TransactionTest

# Run with coverage
php artisan test --coverage
```

### Test Categories
- Feature Tests
- Unit Tests
- API Tests

---

## 📦 File Structure

```
e-wallet-bank/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Admin/
│   │   │   │   ├── AuthController.php
│   │   │   │   ├── DashboardController.php
│   │   │   │   └── TransactionController.php
│   │   │   └── Api/
│   │   │       ├── AuthController.php
│   │   │       ├── TopUpController.php
│   │   │       ├── TransferController.php
│   │   │       ├── DataPlanController.php
│   │   │       ├── TransactionController.php
│   │   │       ├── UserController.php
│   │   │       ├── WalletController.php
│   │   │       └── WebhookController.php
│   │   └── Middleware/
│   │       └── JwtMiddleware.php
│   └── Models/
│       ├── User.php
│       ├── Wallet.php
│       ├── Transaction.php
│       ├── TransactionType.php
│       ├── PaymentMethod.php
│       ├── DataPlan.php
│       └── TransferHistory.php
├── config/
│   ├── auth.php
│   ├── jwt.php
│   └── midtrans.php
├── database/
│   ├── migrations/
│   └── seeders/
├── public/
│   ├── AdminLTE/
│   ├── banks/
│   └── transaction-type/
├── resources/
│   └── views/
│       ├── layouts/
│       │   ├── header.blade.php
│       │   ├── sidebar.blade.php
│       │   └── footer.blade.php
│       ├── base.blade.php
│       ├── login.blade.php
│       ├── dashboard.blade.php
│       └── transaction.blade.php
├── routes/
│   ├── api.php
│   └── web.php
└── tests/
    ├── Feature/
    └── Unit/
```

---

## 🔒 Security

### Implemented Security Features
- JWT authentication for API
- PIN verification for sensitive transactions
- Password hashing with bcrypt
- SQL injection prevention (Eloquent ORM)
- XSS protection
- CSRF protection for web routes
- Middleware authentication
- Database transaction rollback on errors
- Input validation and sanitization

### Best Practices
- Always validate user input
- Use HTTPS in production
- Keep dependencies updated
- Regular security audits
- Proper error handling
- Secure environment variables

---

## 🚢 Deployment

### Production Checklist

1. **Environment**
```bash
APP_ENV=production
APP_DEBUG=false
APP_URL=https://yourdomain.com
```

2. **Optimize Application**
```bash
composer install --optimize-autoloader --no-dev
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

3. **Database**
```bash
php artisan migrate --force
```

4. **Permissions**
```bash
chmod -R 755 storage
chmod -R 755 bootstrap/cache
```

5. **Supervisor Configuration** (for queues)
```ini
[program:easypay-worker]
process_name=%(program_name)s_%(process_num)02d
command=php /path/to/artisan queue:work
autostart=true
autorestart=true
user=www-data
numprocs=4
redirect_stderr=true
stdout_logfile=/path/to/storage/logs/worker.log
```

### Server Requirements
- PHP >= 8.1
- MySQL >= 8.0
- Nginx or Apache
- Composer
- SSL Certificate (Let's Encrypt recommended)

---

## 🤝 Contributing

We welcome contributions! Please follow these steps:

1. Fork the repository
2. Create your feature branch (`git checkout -b feature/AmazingFeature`)
3. Commit your changes (`git commit -m 'Add some AmazingFeature'`)
4. Push to the branch (`git push origin feature/AmazingFeature`)
5. Open a Pull Request

### Coding Standards
- Follow PSR-12 coding standards
- Write meaningful commit messages
- Add tests for new features
- Update documentation

---

## 📝 Changelog

### Version 1.0.0 (2025-12-11)
- ✨ Initial release
- 🔐 JWT authentication
- 💳 Midtrans payment integration
- 📊 Admin dashboard with analytics
- 💰 Top-up, Transfer, Data Plan features
- 📱 Complete RESTful API
- 🎨 Modern UI with AdminLTE

---

## 🐛 Known Issues

None at the moment. Please report issues at [GitHub Issues](https://github.com/yourusername/easypay/issues)

---

## 📄 License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

---

## 👥 Authors

- **Your Name** - *Initial work* - [YourGitHub](https://github.com/yourusername)

---

## 🙏 Acknowledgments

- [Laravel](https://laravel.com) - The PHP Framework
- [AdminLTE](https://adminlte.io) - Admin Dashboard Template
- [Midtrans](https://midtrans.com) - Payment Gateway
- [JWT Auth](https://github.com/tymondesigns/jwt-auth) - JWT Authentication
- All contributors who helped with this project

---

## 📧 Support

For support, email support@easypay.com or join our Slack channel.

---

## 🔗 Links

- **Documentation**: [Full Documentation](https://docs.easypay.com)
- **API Reference**: [API Docs](https://api.easypay.com/docs)
- **Admin Demo**: [Demo Dashboard](https://demo.easypay.com/admin)
- **Website**: [easypay.com](https://easypay.com)

---

<p align="center">Made with ❤️ by EasyPay Team</p>
