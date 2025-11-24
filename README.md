
# 🚀 SmartTask Portal – Task & Productivity Management System

SmartTask Portal is a modern, web-based Task & Productivity Management System designed to help users create tasks, track progress, monitor productivity, and maintain activity logs — all inside a highly responsive Neon UI dashboard.

The platform includes User Panel + Admin Panel, making it suitable for students, professionals, and organizations.

# 📌 Table of Contents

## About the Project

Features

Tech Stack

System Architecture

Database Structure

Project Folder Structure

Installation Guide

Screenshots

Future Enhancements

Contributing

License

Developer

# 📖 About the Project

SmartTask Portal is a fully functional web application built using PHP & MySQL that helps users:

Manage daily tasks

Track productivity

Store activity logs

View insights through charts

Update personal profile

It also features a full Admin Panel where administrators can:

Manage users

Manage tasks

View system-wide activity logs

Monitor system analytics

This project is ideal for academic mini-projects, internships, and learning full-stack web development.

# ⭐ Features
🧑‍💻 User Features

User Registration & Login

Profile Update + Image Upload

Add / Edit / Delete Tasks

Task Status: Pending / Completed

Weekly Productivity Insights

Graphs using Chart.js

User Activity Timeline (Auto Logged)

Responsive Neon UI

Logout System

# 🛡️ Admin Features

Admin Login

Admin Dashboard

View all users

Delete users

View all tasks

View user login activity

System analytics

Secure admin session

## 🛠️ Tech Stack
## Frontend

### HTML

### CSS (Neon UI)

### JavaScript

### Backend

### PHP (Core PHP)

## Database

### MySQL (phpMyAdmin)

### Libraries & Tools

### Chart.js

### FontAwesome

### XAMPP (Apache + MySQL)


## 🏗️ System Architecture
<img width="595" height="664" alt="System Architecture" src="https://github.com/user-attachments/assets/7a845092-a7c9-4eaf-a018-64282e284a2a" />



## 🗄️ Database Structure
## ✔ users
Column	Type
id	INT (PK)
full_name	VARCHAR
email	VARCHAR
password	VARCHAR
education_level	VARCHAR
profile_image	VARCHAR
role	ENUM('user','admin')
created_at	TIMESTAMP
## ✔ admin
Column	Type
id	INT
email	VARCHAR
password	VARCHAR (MD5)
## ✔ todos
Column	Type
id	INT
user_id	INT (FK)
task	VARCHAR
description	TEXT
status	pending/completed
created_at	TIMESTAMP
## ✔ activity_logs
Column	Type
id	INT
user_id	INT or NULL
activity	VARCHAR
created_at	TIMESTAMP



⚙️ Installation Guide

## Follow these steps to run the project locally:

Step 1: Install XAMPP

Download: https://www.apachefriends.org/

Step 2: Move Project to htdocs

Paste folder here:

C:/xampp/htdocs/SmartTask/

Step 3: Create Database

Open phpMyAdmin → New Database → Name:

smart_task

Step 4: Import SQL

Import database.sql

Step 5: Update config.php (if needed_
$host = "localhost";
$dbname = "smart_task";
$username = "root";
$password = "";

Step 6: Run the Project
http://localhost/SmartTask/


## 🖼️ Screenshots (Add after hosting)
/screenshots/login.png
/screenshots/dashboard.png
/screenshots/tasks.png
/screenshots/profile.png
/screenshots/admin_panel.png
/screenshots/activity.png

## 🚀 Future Enhancements

Dark/Light mode

Email Alerts for Tasks

Mobile Application (Flutter/React Native)

Calendar-based Task View

Team Collaboration Features

AI-based Task Suggestions

Weekly Reports via Email

## 🤝 Contributing

Contributions are welcome!
Fork the repo → Create a branch → Commit changes → Make a pull request.

## 📜 License

This project is licensed under the MIT License.
Free to modify and use.

## 👤 Developer

Abhishek Kumar
Abhishek Rajput
Abhishesh Kumar
Decash Yadav
SmartTask Portal – Mini Project
B.Tech CSE
## 🚀 Live Demo

[View Live Demo](https://abhi14324.github./)

