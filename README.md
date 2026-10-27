# 🏠 Home Rental System

A full-featured web-based **Home Rental Management System** built with **PHP** and **MySQL**. This platform connects property owners and prospective tenants while providing administrators with oversight and management tools.

---

## 🌟 Key Features

### 👤 Role-Based Access Control
- **Admin**:
  - Manage all registered users (tenants and owners).
  - Manage and monitor all property listings.
  - Review and oversee rental requests, bookings, and payments.
  - View inquiries and reply to messages.
- **Property Owner**:
  - Add and manage property listings (titles, rent, location, description, image uploads).
  - View tenant requests and approve or reject booking inquiries.
  - Track payment status from tenants.
- **Tenant**:
  - Browse and search properties by location, type, and price.
  - View detailed property information with photos.
  - Submit rental booking requests.
  - Make payments, track payment history, and view receipts.
  - Save favorite properties for quick access.
  - Send inquiries and messages directly.

### 🔐 Authentication & Security
- User registration with role selection (Owner / Tenant).
- Email verification via OTP (powered by **PHPMailer**).
- Secure password hashing using PHP's native `password_hash()`.
- Password reset / "Forgot Password" functionality via email verification.
- Session-based user authentication and access controls.

---

## 🛠️ Technology Stack

- **Backend**: PHP (Native / Procedural & OOP)
- **Database**: MySQL / MariaDB
- **Frontend**: HTML5, CSS3, JavaScript
- **Email Service**: PHPMailer (SMTP support for OTPs & password recovery)
- **Server Environment**: Apache (XAMPP / WAMP / LAMP)

---

## 📋 Prerequisites

Before running the project, ensure you have the following installed:
- [XAMPP](https://www.apachefriends.org/) (recommended) or any local server environment with:
  - **PHP 7.4+** or **PHP 8.x**
  - **Apache HTTP Server**
  - **MySQL / MariaDB**
- Web browser (Chrome, Edge, Firefox, etc.)

---

## 🚀 Installation & Setup Guide

### 1. Clone or Move Project to Web Server Directory
Place the project directory inside your local server's web root (`htdocs` for XAMPP):

```bash
# Path should be:
C:\xampp\htdocs\home_rental_system
```

### 2. Start Apache and MySQL
Open the **XAMPP Control Panel** and click **Start** for both:
- **Apache**
- **MySQL**

### 3. Create & Import the Database
1. Open your web browser and navigate to phpMyAdmin:
   ```
   http://localhost/phpmyadmin/
   ```
2. Create a new database named:
   ```sql
   home_rental_system
   ```
3. Click on the newly created database, go to the **Import** tab.
4. Choose the SQL file located in the project:
   ```
   database/home_rental_system.sql
   ```
5. Click **Import** (or **Go**) at the bottom to execute the script and create all tables and initial sample data.

### 4. Database Connection Settings
The application uses default XAMPP MySQL credentials across the PHP scripts:
- **Host**: `localhost`
- **Username**: `root`
- **Password**: `""` *(blank by default)*
- **Database Name**: `home_rental_system`

If your MySQL instance uses a custom password or port, update the `$conn = new mysqli(...)` connection string in the respective PHP files (e.g., `login.php`, `register.php`, `dashboard.php`, etc.).

### 5. Email / SMTP Configuration (Optional)
For OTP verification and password recovery to send real emails:
1. Open `register.php` and `forgot_password.php`.
2. Locate the PHPMailer configuration section:
   ```php
   $mail->Host       = 'smtp.gmail.com';
   $mail->SMTPAuth   = true;
   $mail->Username   = 'your-email@gmail.com';
   $mail->Password   = 'your-app-password';
   $mail->SMTPSecure = 'tls';
   $mail->Port       = 587;
   ```
3. Replace with your SMTP credentials (for Gmail, generate an **App Password** from your Google Account settings).

---

## 🔑 Default Credentials

The imported database includes a seeded administrator account:

| Role | Email | Password |
|---|---|---|
| **Admin** | `admin@gmail.com` | `admin123` |

> *Note: You can also register a new account as an **Owner** or **Tenant** via the registration page.*

---

## 🌐 Running the Application

Open your browser and navigate to:
```
http://localhost/home_rental_system/
```
The entry point will load the splash screen and redirect to `home.php`.

---

## 📁 Project Structure

```text
home_rental_system/
├── database/
│   └── home_rental_system.sql   # Database schema and seed data
├── PHPMailer/                   # PHPMailer library for email services
├── uploads/                     # Uploaded property images and proofs
├── add_property.php             # Form to create new property listing
├── admin.php                    # Admin dashboard & management portal
├── booking.php                  # Property booking handling
├── confirm_payment.php          # Payment confirmation logic
├── dashboard.php                # Unified routing/dashboard view
├── forgot_password.php          # Password recovery via OTP
├── home.php                     # Main landing page for visitors
├── index.php                    # Splash/entry screen
├── login.php                    # User authentication
├── logout.php                   # Session termination
├── owner.php                    # Property owner dashboard
├── pay.php                      # Payment processing interface
├── payments.php                 # Payment management & records
├── payment_success.php          # Payment success confirmation screen
├── properties.php               # Property listings page
├── receipt.php                  # Printable payment receipt
├── register.php                 # User signup with OTP verification
├── request_property.php         # Rental request submission
├── requests.php                 # Request management view
├── search.php                   # Property search & filtering
├── send_message.php             # Contact/inquiry messaging
├── tenant.php                   # Tenant dashboard & active rentals
├── users.php                    # Admin user management portal
├── verify_otp.php               # Account OTP verification page
└── view_property.php            # Detailed property view
```

---

## 📄 License
This project is open-source and available for educational and personal use.
