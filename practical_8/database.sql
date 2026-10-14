CREATE DATABASE IF NOT EXISTS student_crud;
USE student_crud;

CREATE TABLE IF NOT EXISTS students (
    id INT AUTO_INCREMENT PRIMARY KEY,
    enrollment_no TEXT NOT NULL,
    student_name TEXT NOT NULL,
    email TEXT NOT NULL,
    department VARCHAR(100) NOT NULL,
    semester INT NOT NULL
);