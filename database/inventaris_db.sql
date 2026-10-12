CREATE DATABASE IF NOT EXISTS inventaris_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE inventaris_db;

SET FOREIGN_KEY_CHECKS=0;
DROP TABLE IF EXISTS activity_logs;
DROP TABLE IF EXISTS products;
DROP TABLE IF EXISTS categories;
DROP TABLE IF EXISTS suppliers;
SET FOREIGN_KEY_CHECKS=1;

CREATE TABLE categories (
 id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
 name VARCHAR(100) NOT NULL UNIQUE,
 description VARCHAR(255) NULL,
 created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE suppliers (
 id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
 name VARCHAR(150) NOT NULL,
 phone VARCHAR(30) NULL,
 email VARCHAR(150) NULL UNIQUE,
 created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE products (
 id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
 category_id INT UNSIGNED NOT NULL,
 supplier_id INT UNSIGNED NOT NULL,
 sku VARCHAR(40) NOT NULL UNIQUE,
 name VARCHAR(150) NOT NULL,
 description TEXT NULL,
 stock INT UNSIGNED NOT NULL DEFAULT 0,
 price DECIMAL(12,2) UNSIGNED NOT NULL DEFAULT 0.00,
 created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
 updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
 CONSTRAINT fk_product_category FOREIGN KEY (category_id) REFERENCES categories(id) ON UPDATE CASCADE ON DELETE RESTRICT,
 CONSTRAINT fk_product_supplier FOREIGN KEY (supplier_id) REFERENCES suppliers(id) ON UPDATE CASCADE ON DELETE RESTRICT,
 INDEX idx_product_name (name)
) ENGINE=InnoDB;

CREATE TABLE activity_logs (
 id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
 action VARCHAR(30) NOT NULL,
 description VARCHAR(255) NOT NULL,
 product_id INT UNSIGNED NULL,
 created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
 INDEX idx_activity_created (created_at)
) ENGINE=InnoDB;

INSERT INTO categories (name,description) VALUES
('Elektronik','Perangkat elektronik'),('Alat Tulis','Perlengkapan tulis'),
('Furniture','Perabot ruangan'),('Kebutuhan Rumah','Peralatan rumah'),
('Aksesoris','Aksesoris perangkat');

INSERT INTO suppliers (name,phone,email) VALUES
('PT Sumber Teknologi','021-5550101','teknologi@example.com'),
('CV Pena Nusantara','021-5550102','pena@example.com'),
('Mebel Sejahtera','021-5550103','mebel@example.com'),
('UD Rumah Lengkap','021-5550104','rumah@example.com'),
('Global Aksesoris','021-5550105','aksesoris@example.com');

INSERT INTO products (category_id,supplier_id,sku,name,description,stock,price) VALUES
(1,1,'ELK-001','Mouse Wireless','Mouse wireless',25,125000),
(1,1,'ELK-002','Keyboard USB','Keyboard USB standar',18,175000),
(2,2,'ALT-001','Buku Catatan','Buku 100 halaman',80,12000),
(3,3,'FRN-001','Kursi Kerja','Kursi untuk meja kerja',10,450000),
(4,4,'RUM-001','Lampu Meja','Lampu LED hemat energi',14,95000);
