# Subscription-System
# Email Subscription System

## Overview

The **Email Subscription System** is a web application designed to manage email subscriptions effectively. Users can subscribe to receive updates, and administrators can manage subscriber information through a dedicated dashboard. This project implements a user-friendly interface, allowing seamless subscription management while ensuring data integrity and security.

## Features

- **User Subscription:** Allows users to sign up by providing their name and email address.
- **Email Confirmation:** Sends a confirmation email to verify the user's email address upon subscription.
- **Admin Dashboard:** A secure dashboard for administrators to manage subscribers, view details, and delete entries if necessary.
- **Search Functionality:** Enables easy searching of subscribers by name or email in the admin dashboard.
- **Responsive Design:** Ensures usability on both desktop and mobile devices.

## Technologies Used

- **Frontend:**
  - HTML5
  - CSS3 (Bootstrap for responsive design)
  - JavaScript (jQuery for dynamic content)
- **Backend:**
  - PHP
  - MySQL (for database management)
- **Prerequisites:**
Before you begin, ensure you have met the following requirements:

1- PHP
Make sure you have PHP installed on your machine. You can download and install it from PHP.net.
Version: PHP 7.4 or higher is recommended.

2- Composer
You'll need Composer, the dependency manager for PHP, to install necessary libraries (e.g., PHPMailer).

Download Composer from getcomposer.org.
After installation, run:
composer install
This will install the required packages listed in composer.json, including PHPMailer.

3- MySQL Database
Set up a MySQL database to store subscriber information.

You can use tools like XAMPP or WAMP to install a local MySQL server.
Create a database and configure your connection details (host, username, password) in the config/Database.php file.
4- Web Server
Use a local server like Apache or Nginx to serve your PHP files. You can use:

XAMPP (for both Apache and MySQL)
WAMP (for Windows users)
MAMP (for Mac users)

5- PHPMailer
The project uses PHPMailer for sending emails. It will be installed via Composer, but make sure to have access to an SMTP server (e.g., Gmail, your hosting provider) and configure my credentials in subscribe.php.
# Code 
https://github.com/Monam94/Subscription-System
