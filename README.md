# Mortgage Loan Calculator

A comprehensive mortgage loan calculator web application built with **Laravel 11** backend API and **React.js TypeScript** frontend. This application allows users to calculate mortgage payments, generate detailed amortization schedules, and analyze the impact of extra payments on loan terms.

## Features

- **Loan Calculation**: Calculate monthly payments using standard amortization formula
- **Amortization Schedules**: Generate detailed payment breakdowns for the entire loan term
- **Extra Payment Analysis**: See how extra payments reduce loan term and save interest
- **Effective Interest Rate**: Calculate true cost of borrowing with extra payments
- **Modern UI**: Professional React.js interface with responsive design
- **Input Validation**: Comprehensive validation with detailed error messages
- **Test Coverage**: 82 unit and feature tests with full coverage
- **Enterprise Architecture**: Domain-Driven Design with SOLID principles

## Quick Start

### Prerequisites

- **Docker & Docker Compose** (recommended)
- **PHP 8.2+** (if running without Docker)
- **Node.js 18+** (for frontend development)
- **Composer** (PHP dependency manager)

### Option 1: Docker Setup (Recommended)

1. **Clone the repository**
   ```bash
   git clone <repository-url>
   cd tech-exam
   ```

2. **Start the application**
   ```bash
   docker compose up -d
   ```

3. **Install dependencies and setup database**
   ```bash
   # Install PHP dependencies
   docker compose exec app composer install
   
   # Run database migrations
   docker compose exec app php artisan migrate
   
   # Generate application key
   docker compose exec app php artisan key:generate
   
   # Seed sample loan data (optional)
   docker compose exec app php artisan db:seed
   ```

4. **Setup frontend**
   ```bash
   cd frontend
   npm install
   npm start
   ```

5. **Access the application**
   - **Frontend**: http://localhost:3000
   - **Backend API**: http://localhost:8080/api

### Option 2: Local Development Setup

1. **Backend Setup**
   ```bash
   # Install dependencies
   composer install
   
   # Setup environment
   cp .env.example .env
   php artisan key:generate
   
   # Configure database in .env file
   # DB_CONNECTION=sqlite
   # DB_DATABASE=/absolute/path/to/database.sqlite
   
   # Run migrations
   php artisan migrate
   
   # Seed sample data (optional)
   php artisan db:seed
   
   # Start Laravel server
   php artisan serve --port=8080
   ```

2. **Frontend Setup**
   ```bash
   cd frontend
   npm install
   npm start
   ```

## Testing

### Run All Tests
```bash
# Using Docker
docker compose exec app php artisan test

# Local development
php artisan test
```

### Test Categories
```bash
# Unit tests only
docker compose exec app php artisan test tests/Unit/

# Feature/API tests only  
docker compose exec app php artisan test tests/Feature/

# Specific test file
docker compose exec app php artisan test tests/Feature/LoanCalculationTest.php
```

### Test Coverage
- **82 total tests** with 100% passing rate
- **Unit tests**: Domain logic, value objects, services, entities
- **Feature tests**: API endpoints, integration testing, database operations
- **Validation tests**: Input validation and comprehensive error handling

## API Documentation & Sample Input/Output

### Calculate Loan
**POST** `/api/loans/calculate`

**Sample Input:**
```json
{
  "loan_amount": 250000,
  "annual_interest_rate": 4.5,
  "loan_term_years": 25,
  "monthly_extra_payment": 500
}
```

**Sample Output:**
```json
{
  "success": true,
  "loan_id": 49,
  "loan_details": {
    "id": 49,
    "loan_amount": 250000,
    "annual_interest_rate": 4.5,
    "loan_term_years": 25,
    "monthly_extra_payment": 500,
    "calculated_monthly_payment": 1389.58,
    "effective_interest_rate": 2.505
  },
  "standard_schedule": {
    "total_payments": 300,
    "total_interest": 166874.68,
    "total_principal": 250000,
    "entries": [
      {
        "month": 1,
        "starting_balance": 250000,
        "monthly_payment": 1389.58,
        "principal": 452.08,
        "interest": 937.50,
        "extra_payment": 0,
        "ending_balance": 249547.92,
        "remaining_term": null
      }
      // ... continues for full 300 months
    ]
  },
  "extra_payment_schedule": {
    "total_payments": 186,
    "total_interest": 89432.15,
    "total_principal": 250000,
    "interest_savings": 77442.53,
    "time_savings": 114,
    "entries": [
      {
        "month": 1,
        "starting_balance": 250000,
        "monthly_payment": 1389.58,
        "principal": 452.08,
        "interest": 937.50,
        "extra_payment": 500,
        "ending_balance": 249047.92,
        "remaining_term": 185
      }
      // ... continues until loan is paid off in 186 months
    ]
  }
}
```

