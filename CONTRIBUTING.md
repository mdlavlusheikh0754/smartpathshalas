# Contributing to Smart Pathshala

Thank you for considering contributing to Smart Pathshala! This document provides guidelines for contributing to this project.

## Code of Conduct

This project adheres to a code of conduct. By participating, you are expected to uphold this code.

## How to Contribute

### Reporting Bugs

Before creating bug reports, please check the existing issues to avoid duplicates. When creating a bug report, include:

- A clear and descriptive title
- Steps to reproduce the issue
- Expected behavior
- Actual behavior
- Screenshots (if applicable)
- Environment details (OS, PHP version, etc.)

### Suggesting Enhancements

Enhancement suggestions are welcome! Please provide:

- A clear and descriptive title
- Detailed description of the proposed feature
- Use cases and benefits
- Any relevant examples or mockups

### Pull Requests

1. Fork the repository
2. Create a feature branch (`git checkout -b feature/amazing-feature`)
3. Make your changes
4. Add tests if applicable
5. Ensure all tests pass
6. Update documentation if needed
7. Commit your changes (`git commit -m 'Add amazing feature'`)
8. Push to the branch (`git push origin feature/amazing-feature`)
9. Open a Pull Request

### Development Setup

1. Clone the repository
2. Install dependencies: `composer install && npm install`
3. Copy environment file: `cp .env.example .env`
4. Generate application key: `php artisan key:generate`
5. Run migrations: `php artisan migrate`
6. Start development server: `php artisan serve`

### Coding Standards

- Follow PSR-12 coding standards
- Use meaningful variable and function names
- Add comments for complex logic
- Write tests for new features
- Ensure code is properly formatted

### Testing

- Run tests before submitting: `php artisan test`
- Add tests for new functionality
- Ensure all tests pass

### Documentation

- Update relevant documentation
- Add docblocks to new methods
- Update API documentation if needed

## Project Structure

```
smart-pathshala/
├── app/                    # Application code
├── config/                 # Configuration files
├── database/              # Migrations and seeders
├── docs/                  # Documentation
├── public/                # Public assets
├── resources/             # Views, CSS, JS
├── routes/                # Route definitions
├── scripts/               # Deployment and utility scripts
├── storage/               # File storage
└── tests/                 # Test files
```

## Questions?

If you have questions about contributing, please create an issue or contact the maintainers.

Thank you for contributing to Smart Pathshala!