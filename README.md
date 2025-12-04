# Jadaa Mart - E-Commerce Solution

A complete e-commerce web application built with PHP, MySQL, and Bootstrap 5.

## Features
- **User Side**:
  - Home page with slider and featured products.
  - Product browsing with categories, search, and sorting.
  - Shopping Cart and Checkout system.
  - User Authentication (Login/Register).
  - User Dashboard (Order History).
- **Admin Side**:
  - Dashboard Overview (Stats).
  - Product Management (Add, Edit, Delete).
  - Order Management (Update Status).

## Setup Instructions

### 1. Prerequisites
- Install **XAMPP** (or any PHP/MySQL environment).
- Ensure Apache and MySQL services are running.

### 2. Installation
1. Move the `JadaaMart` folder to your XAMPP `htdocs` directory:
   `C:\xampp\htdocs\JadaaMart`
2. Open **phpMyAdmin** (http://localhost/phpmyadmin).
3. Create a new database named `jadaamart_db`.
4. Import the `database.sql` file located in the project root.

### 3. Configuration
- The database connection is configured in `config/database.php`.
- Default credentials:
  - Host: `localhost`
  - User: `root`
  - Password: `` (empty)

### 4. Usage
- **Public Site**: Access http://localhost/JadaaMart
- **Admin Panel**: Access http://localhost/JadaaMart/admin
  - **Admin Login**:
    - Email: `admin@jadaamart.com`
    - Password: `admin123`

## Directory Structure
- `/public`: Public assets (if separated).
- `/assets`: CSS, JS, Images.
- `/includes`: Header, Footer, Functions.
- `/config`: Database configuration.
- `/admin`: Admin panel files.
- `/controllers`: Logic files (if separated).
- `/models`: Data models (if separated).

## Technologies
- **Backend**: Native PHP (PDO)
- **Frontend**: HTML5, CSS3, Bootstrap 5
- **Database**: MySQL
