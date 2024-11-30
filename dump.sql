CREATE DATABASE crud_tutorial;

USE crud_tutorial;

CREATE TABLE entries (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    description TEXT NOT NULL
);
