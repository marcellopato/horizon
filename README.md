# 🎥 Hireflix Clone - Video Interview Platform

A complete one-way video interview platform built with Laravel, TailwindCSS, and WebRTC. This application enables companies to create video interview processes and allows candidates to record responses remotely.

## ✅ Project Status: COMPLETE

All core features have been successfully implemented and tested:

- [x] **Phase 1:** Environment Setup (Docker + Laravel Sail + TailwindCSS v4)
- [x] **Phase 2:** Role-Based Authentication System
- [x] **Phase 3:** Interview Management System
- [x] **Phase 4:** Video Recording & Upload System
- [x] **Phase 5:** Review & Scoring System
- [x] **Phase 6:** UI/UX Polish & Testing

## 🏗️ Technology Stack

- **Backend:** Laravel 11.x with PHP 8.4
- **Frontend:** TailwindCSS v4 with Vite HMR
- **Database:** MySQL 8.0
- **Development:** Docker via Laravel Sail
- **Authentication:** Laravel Breeze with custom role system

## 🚀 Features Implemented

### 🎯 Core Functionality

#### For Admins/Reviewers
- ✅ Create and manage video interviews with descriptions
- ✅ Add multiple questions with individual time limits
- ✅ View all interviews in the system
- ✅ Review candidate video submissions
- ✅ Add scores and comments to candidate responses
- ✅ Complete CRUD operations for interviews and questions

#### For Candidates
- ✅ Browse and view available interviews
- ✅ System checks (camera/microphone permissions)
- ✅ Record video responses using WebRTC
- ✅ Timed recording sessions per question
- ✅ Automatic video upload and submission
- ✅ Real-time recording controls (start/stop/restart)

### 🔐 Authentication & Security
- **Role-based access control** with 3 user types:
  - **Admin:** Full system management
  - **Reviewer:** Interview creation and candidate evaluation  
  - **Candidate:** Interview participation and video recording
- **Laravel Gates** for fine-grained authorization
- **Custom middleware** for role-based route protection
- **Secure file uploads** with validation

### 🎬 Video Recording System
- **WebRTC Integration** for browser-based recording
- **Real-time camera preview** during recording
- **Permission handling** for camera/microphone access
- **Timed recording sessions** with visual countdown
- **Automatic file upload** using FormData
- **Video storage** in Laravel's filesystem

## 📦 Installation & Setup

### Prerequisites
- Docker Desktop
- Git

### Quick Start

1. **Clone the repository:**
   ```bash
   git clone https://github.com/marcellopato/horizon.git
   cd horizon
   ```

2. **Start the development environment:**
   ```bash
   docker-compose up -d
   ```

3. **Install dependencies and setup database:**
   ```bash
   docker-compose exec laravel.test composer install
   docker-compose exec laravel.test php artisan migrate
   docker-compose exec laravel.test php artisan db:seed
   ```

4. **Start the development server:**
   ```bash
   docker-compose exec laravel.test npm run dev
   ```

5. **Access the application:**
   - Frontend: http://localhost
   - Development server: http://localhost:5173

## 👤 Test Accounts

Use these pre-seeded accounts for testing:

| Role | Email | Password | Access Level |
|------|-------|----------|-------------|
| **Admin** | admin@horizon.com | password | Full system access |
| **Reviewer** | reviewer@horizon.com | password | Interview management |
| **Candidate** | candidate@horizon.com | password | Interview participation |

## 🛠️ Development Commands

### User Management
```bash
# Create a new admin user securely
docker-compose exec laravel.test php artisan admin:create "Name" "email@domain.com" "password"

# Run database migrations
docker-compose exec laravel.test php artisan migrate

# Seed test data
docker-compose exec laravel.test php artisan db:seed
```

### Development
```bash
# Start development server with HMR
docker-compose exec laravel.test npm run dev

# Build for production
docker-compose exec laravel.test npm run build

# Run tests
docker-compose exec laravel.test php artisan test
```

## 📁 Project Structure

```
horizon/
├── app/
│   ├── Http/
│   │   ├── Controllers/Auth/     # Authentication controllers
│   │   └── Middleware/           # Role-based access control
│   ├── Models/                   # Eloquent models with role helpers
│   └── Console/Commands/         # Custom artisan commands
├── database/
│   ├── migrations/               # Database schema
│   └── seeders/                  # Test data seeders
├── resources/
│   ├── views/                    # Blade templates with role-based UI
│   └── css/                      # TailwindCSS v4 configuration
└── routes/                       # Application routes with middleware
```

## 🔄 Git Workflow

The project follows a feature-branch workflow:
- `main` - Production-ready code
- `feature/*` - Feature development branches
- All changes go through Pull Requests

## 🎯 Upcoming Features (Phase 3+)

- **Interview Management System**
  - Create interviews with multiple questions
  - Set time limits and recording parameters
  - Manage interview visibility and access

- **Video Recording System**
  - Browser-based video recording
  - File upload with progress tracking
  - Video compression and optimization

- **Review & Scoring System**
  - Candidate response evaluation
  - Scoring rubrics and comments
  - Interview analytics and reports

## 📋 Development Guidelines

- **Security First:** All user inputs validated and sanitized
- **Role-Based Access:** Every feature respects user permissions
- **Mobile-Friendly:** Responsive design with TailwindCSS
- **Modern Stack:** Laravel 11 + TailwindCSS v4 + Vite

## 🤝 Contributing

This is a development challenge project for Horizon Sphere Equity. For questions or feedback, please contact the development team.

## 📄 License

This project is developed as part of a technical assessment and follows standard software development practices.

---

**Development Timeline:** 24-hour challenge project
**Started:** September 12, 2025
**Current Phase:** Authentication & Security ✅
