CREATE DATABASE IF NOT EXISTS smart_classroom_feedback;

USE smart_classroom_feedback;


-- =========================
-- USERS TABLE
-- =========================

CREATE TABLE IF NOT EXISTS users (

    id INT AUTO_INCREMENT PRIMARY KEY,

    name VARCHAR(100) NOT NULL,

    email VARCHAR(100) NOT NULL UNIQUE,

    password VARCHAR(255) NOT NULL,

    role ENUM('student', 'admin') DEFAULT 'student',

    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP

);


-- =========================
-- FACULTY TABLE
-- =========================

CREATE TABLE IF NOT EXISTS faculty (

    id INT AUTO_INCREMENT PRIMARY KEY,

    name VARCHAR(100) NOT NULL,

    department VARCHAR(100) NOT NULL,

    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP

);


-- =========================
-- SUBJECTS TABLE
-- =========================

CREATE TABLE IF NOT EXISTS subjects (

    id INT AUTO_INCREMENT PRIMARY KEY,

    subject_name VARCHAR(100) NOT NULL,

    faculty_id INT NOT NULL,

    semester VARCHAR(20) NOT NULL,

    FOREIGN KEY (faculty_id)
        REFERENCES faculty(id)
        ON DELETE CASCADE

);


-- =========================
-- FEEDBACK TABLE
-- =========================

CREATE TABLE IF NOT EXISTS feedback (

    id INT AUTO_INCREMENT PRIMARY KEY,

    student_id INT NOT NULL,

    faculty_id INT NOT NULL,

    subject_id INT NOT NULL,

    teaching_rating INT NOT NULL,

    clarity_rating INT NOT NULL,

    interaction_rating INT NOT NULL,

    comment TEXT,

    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (student_id)
        REFERENCES users(id)
        ON DELETE CASCADE,

    FOREIGN KEY (faculty_id)
        REFERENCES faculty(id)
        ON DELETE CASCADE,

    FOREIGN KEY (subject_id)
        REFERENCES subjects(id)
        ON DELETE CASCADE

);


-- =========================
-- SAMPLE FACULTY
-- =========================

INSERT INTO faculty (name, department)
VALUES
('Dr. Rahul Patel', 'Computer Science'),
('Prof. Amit Shah', 'Computer Science'),
('Prof. Neha Mehta', 'Information Technology');


-- =========================
-- SAMPLE SUBJECTS
-- =========================

INSERT INTO subjects
(subject_name, faculty_id, semester)
VALUES
('Web Development', 1, '5th'),
('PHP Programming', 2, '5th'),
('Database Management System', 3, '5th'),
('Data Mining', 1, '5th');