### Key Calculations Demonstrated:
1. **Monthly Payment**: AED 1,389.58 (using standard amortization formula)
2. **Standard Schedule**: 300 payments, AED 166,874.68 total interest
3. **With Extra Payments**: 186 payments, AED 89,432.15 total interest
4. **Savings**: AED 77,442.53 interest saved, 114 months shorter term
5. **Effective Interest Rate**: 2.505% (lower due to shortened term from extra payments)

### Get Loan Details
**GET** `/api/loans/{id}`

### Delete Loan
**DELETE** `/api/loans/{id}`

## Project Architecture

### Backend Architecture (Laravel 11)
```
app/
├── Application/                  # Application Layer
│   ├── DTOs/                    # Data Transfer Objects
│   │   └── LoanCalculationRequest.php
│   └── Services/                # Application Services
│       └── LoanApplicationService.php
├── Domain/                      # Domain Layer (DDD)
│   └── Loan/
│       ├── Constants/           # Domain Constants
│       │   └── LoanValidationConstants.php
│       ├── Contracts/           # Domain Interfaces
│       │   ├── AmortizationScheduleRepositoryInterface.php
│       │   ├── LoanRepositoryInterface.php
│       │   └── PaymentStrategyInterface.php
│       ├── Entities/            # Domain Entities
│       │   ├── AmortizationSchedule.php
│       │   ├── Loan.php
│       │   └── PaymentScheduleEntry.php
│       ├── Factories/           # Domain Factories
│       │   └── LoanFactory.php
│       ├── Services/            # Domain Services
│       │   ├── EffectiveRateCalculator.php
│       │   ├── ExtraPaymentStrategy.php
│       │   ├── LoanAmortizationService.php
│       │   ├── MonthlyPaymentCalculator.php
│       │   ├── ScheduleGenerator.php
│       │   └── StandardPaymentStrategy.php
│       └── ValueObjects/        # Value Objects
│           ├── ExtraPayment.php
│           ├── InterestRate.php
│           ├── LoanAmount.php
│           ├── LoanTerm.php
│           └── MonthlyPayment.php
├── Http/                        # Presentation Layer
│   ├── Controllers/
│   │   └── Api/
│   │       └── LoanController.php
│   ├── Requests/               # Form Request Validation
│   │   └── LoanCalculationRequest.php
│   ├── Traits/                 # HTTP Traits
│   │   └── HandlesLoanExceptions.php
│   └── Transformers/           # Response Transformers
│       └── LoanTransformer.php
└── Infrastructure/             # Infrastructure Layer
    ├── Models/                 # Eloquent Models
    │   ├── AmortizationScheduleModel.php
    │   ├── ExtraRepaymentScheduleModel.php
    │   └── LoanModel.php
    └── Repositories/           # Data Access Layer
        ├── EloquentAmortizationScheduleRepository.php
        └── EloquentLoanRepository.php
```

### Frontend Architecture (React.js + TypeScript)
```
frontend/src/
├── components/                  # React Components
│   ├── ErrorMessage.tsx        # Error display component
│   ├── ExtraPaymentBenefits.tsx # Extra payment benefits display
│   ├── FormField.tsx           # Reusable form field
│   ├── LoadingSpinner.tsx      # Loading indicator
│   ├── LoanForm.tsx            # Main loan input form
│   ├── LoanResults.tsx         # Results display
│   ├── LoanSummary.tsx         # Loan summary component
│   ├── MortgageCalculator.tsx  # Main calculator component
│   └── ScheduleTable.tsx       # Amortization table display
├── services/                   # API Services
│   └── api.ts                  # API client configuration
├── types/                      # TypeScript Definitions
│   └── loan.ts                 # Loan-related type definitions
└── utils/                      # Utility Functions
    └── formatters.ts           # Currency and number formatters
```

## Calculation Formula

The application uses the standard amortization formula as specified in the requirements:

### Monthly Payment Calculation
```
Monthly Interest Rate = (Annual Interest Rate / 12) / 100
Number of Months = Loan Term × 12
Monthly Payment = (Loan Amount × Monthly Interest Rate) / (1 - (1 + Monthly Interest Rate)^(-Number of Months))
```

