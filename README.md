# FoodBook - Digital Recipe Book

**Course Module:** ICT 1209 - Web Technologies  
**Project:** Mini Project (Individual Submission)  
**Student Name:** Ishara Nishshanka  
**Registration Number:** ITT/2021/076  
**Department:** Department of ICT  
**Faculty:** Faculty of Technology  
**University:** Rajarata University of Sri Lanka  

---

## 1. Project Overview

FoodBook is a web application created using HTML, CSS, Bootstrap, JavaScript, PHP, and MySQL. It allows users to browse food recipes, search by title or ingredient in real time, filter by meal category, register an account, and add their own recipes.

### Technologies Used:
- **Frontend:** HTML5, CSS3, JavaScript, Bootstrap 5.3
- **Backend:** PHP 8
- **Database:** MySQL (phpMyAdmin)
- **Environment:** XAMPP

---

## 2. Main Features

### Frontend (HTML, CSS, Bootstrap & JavaScript)
- **Responsive Layout:** Adapts to mobile, tablet, and desktop screens using Bootstrap 5.
- **Interactive Image Slider:** Auto-playing hero slider with previous and next controls.
- **Dynamic Content Search & Filter:** Instant client-side search by keyword and category dropdown without page refresh.
- **Form Validation:** Client-side validation for required fields, email format, and password length.
- **Quick View Modal:** Click on any recipe card to open a modal popup with full ingredients and step-by-step instructions.

### Backend (PHP & MySQL)
- **User Authentication:** 
  - User registration (`register.php`) with password hashing using `password_hash()`.
  - User login (`login.php`) using `password_verify()` and PHP sessions.
  - User logout (`logout.php`) to safely destroy sessions.
- **Recipe Management (Dashboard):**
  - Authenticated users can view recipes they created.
  - Users can submit new recipes (title, category, cooking time, servings, ingredients, instructions).
  - Users can delete their own recipes.
- **Contact Form:**
  - Visitors can submit questions or messages, which are saved in the `messages` table in MySQL.

---

## 3. Database Structure (`foodbook`)

The application uses a MySQL database named **`foodbook`**. The SQL file is included in this repository as `database.sql`.

### Tables:
1. **`users`** - Stores registered user details:
   - `id` (INT, Primary Key, Auto Increment)
   - `username` (VARCHAR)
   - `email` (VARCHAR, Unique)
   - `password` (VARCHAR, hashed with bcrypt)
   - `created_at` (TIMESTAMP)

2. **`recipes`** - Stores submitted recipes:
   - `id` (INT, Primary Key, Auto Increment)
   - `user_id` (INT, Foreign Key referencing `users.id`)
   - `title` (VARCHAR)
   - `description` (TEXT)
   - `category` (VARCHAR - Breakfast, Lunch, Dinner, Desserts, Drinks)
   - `ingredients` (TEXT)
   - `instructions` (TEXT)
   - `cooking_time` (INT)
   - `servings` (INT)
   - `image_url` (VARCHAR)
   - `rating` (DECIMAL)
   - `views` (INT)
   - `created_at` (TIMESTAMP)

3. **`messages`** - Stores contact form inquiries:
   - `id` (INT, Primary Key, Auto Increment)
   - `name` (VARCHAR)
   - `email` (VARCHAR)
   - `subject` (VARCHAR)
   - `message` (TEXT)
   - `created_at` (TIMESTAMP)

---

## 4. How to Run the Project Locally (XAMPP)

### Step 1: Clone or Copy Project Files
Place the project folder inside your XAMPP `htdocs` directory:
```
C:\xampp\htdocs\foodbook\
```

### Step 2: Start Apache and MySQL
1. Open the **XAMPP Control Panel**.
2. Click **Start** for **Apache**.
3. Click **Start** for **MySQL**.

### Step 3: Import the Database
1. Open your web browser and go to: `http://localhost/phpmyadmin/`
2. Click **New** on the left menu and create a database named `foodbook`.
3. Select the `foodbook` database, then click on the **Import** tab.
4. Choose the `database.sql` file from the project root folder.
5. Click **Import** at the bottom of the page.

### Step 4: Open in Browser
Visit the following URL in your web browser:
```
http://localhost/foodbook/
```

---

## 5. Demo Login Credentials

You can use the following default account to test the login and dashboard:

- **Email:** `itt2021076@tec.rjt.ac.lk`
- **Password:** `ITT/2021/076`

*(You can also register a new account on the registration page).*

---

## 6. Folder Structure

```
foodbook/
|-- auth/
|   |-- login.php          # User login page
|   |-- logout.php         # Logout logic
|   |-- register.php       # User registration page
|-- css/
|   |-- style.css          # Custom CSS styles
|-- js/
|   |-- main.js            # Slider, search filter, and modal logic
|   |-- validation.js      # Client-side form validation
|-- includes/
|   |-- db.php             # Database connection
|   |-- functions.php      # Helper functions
|   |-- recipe_handler.php # Add and delete recipe logic
|   |-- contact_handler.php# Contact form processing
|-- contact.php            # Contact and FAQ page
|-- dashboard.php          # User recipe dashboard
|-- index.php              # Homepage and recipe list
|-- database.sql           # Database export file
|-- README.md              # Project documentation
```
