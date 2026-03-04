-- Database creation and setup for PHP Lab Registration System
-- Run this in phpMyAdmin or MySQL Workbench

CREATE DATABASE IF NOT EXISTS php_lab_db;
USE php_lab_db;

-- Create registrations table
CREATE TABLE registrations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    fname VARCHAR(100) NOT NULL,
    lname VARCHAR(100) NOT NULL,
    address TEXT NOT NULL,
    country VARCHAR(100) NOT NULL,
    gender ENUM('Male', 'Female') NOT NULL,
    skills JSON NOT NULL,
    username VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    department VARCHAR(100) NOT NULL,
    code VARCHAR(20) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Sample data (optional)
INSERT INTO registrations (fname, lname, address, country, gender, skills, username, password, department, code) VALUES
('John', 'Doe', '123 Main St, City', 'USA', 'Male', '["PHP", "MySQL"]', 'johndoe', 'password123', 'OpenSource', 'Sh68Sa'),
('Jane', 'Smith', '456 Oak Ave, Town', 'UK', 'Female', '["PHP", "J2SE", "PostgreSQL"]', 'janesmith', 'password456', 'OpenSource', 'Sh68Sa');