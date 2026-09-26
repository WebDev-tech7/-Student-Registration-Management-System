# Student Registration Management system

## Description
A complete web-based student registration management system 
built with PHP and MySQL. The application allows administrators 
to manage students, classes, academic years, and registrations 
through a secure and intuitive interface.

## Technologies Used
- PHP 8.2
- MySQL / MariaDB
- HTML / CSS
- JavaScript
- Apache (XAMPP / WAMP)

## Features
- Secure authentication system (login/logout)
- Dashboard with real-time statistics
- Manage academic years
- Manage classes
- Add, edit, delete and search students
- Register/re-register students per academic year and class
- Filter registrations by student, year or class
- SQL injection protection using prepared statements
- Password hashing with bcrypt

## Database Tables
- utilisateur — Admin accounts
- annee_scolaire — Academic years
- classe — Classes
- etudiant — Students (matricule, name, email, phone, gender)
- inscription — Student registrations per year and class

## Database Structure

### Tables
- **annee_scolaire** — Academic years (2020-2021 to 2024-2025)
- **classe** — Classes (1DI, 2DI, 3DI, 3MI)
- **etudiant** — Students with matricule, name, email, 
  phone, gender, date of birth
- **inscription** — Links student + class + academic year 
  with automatic registration date
- **utilisateur** — Admin accounts with bcrypt 
  hashed passwords

### Relationships
- One student can have multiple registrations 
  (one per academic year)
- Each registration links one student to one class 
  and one academic year
- Cascading delete: removing a student automatically 
  removes all their registrations

### Security Features
- Primary keys on all tables
- Unique constraints (matricule, email, username)
- Foreign key constraints with CASCADE
- Passwords hashed with bcrypt (PHP password_hash)
- SQL injection prevention with prepared statements

## How to Run
1. Install XAMPP or WAMP
2. Import the file gestion_inscription.sql 
   into phpMyAdmin
3. Place the project folder in htdocs/
4. Open http://GestionInscription/
5. Login with your credentials

## Project Type
Academic Project — Licence in Computer Science  
(Software Development)
SUP'MANAGEMENT, Nouakchott, Mauritania

## Author
CHEIKHNA BOCAR DIAGANA
Final-year Computer Science Student
Mauritania
