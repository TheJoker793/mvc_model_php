-- =============================================================
-- BASE DE DONNÉES : php_mvc_shop
-- =============================================================

CREATE DATABASE IF NOT EXISTS php_mvc_shop
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

USE php_mvc_shop;

-- -------------------------------------------------------------
-- TABLE : users
-- -------------------------------------------------------------
CREATE TABLE users (
    id          INT AUTO_INCREMENT PRIMARY KEY,
    name        VARCHAR(100)        NOT NULL,
    email       VARCHAR(150)        NOT NULL UNIQUE,
    password    VARCHAR(255)        NOT NULL,
    role        ENUM('admin','user') NOT NULL DEFAULT 'user',
    created_at  TIMESTAMP           DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- -------------------------------------------------------------
-- TABLE : categories
-- -------------------------------------------------------------
CREATE TABLE categories (
    id          INT AUTO_INCREMENT PRIMARY KEY,
    name        VARCHAR(100)  NOT NULL,
    slug        VARCHAR(120)  NOT NULL UNIQUE,
    description TEXT,
    created_at  TIMESTAMP     DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- -------------------------------------------------------------
-- TABLE : products
-- -------------------------------------------------------------
CREATE TABLE products (
    id          INT AUTO_INCREMENT PRIMARY KEY,
    category_id INT           NOT NULL,
    name        VARCHAR(150)  NOT NULL,
    slug        VARCHAR(180)  NOT NULL UNIQUE,
    description TEXT,
    price       DECIMAL(10,2) NOT NULL DEFAULT 0.00,
    stock       INT           NOT NULL DEFAULT 0,
    image       VARCHAR(255),
    created_at  TIMESTAMP     DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_product_category
        FOREIGN KEY (category_id) REFERENCES categories(id)
        ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB;

-- -------------------------------------------------------------
-- DONNÉES DE TEST
-- -------------------------------------------------------------
INSERT INTO users (name, email, password, role) VALUES
('Admin', 'admin@shop.com',
 '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', -- password: password
 'admin'),
('Jean Dupont', 'user@shop.com',
 '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', -- password: password
 'user');

INSERT INTO categories (name, slug, description) VALUES
('Électronique',   'electronique',   'Appareils électroniques et gadgets'),
('Vêtements',      'vetements',      'Mode et accessoires'),
('Alimentation',   'alimentation',   'Produits alimentaires et boissons');

INSERT INTO products (category_id, name, slug, description, price, stock) VALUES
(1, 'Smartphone XL',  'smartphone-xl',  'Un téléphone puissant',   599.99, 15),
(1, 'Casque Bluetooth','casque-bt',      'Son cristallin sans fil', 89.99,  30),
(2, 'T-Shirt Coton',  't-shirt-coton',  'Coton bio, toutes tailles',19.99, 100),
(3, 'Café Arabica',   'cafe-arabica',   'Café premium 250g',        12.50,  50);
