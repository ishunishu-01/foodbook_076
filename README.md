# 🍳 FoodBook - Digital Recipe Book Web Application

**Course Module:** ICT 1209 – Web Technologies  
**Project:** Mini Project – Individual Submission  
**Student Name:** Ishara Nishshanka  
**Registration Number:** ITT/2021/076  
**Department:** Department of ICT  
**Faculty:** Faculty of Technology  
**University:** Rajarata University of Sri Lanka  

---

## 📖 1. Project Overview

**FoodBook** is an interactive, full-stack web application designed for food lovers and culinary creators. Users can discover culinary recipes, filter dishes by meal type or ingredient in real time, view detailed cooking steps via interactive modal dialogs, register a personal account, and publish their own signature recipes.

### 🌟 Key Highlights
- **Responsive Design:** Fluid layout across mobile, tablet, and desktop viewports using **Bootstrap 5.3** and custom CSS3.
- **Dynamic JavaScript Interactivity:** Real-time client-side search without page reloads, interactive hero slider, modal popups, and live form validation.
- **Secure Backend Integration:** Modular **PHP 8** architecture using PDO/MySQLi prepared statements, session-based authentication, and `password_hash()` bcrypt encryption.
- **Relational Database:** MySQL database schema named `foodbook` featuring relational integrity with foreign keys and cascade delete rules.

---

## 🛠️ 2. Technologies Used

| Layer | Technologies |
|---|---|
| **Frontend** | HTML5, CSS3, JavaScript (ES6+), Bootstrap 5.3.2, Bootstrap Icons |
| **Backend** | PHP 8.x (Session management, Prepared Statements, Input Sanitization) |
| **Database** | MySQL (phpMyAdmin, InnoDB engine, UTF-8 unicode collation) |
| **Local Server** | XAMPP / WAMP / LAMP |

---

## 🚀 3. Features Implemented

### 3.1. Frontend & User Experience (20% + 15%)
1. **Responsive Multi-Page Structure:**
   - `index.php`: Landing page with dynamic recipe showcase, hero slider, stats, and search bar.
   - `dashboard.php`: Protected user portal for publishing and managing recipes.
   - `contact.php`: Feedback and inquiry page with accordion FAQ and contact cards.
   - `auth/login.php` & `auth/register.php`: Clean authentication pages.
2. **Interactive JavaScript Features (Meets & exceeds 3 required features):**
   - **Dynamic Content Updates:** Instant client-side search and category filtering with dynamic recipe counts and "No Results" fallback.
   - **Interactive Image Slider:** Auto-advancing hero banner (4.5s interval) with manual previous/next buttons, navigation dots, and pause-on-hover.
   - **Form Validation:** Comprehensive client-side validation (`js/validation.js`) with regex email checking, real-time password strength meter, and character counter.
   - **Smooth Scrolling:** Smooth navigation offset for all internal anchor links.
   - **Event Handling:** Interactive Quick View modal popups, card hover lift effects, and category pills.
   - **Custom Animations:** IntersectionObserver-driven scroll animations (`fadeInUp`) and animated statistics counters.

### 3.2. Backend & Database Integration (20% + 20% + 10%)
1. **User Authentication:**
   - Registration with password confirmation, format validation, and `password_hash()` bcrypt encryption.
   - Secure login verification via `password_verify()` with session tracking (`$_SESSION['user_id']`).
   - Clean session destruction on logout (`auth/logout.php`).
2. **Recipe CRUD (Create, Read, Update, Delete):**
   - Authenticated users can submit recipes with titles, meal categories, preparation steps, ingredients, cooking times, and servings.
   - Users can safely delete their own recipes from their dashboard.
3. **Contact Form Handling:**
   - Submissions are sanitized and stored in the MySQL `messages` table with flash message feedback.

---

## 🗄️ 4. Database Schema (`foodbook`)

The application connects to a MySQL database named **`foodbook`**. The complete dump is provided in [`database.sql`](database.sql).

