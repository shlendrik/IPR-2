CREATE DATABASE IF NOT EXISTS db101;
USE db101;


CREATE TABLE IF NOT EXISTS api_keys (
    id INT AUTO_INCREMENT PRIMARY KEY,
    api_key VARCHAR(255) NOT NULL UNIQUE,
    user_id INT,
    is_active BOOLEAN DEFAULT TRUE
);


CREATE TABLE IF NOT EXISTS options (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    value TEXT
);

INSERT INTO api_keys (api_key, user_id, is_active)
VALUES ('$2y$10$N9qo8uLOickgx2ZMRZo5e.4H9g5b7g7Qv85G5x/5fJhz0u3X0MJpW', 1, TRUE);

INSERT INTO options (name, value) VALUES ('theme', 'dark');