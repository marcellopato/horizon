# 🎥 Horizon - Hireflix Clone

A comprehensive video interview platform built with Laravel, TailwindCSS, and modern web technologies. This application allows companies to create video interview processes and candidates to record responses remotely.

## 🚀 Project Status

**Current Progress: Phase 2 - Authentication Complete ✅**

- [x] **Phase 1:** Environment Setup (Docker + Laravel Sail + TailwindCSS v4)
- [x] **Phase 2:** Role-Based Authentication System
- [ ] **Phase 3:** Interview Management System
- [ ] **Phase 4:** Video Recording & Upload
- [ ] **Phase 5:** Review & Scoring System

## 🏗️ Technology Stack

- **Backend:** Laravel 11.x with PHP 8.4
- **Frontend:** TailwindCSS v4 with Vite HMR
- **Database:** MySQL 8.0
- **Development:** Docker via Laravel Sail
- **Authentication:** Laravel Breeze with custom role system

## ✨ Features Implemented

### 🔐 Authentication & Authorization
- **Role-based access control** with 3 user types:
  - **Admin:** Full system management
  - **Reviewer:** Interview creation and candidate evaluation
  - **Candidate:** Interview participation and video responses
- **Secure registration** (public registration limited to reviewer/candidate roles)
- **Laravel Breeze integration** with custom role middleware
- **Admin user management** via CLI commands

### 🛡️ Security Features
- **Protected admin creation** - No public admin registration
- **Role-based middleware** for route protection
- **Secure password hashing** and email verification
- **Command-line admin creation** for secure onboarding

## 🎯 Core Functionality (Planned)

### For Admins/Reviewers:
- [ ] Create and manage video interviews
- [ ] Set up interview questions and time limits  
- [ ] Review candidate submissions with scoring
- [ ] Generate interview reports and analytics

### For Candidates:
- [ ] Browse available interviews
- [ ] Record video responses to questions
- [ ] Track submission status and feedback
- [ ] Receive interview results and scores

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
| **Admin** | admin@horizon.test | admin123 | Full system access |
| **Reviewer** | reviewer@horizon.test | reviewer123 | Interview management |
| **Candidate** | candidate@horizon.test | candidate123 | Interview participation |

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
