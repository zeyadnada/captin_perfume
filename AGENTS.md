# Perfume E-commerce – Project Instructions

## 1. Project Overview

We are building a single-store Perfume E-commerce application using Laravel 13
and PHP 8.3.

This is NOT a multi-vendor marketplace. There is only one store managed through a central admin dashboard.

The application will eventually include:

- Admin Dashboard.
- Customer-facing E-commerce Website.
- Product and Inventory Management.
- Shopping Cart and Checkout.
- Order Management.
- Payment and Shipping Integration.
- Customer Management.

**Development will start with the Admin Dashboard**, focusing on project setup, master data, and normal CRUD operations. The customer-facing website will be developed later.

## 2. Technology Stack

- Backend: Laravel 13.
- Language: PHP 8.3.
- Database: MySQL.
- Frontend: Blade.
- CSS Framework: Bootstrap.
- JavaScript: Vanilla JavaScript.
- Authentication: Laravel authentication.
- Permissions: Spatie Laravel Permission.
- API Authentication: Laravel Sanctum (when API development starts).
- API Documentation: Scramble (when API development starts).

Do not introduce additional technologies or packages without a good reason.

## 3. Existing Admin Dashboard Template – CoolAdmin

The project already contains a Bootstrap Admin Dashboard template called **CoolAdmin**.

Its original folder is located in the project root:

`CoolAdmin/`

This folder is excluded from Git tracking through `.gitignore`.

Important instructions:

- Inspect the CoolAdmin folder before implementing any dashboard pages.
- Understand its HTML structure, layouts, CSS, JavaScript, images, fonts, and components.
- Use CoolAdmin as the design reference for the entire admin dashboard.
- Integrate the template into Laravel Blade views.
- Create reusable Blade layouts and components where appropriate.
- Reuse its existing design, colors, buttons, forms, tables, cards, and other UI components.
- Do not create a new dashboard design.
- Do not introduce another UI framework.
- Do not unnecessarily modify the original CoolAdmin source files.

The original CoolAdmin folder should remain available as a reference. Copy only the required assets into Laravel's public directory when integrating the template.

## 4. Architecture and Coding Standards

Follow Laravel 13 best practices and write clean, maintainable, readable code.

General rules:

- Follow SOLID principles where appropriate.
- Use dependency injection.
- Use Form Request classes for validation.
- Use Eloquent relationships properly.
- Avoid N+1 query problems.
- Use database transactions for operations involving multiple related database changes.
- Use Policies and Gates for authorization.
- Use Events and Listeners when they provide meaningful decoupling.
- Use Jobs and Queues for suitable background operations.
- Use Enums when they improve clarity and maintainability.
- Keep controllers focused and avoid massive controllers.
- Follow proper Laravel and PHP naming conventions.
- Handle exceptions appropriately.
- Avoid duplicated business logic.

Do not over-engineer simple CRUD operations.

Do not create unnecessary Repository, Service, Interface, or Factory classes just for the sake of applying design patterns.

Introduce abstractions only when they provide real benefits.

## 5. Learning Objectives

This project is also a practical opportunity to improve my backend development skills.

Help me understand and practice:

- Advanced Laravel concepts.
- Eloquent relationships and query optimization.
- SOLID principles.
- Dependency Injection and Service Container.
- Design Patterns.
- Database Transactions.
- Events, Listeners, and Queues.
- Authentication and Authorization.
- API development.
- Security and Performance Optimization.

When implementing something technically important:

1. Explain what the concept does.
2. Explain why we need it in the current feature.
3. Explain important architectural decisions.
4. Mention alternative approaches when relevant.

Keep explanations practical and related to the actual implementation. Avoid unnecessary theoretical lectures.

## 6. Development Roadmap

Follow this roadmap in order. Do not jump to future phases unless explicitly requested.

### Phase 1 – Project Foundation
- Inspect the existing Laravel project.
- Configure the database.
- Integrate the CoolAdmin dashboard template.
- Set up authentication.
- Set up admin access and permissions.
- Prepare the application structure.

### Phase 2 – Admin Dashboard and Master Data
- Brands Management.
- Categories Management.
- Product Management.
- Product Variants.
- Product Images.
- Other necessary master data.

### Phase 3 – Inventory Management
- Stock management.
- Stock movements.
- Inventory adjustments.

### Phase 4 – E-commerce Core
- Customer accounts.
- Product catalog.
- Shopping cart.
- Checkout.
- Customer addresses.

### Phase 5 – Orders and Payments
- Order management.
- Order items.
- Order status management.
- Payment methods and integration.
- Shipping and delivery management.

### Phase 6 – Advanced Features
- Coupons and discounts.
- Product reviews.
- Notifications.
- Reports and analytics.
- API development.

### Phase 7 – Quality and Deployment
- Security review.
- Query optimization.
- Caching and queues.
- Deployment.

## 7. AI Development Workflow

Work with me as a collaborative Laravel developer, not just an autonomous code generator.

For every new module or significant feature, follow this workflow:

### Step 1 – Analyze
- Understand the requirements.
- Inspect relevant existing code.
- Identify dependencies and affected files.

### Step 2 – Plan
- Propose the database structure.
- Explain relationships.
- Identify required models, controllers, requests, views, and other classes.
- Explain important architectural decisions.

### Step 3 – Implement
- Implement the feature in small, logical steps.
- Follow existing project conventions.
- Avoid modifying unrelated files.

### Step 4 – Review
- Check validation, authorization, security, and performance.
- Identify potential bugs and edge cases.

### Step 5 – Explain
- Summarize what was implemented.
- Explain important Laravel concepts used.
- Mention any decisions I should understand before moving forward.

## 8. Important Rules
- Never assume the existing project structure. Inspect it first.
- Never overwrite or replace existing dashboard template files without checking their usage.
- Never install unnecessary packages.
- Never modify unrelated code.
- Never generate the entire application in one operation.
- Never skip database design before implementing a new module.
- Never implement complex business logic without explaining it.
- Prefer simple, maintainable solutions over unnecessary abstractions.
- Ask me when an important business requirement is unclear.
- Preserve existing functionality when adding new features.
- Follow the current development phase and do not implement future features prematurely.
- Explain significant changes before applying them.

Remember: This is a real freelancing project and a learning opportunity. Code quality, maintainability, and understanding are as important as completing features.