### Table: `users`
| Column | Type | Attributes | Description |
|---|---|---|---|
| `id` | INT(11) | AUTO_INCREMENT, PRIMARY KEY | Unique user ID |
| `username` | VARCHAR(50) | NOT NULL | User's handle |
| `email` | VARCHAR(100) | NOT NULL, UNIQUE | User's email |
| `password` | VARCHAR(255) | NOT NULL | Hashed password (`password_hash`) |
| `created_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP | Registration timestamp |

### Table: `recipes`
| Column | Type | Attributes | Description |
|---|---|---|---|
| `id` | INT(11) | AUTO_INCREMENT, PRIMARY KEY | Unique recipe ID |
| `user_id` | INT(11) | NOT NULL, FOREIGN KEY | References `users(id)` ON DELETE CASCADE |
| `title` | VARCHAR(150) | NOT NULL | Recipe title |
| `description` | TEXT | NULL | Short summary |
| `category` | VARCHAR(50) | NOT NULL | Breakfast, Lunch, Dinner, etc. |
| `ingredients` | TEXT | NOT NULL | List of ingredients |
| `instructions`| TEXT | NOT NULL | Preparation steps |
| `cooking_time`| INT(11) | DEFAULT 30 | Time in minutes |
| `servings` | INT(11) | DEFAULT 4 | Servings yield |
| `image_url` | VARCHAR(500) | NULL | Food photograph URL |
| `rating` | DECIMAL(3,1) | DEFAULT 4.5 | Community rating |
| `views` | INT(11) | DEFAULT 0 | View counter |
| `created_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP | Creation timestamp |

### Table: `messages`
| Column | Type | Attributes | Description |
|---|---|---|---|
| `id` | INT(11) | AUTO_INCREMENT, PRIMARY KEY | Unique message ID |
| `name` | VARCHAR(100) | NOT NULL | Sender name |
| `email` | VARCHAR(100) | NOT NULL | Sender email |
| `subject` | VARCHAR(200) | DEFAULT 'General Inquiry' | Subject |
| `message` | TEXT | NOT NULL | Query body |
| `created_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP | Submission timestamp |

---

## ⚙️ 5. Step-by-Step Installation & Setup Guide

### Step 1: Install XAMPP
Download and install [XAMPP](https://www.apachefriends.org/) (PHP 8.x recommended).

### Step 2: Copy Project to `htdocs`
Copy this project folder into your XAMPP web root directory:
```
C:\xampp\htdocs\foodbook\
```
*(Or keep in your working folder and configure a VirtualHost).*

### Step 3: Start Apache & MySQL
1. Open the **XAMPP Control Panel**.
2. Click **Start** next to **Apache**.
3. Click **Start** next to **MySQL**.

### Step 4: Import Database (`foodbook`)
1. Open your browser and navigate to **[http://localhost/phpmyadmin/](http://localhost/phpmyadmin/)**.
2. Click on **New** on the left sidebar.
3. In the "Database name" field, enter `foodbook` and select collation `utf8mb4_unicode_ci`, then click **Create**.
4. With `foodbook` selected, click the **Import** tab at the top.
5. Click **Choose File** and select the [`database.sql`](database.sql) file located in the project root.
6. Click **Import** at the bottom. All tables (`users`, `recipes`, `messages`) and sample data will be created automatically.

### Step 5: Verify Database Configuration
Open `includes/db.php` and ensure the database credentials match your local setup:
```php
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'foodbook');
```

### Step 6: Launch Application
Open your browser and visit:
```
http://localhost/foodbook/
```

---

## 🔑 6. Demo User Credentials

For testing and grading convenience, sample accounts are pre-populated in `database.sql`:

| Role | Username | Email | Password |
|---|---|---|---|
| **Admin Chef** | `ishara_admin` | `ishara@foodbook.com` | `Password123` |
| **Community Chef** | `chef_kamal` | `kamal@foodbook.com` | `Password123` |

*Note: You can also register a brand new account at `auth/register.php` anytime.*

---

## 📁 7. Project Folder Structure

```
itt2021076/
│── auth/
│   ├── login.php               # User login authentication page
│   ├── logout.php              # Session destruction logic
│   └── register.php           # User registration page
│── css/
│   └── style.css               # Custom responsive styles & animations
│── js/
│   ├── main.js                 # Slider, search filter, modal & animations
│   └── validation.js           # Client-side form validation routines
│── includes/
│   ├── db.php                  # MySQL database connection
│   ├── functions.php           # Helper functions & query abstractions
│   ├── recipe_handler.php      # Recipe CRUD processor (Add/Delete)
│   └── contact_handler.php     # Contact form submission processor
│── contact.php                 # Contact form & FAQ page
│── dashboard.php               # Protected user dashboard
│── index.php                   # Main home page & recipe catalog
│── database.sql                # Complete MySQL database export
└── README.md                   # Setup documentation & project details
```

---

## 👥 8. Author & Academic Declaration

- **Student:** Ishara Nishshanka
- **Registration Number:** ITT/2021/076
- **Module:** ICT 1209 – Web Technologies Mini Project
- **Institution:** Rajarata University of Sri Lanka

*I hereby declare that this mini-project is an original piece of work developed by me in partial fulfillment of the continuous assessment requirements for ICT 1209.*
