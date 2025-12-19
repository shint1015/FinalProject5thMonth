# FinalProject5thMonth

This repository contains the **PHP + MySQL REST-style Back-End API** developed for the **Elephant Samurai website** as part of the **Content Management with PHP, MySQL, and WordPress**.

The API replaces the mock API previously used in the front-end SPA and provides a secure, persistent, and well-structured backend that supports authentication, authorization, CRUD operations, and audit logging.

---

## 🔗 Project Context

In the previous course, we built a **React Single Page Application** for the **Elephant Samurai** startup concept and mocked API responses using hard-coded data or tools like MockAPI.

This project delivers the **real backend** that:
- Persists data using **MySQL**
- Exposes **JSON-based REST APIs**
- Implements **authentication, authorization, and audit trails**
- Matches and refines the original **ERD and OpenAPI specification**

---

## 🧠 Project Overview

The Elephant Samurai backend API is designed to support core business features such as:

- User authentication and role management
- Core domain resources (based on the Elephant Samurai product concept)
- Secure CRUD operations
- Aggregated and joined data endpoints
- Audit logging for nonrepudiation

All endpoints are built using **pure PHP**, **prepared statements**, and **session-based authentication**.

---

## 🛠️ Tech Stack

- **Language:** PHP
- **Database:** MySQL
- **Architecture:** REST-style API
- **Authentication:** PHP Sessions
- **Security:** Password hashing, prepared statements, input validation
- **Documentation:** OpenAPI (YAML/JSON)

---

## 📌 Core Features

### 1. API & Domain Design
- REST-style endpoints for each main resource
- Supports:
  - `GET /resources`
  - `POST /resources`
  - `PUT /resources`
  - `DELETE /resources`
- OpenAPI specification included (`openapi.yaml`)

### 2. Database Design (MySQL)
- Normalized relational schema
- Primary & foreign key constraints
- Referential integrity enforced
- Realistic sample data (30 rows)

### 3. Authentication & Sessions
- User registration and login
- Passwords hashed using `password_hash()`
- Verification using `password_verify()`
- Session-based authentication
- Endpoint to check logged-in user:

### 4. Authorization (Role-Based Access)
- Roles supported:
- **Admin** – full CRUD access
- **User** – restricted access
- Authorization checks enforced at controller/service level
- Sensitive operations protected (update/delete/admin-only endpoints)

### 5. CRUD & Business Logic
- Full CRUD for at least two core entities
- Joined / aggregated endpoints, such as:
- Listing entities with related data
- Summary counts or totals
- Clean separation of concerns:
- Controllers
- Services
- Repositories

### 6. Security Practices
- Prepared statements for **all** SQL queries
- Input sanitization and validation
- No raw `$_POST` / `$_GET` usage
- Session regeneration on login
- Secure handling of user data

### 7. Error Handling & Logging
- Structured JSON error responses
- Try–catch blocks for DB operations
- Application logs stored in:

### 8. Audit Trail (Nonrepudiation)
- `audit_logs` table implemented
- Tracks:
- User ID
- Action (create, update, delete)
- Entity & entity ID
- Timestamp
- IP address
- Logs all critical CRUD actions on main entities

### 🚀 How to Import
1.Import the database using exported_database.sql
2.Update database credentials in: /config/database.php
3.Run the project locally using Apache or PHP built-in server
4.Test endpoints using Postman 

### Team Member 
1.Shintaro Miyata
2.Aiya Tossapol
3.Daiki Ebisuya