### Example Calculation
- **Loan Amount**: AED 250,000
- **Annual Interest Rate**: 4.5%
- **Loan Term**: 25 years
- **Monthly Interest Rate**: (4.5 / 12) / 100 = 0.00375
- **Number of Months**: 25 × 12 = 300
- **Monthly Payment**: AED 1,389.58

## Database Schema

### loans
| Column | Type | Description |
|--------|------|-------------|
| id | Primary Key | Auto-increment ID |
| loan_amount | Decimal(15,2) | Principal loan amount |
| annual_interest_rate | Decimal(5,3) | Annual interest rate % |
| loan_term_years | Integer | Loan term in years |
| monthly_extra_payment | Decimal(10,2) | Optional extra payment |
| calculated_monthly_payment | Decimal(10,2) | Calculated payment |
| effective_interest_rate | Decimal(5,3) | Effective rate with extras |

### loan_amortization_schedule
| Column | Type | Description |
|--------|------|-------------|
| id | Primary Key | Auto-increment ID |
| loan_id | Integer | Foreign key to loans |
| month_number | Integer | Payment month (1-360) |
| starting_balance | Decimal(15,2) | Balance at month start |
| monthly_payment | Decimal(10,2) | Fixed monthly payment |
| principal_component | Decimal(10,2) | Principal portion |
| interest_component | Decimal(10,2) | Interest portion |
| ending_balance | Decimal(15,2) | Balance after payment |

### extra_repayment_schedule  
| Column | Type | Description |
|--------|------|-------------|
| id | Primary Key | Auto-increment ID |
| loan_id | Integer | Foreign key to loans |
| month_number | Integer | Payment month |
| starting_balance | Decimal(15,2) | Balance at month start |
| monthly_payment | Decimal(10,2) | Fixed monthly payment |
| principal_component | Decimal(10,2) | Principal portion |
| interest_component | Decimal(10,2) | Interest portion |
| extra_repayment | Decimal(10,2) | Extra payment amount |
| ending_balance | Decimal(15,2) | Balance after payments |
| remaining_term | Integer | Months remaining |

## Configuration

### Environment Variables
```bash
# Application
APP_NAME="Mortgage Calculator"
APP_ENV=local
APP_KEY=base64:...
APP_DEBUG=true
APP_URL=http://localhost:8080

# Database
DB_CONNECTION=sqlite
DB_DATABASE=/var/www/database.sqlite

# CORS (for React frontend)
FRONTEND_URL=http://localhost:3000
```

### Frontend Configuration
```bash
# React Environment
REACT_APP_API_URL=http://localhost:8080/api
```

## Validation Rules

### Input Validation
- **Loan Amount**: AED 5,000 - AED 4,000,000 (minimum viable loan amount)
- **Annual Interest Rate**: 0% and above (no upper limit)
- **Loan Term**: 1 year minimum (no upper limit)
- **Extra Payment**: AED 0 and above - Cannot equal or exceed loan amount

### Error Handling
- **422**: Validation errors with detailed field-specific messages
- **404**: Resource not found with structured error format
- **500**: Server errors with user-friendly messages

## Deployment

### Production Setup
1. **Configure environment**
   ```bash
   cp .env.example .env
   # Update production settings
   ```

2. **Optimize application**
   ```bash
   composer install --optimize-autoloader --no-dev
   php artisan config:cache
   php artisan route:cache
   php artisan view:cache
   ```

3. **Build frontend**
   ```bash
   cd frontend
   npm run build
   ```

## Requirements Compliance

This project fully implements all requirements from the PHP Laravel Practical Exam:

- **Unit tests** for API endpoints with exact loan parameters
- **Input validation** with appropriate error messages and proper handling
- **Monthly payment calculation** using exact specified formula
- **Amortization schedule generation** with detailed monthly breakdowns
- **Database storage** in required tables with exact column specifications
- **Extra repayment logic** with shortened loan terms and fixed monthly payments
- **Effective interest rate calculation** accounting for extra payments
- **Laravel routing, controllers, and views** with React.js frontend
- **Comprehensive testing** with various input scenarios and edge cases
- **React.js web interface** with modern professional UI
- **Complete documentation** and setup instructions
- **Database migrations and seeding files** included

---

**Built with Laravel 11 & React.js TypeScript**