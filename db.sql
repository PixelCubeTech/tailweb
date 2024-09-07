CREATE DATABASE teacher_portal;

USE teacher_portal;

CREATE TABLE teachers (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL
);

CREATE TABLE students (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) NOT NULL,
    subject VARCHAR(50) NOT NULL,
    marks INT NOT NULL,
    UNIQUE(name, subject)
);
