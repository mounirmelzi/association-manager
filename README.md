# Association Manager - Project Setup Instructions

## Prerequisites

- XAMPP v8.2.12 (includes PHP 8.2.12) - [Download Link](https://www.apachefriends.org/fr/download.html)
- MySQL database (included with XAMPP)

## Installation Steps

### 1. Project Placement

1. Locate your XAMPP's `htdocs` directory (default: `C:/xampp/htdocs`)
2. Place the entire `association-manager` project folder directly in the `htdocs` directory
   - Correct path should be: `C:/xampp/htdocs/association-manager`

### 2. Database Setup

1. Start XAMPP's MySQL service
2. Open phpMyAdmin (`http://localhost/phpmyadmin`)
3. Create a new database for the project
4. Import the database structure and data:
   - Select your newly created database
   - Click on "Import" in the top menu
   - Choose the `TDW.sql` file
   - Click "Go" to import the database structure and data

### 3. Database Configuration

1. Navigate to `app/config/database.config.php`
2. Update the database connection parameters with your local settings:

   ```php
   // Example configuration
   const DB_ENGINE = "mysql";
   const DB_HOST = "localhost";
   const DB_PORT = "3306";
   const DB_NAME = "association_manager";
   const DB_USERNAME = "root";
   const DB_PASSWORD = "";
   ```

### 4. Base URL Configuration

1. The project uses a BASE_URL constant defined in `app/config/app.config.php`:

   ```php
   const BASE_URL = "http://localhost/association-manager/public/";
   ```

2. **Important**: If you place the project in a different location, you must update this URL to match your setup
   - The URL should point to the `public` folder in your project
   - Always use HTTP protocol (e.g., `http://`) instead of file system paths
   - Make sure the path matches your actual project location relative to the web server root

### 5. Running the Project

1. Start `XAMPP`'s `Apache` and `MySQL` services
2. Access the project through your web browser using the BASE_URL
   - Default: `http://localhost/association-manager/public/`

### Troubleshooting

- If you see a 404 error, verify that:
  1. The project folder is directly in `htdocs`
  2. The BASE_URL constant matches your actual project location
  3. Apache service is running
- For database connection errors:
  1. Verify your database credentials
  2. Ensure MySQL service is running
  3. Confirm the database exists and has been properly imported
  4. Check that the database name in database.config.php matches the one you created

For any additional assistance or issues, please contact me directly.
