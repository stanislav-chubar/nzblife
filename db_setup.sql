-- NZB.life Database Schema

CREATE DATABASE IF NOT EXISTS nzblife CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE nzblife;

-- Roles table
CREATE TABLE IF NOT EXISTS roles (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) NOT NULL UNIQUE,
    display_name VARCHAR(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO roles (name, display_name) VALUES
('registered', 'Registered'),
('vip', 'VIP'),
('admin', 'Admin')
ON DUPLICATE KEY UPDATE display_name = VALUES(display_name);

-- Users table
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(255) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    role_id INT NOT NULL DEFAULT 1,
    vip_expires_at DATETIME NULL,
    api_hits_today INT NOT NULL DEFAULT 0,
    grabs_total INT NOT NULL DEFAULT 0,
    logout_session_on_ip_change TINYINT NOT NULL DEFAULT 0,
    registered_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    last_login_at DATETIME NULL,
    remember_token VARCHAR(64) NULL,
    reset_token VARCHAR(64) NULL,
    reset_token_expires DATETIME NULL,
    FOREIGN KEY (role_id) REFERENCES roles(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
