-- CAFEESQUINA — Esquema completo
CREATE DATABASE IF NOT EXISTS cafeesquina CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE cafeesquina;

CREATE TABLE IF NOT EXISTS users (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    full_name VARCHAR(100) DEFAULT NULL,
    phone VARCHAR(20) DEFAULT NULL,
    role ENUM('client', 'admin') NOT NULL DEFAULT 'client',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS categories (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(80) NOT NULL UNIQUE,
    description VARCHAR(255) DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS products (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    category_id INT UNSIGNED NOT NULL,
    name VARCHAR(150) NOT NULL,
    description TEXT NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    image VARCHAR(255) DEFAULT NULL,
    status ENUM('available', 'unavailable') NOT NULL DEFAULT 'available',
    featured TINYINT(1) NOT NULL DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE RESTRICT
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS promotions (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(150) NOT NULL,
    description TEXT NOT NULL,
    image VARCHAR(255) DEFAULT NULL,
    start_date DATE NOT NULL,
    end_date DATE NOT NULL,
    active TINYINT(1) NOT NULL DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS orders (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id INT UNSIGNED DEFAULT NULL,
    product_id INT UNSIGNED NOT NULL,
    product_name VARCHAR(150) NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    channel VARCHAR(30) NOT NULL DEFAULT 'whatsapp',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- Admin: Admin123!
INSERT INTO users (username, email, password, full_name, role) VALUES
('admin', 'admin@cafeesquina.local', '$2y$10$pOwGqTRdBvbDQMz3SB4YO.C4wFKMH2NXuWkQxKS8IPH6ZepBfbTty', 'Administrador', 'admin')
ON DUPLICATE KEY UPDATE password = VALUES(password), role = 'admin';

INSERT INTO categories (name, description) VALUES
('Cafés calientes', 'Espresso, americano, capuchino y más'),
('Cafés fríos', 'Cold brew, iced latte, nitro'),
('Frappés', 'Bebidas frías cremosas'),
('Tés', 'Tés artesanales e infusiones'),
('Chocolates', 'Chocolate caliente premium'),
('Postres', 'Brownies, cheesecakes, galletas'),
('Pasteles', 'Porciones y pasteles del día'),
('Sándwiches', 'Opciones saladas frescas'),
('Desayunos', 'Combos para empezar el día'),
('Especialidades de la casa', 'Creaciones exclusivas CAFEESQUINA')
ON DUPLICATE KEY UPDATE name = VALUES(name);

INSERT INTO products (category_id, name, description, price, image, status, featured) VALUES
(1, 'Espresso Esquina', 'Shot intenso de café artesanal.', 2.50, 'https://images.unsplash.com/photo-1514432324607-a09d9b4aefdd?w=600', 'available', 1),
(1, 'Capuchino Vainilla', 'Espuma sedosa con toque de vainilla.', 3.75, 'https://images.unsplash.com/photo-1572442388796-11668a67e3d9?w=600', 'available', 1),
(2, 'Iced Caramel Latte', 'Latte frío con caramelo.', 4.25, 'https://images.unsplash.com/photo-1517701604599-b8c035bafa0e?w=600', 'available', 1),
(3, 'Frappé Moka', 'Chocolate y café batido.', 4.99, 'https://images.unsplash.com/photo-1572490122747-3968b75cc699?w=600', 'available', 0),
(6, 'Cheesecake Maracuyá', 'Cremoso con coulis tropical.', 3.99, 'https://images.unsplash.com/photo-1524351199678-941a58a4df50?w=600', 'available', 1),
(9, 'Desayuno Esquina', 'Café + croissant + jugo.', 6.50, 'https://images.unsplash.com/photo-1533089860903-50726b201f0c?w=600', 'available', 1),
(10, 'Affogato Especial', 'Helado de vainilla con espresso caliente.', 5.25, 'https://images.unsplash.com/photo-1559496417-4139a8a0f254?w=600', 'available', 1);

INSERT INTO promotions (title, description, image, start_date, end_date, active) VALUES
('Combo Mañanero', 'Café mediano + pastel por $5.99', 'https://images.unsplash.com/photo-1495474472287-4d71bcdd2085?w=800', CURDATE(), DATE_ADD(CURDATE(), INTERVAL 30 DAY), 1),
('2x1 Frappés', 'Viernes de frappés: lleva 2 y paga 1', 'https://images.unsplash.com/photo-1461023058943-07fcbe16d735?w=800', CURDATE(), DATE_ADD(CURDATE(), INTERVAL 14 DAY), 1);
