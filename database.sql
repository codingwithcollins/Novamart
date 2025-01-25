-- Create the database
CREATE DATABASE novamart;

-- Use the database
USE novamart;

-- Table for storing user information
CREATE TABLE users (
    user_id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    phone VARCHAR(20),
    address TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Table for product categories
CREATE TABLE categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    image VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Table for storing products
CREATE TABLE products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    category_id INT NOT NULL,
    name VARCHAR(255) NOT NULL,
    description TEXT,
    image_url VARCHAR(255),
    rating DECIMAL(3, 2),
    reviews_count INT,
    original_price DECIMAL(10, 2),
    discounted_price DECIMAL(10, 2),
    discount_percentage DECIMAL(5, 2),
    quantity_in_stock INT,
    is_featured BOOLEAN,
    is_most_popular BOOLEAN,
    is_just_arrived BOOLEAN,
    is_best_selling BOOLEAN,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE CASCADE
);

-- Table for storing orders
CREATE TABLE orders (
    order_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    total_price DECIMAL(10, 2) NOT NULL,
    status VARCHAR(50) NOT NULL,
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE
);

-- Table for storing order details (products in an order)
CREATE TABLE order_details (
    order_detail_id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    product_id INT NOT NULL,
    quantity INT NOT NULL,
    price DECIMAL(10, 2) NOT NULL,
    FOREIGN KEY (order_id) REFERENCES orders(order_id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
);

-- Table for storing feedback
CREATE TABLE feedback (
    feedback_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    message TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE
);

-- Table for storing cart items
CREATE TABLE cart (
    cart_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    product_id INT NOT NULL,
    quantity INT NOT NULL DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
);

-- Table for storing wishlist items
CREATE TABLE wishlist (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    product_id INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
);

-- Table for storing admin users
CREATE TABLE admins (
    admin_id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Table for storing shipping options
CREATE TABLE shipping_options (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    price DECIMAL(10, 2) NOT NULL
);

-- Insert shipping options for Kenya
INSERT INTO shipping_options (name, price) VALUES 
('Standard Shipping (within Kenya)', 5.00),
('Express Shipping (within Kenya)', 12.00),
('Overnight Shipping (within Kenya)', 20.00),
('Same Day Delivery (Nairobi)', 15.00),
('Rural Delivery (outside Nairobi)', 10.00);

-- Insert sample users
INSERT INTO users (username, email, password, phone, address) 
VALUES 
('john_doe', 'john@example.com', 'password123', '123-456-7890', '123 Elm St, Springfield, IL'),
('jane_smith', 'jane@example.com', 'password456', '987-654-3210', '456 Oak St, Springfield, IL');

-- Insert sample admins
INSERT INTO admins (username, email, password) 
VALUES 
('admin1', 'admin1@example.com', 'adminpass123'),
('admin2', 'admin2@example.com', 'adminpass456');

-- Insert sample product categories with images
INSERT INTO categories (name, image) 
VALUES 
('Bakery', 'bakery.jpg'),
('Dairy', 'dairy.jpg'),
('Snacks', 'snacks.jpg'),
('Fruits', 'fruits.jpg'),
('Vegetables', 'vegetables.jpg'),
('Beverages', 'beverages.jpg');

-- Insert sample products
INSERT INTO products (category_id, name, description, image_url, rating, reviews_count, original_price, discounted_price, discount_percentage, quantity_in_stock, is_featured, is_most_popular, is_just_arrived, is_best_selling, created_at)
VALUES
-- Bakery
(1, 'Whole Wheat Sandwich Bread', 'A delicious and healthy whole wheat sandwich bread.', 'images/product-thumb-1.png', 4.5, 222, 24.00, 18.00, 25.00, 100, TRUE, FALSE, FALSE, TRUE, CURRENT_TIMESTAMP),
(1, 'Croissant', 'Buttery and flaky croissant, freshly baked.', 'images/product-thumb-2.png', 4.7, 190, 3.50, 3.00, 14.29, 120, FALSE, FALSE, FALSE, FALSE, CURRENT_TIMESTAMP),
(1, 'Sourdough Bread', 'Artisan sourdough bread with a tangy flavor.', 'images/product-thumb-3.png', 4.8, 250, 6.00, 5.50, 8.33, 150, FALSE, TRUE, FALSE, FALSE, CURRENT_TIMESTAMP),

-- Dairy
(2, 'Organic Milk', 'Fresh organic milk from grass-fed cows.', 'images/product-thumb-4.png', 4.8, 150, 4.50, 4.00, 11.11, 200, FALSE, TRUE, FALSE, TRUE, CURRENT_TIMESTAMP),
(2, 'Cheddar Cheese', 'Rich and creamy cheddar cheese.', 'images/product-thumb-5.png', 4.6, 180, 7.00, 6.50, 7.14, 100, FALSE, FALSE, TRUE, FALSE, CURRENT_TIMESTAMP),
(2, 'Yogurt', 'Plain and natural yogurt with no additives.', 'images/product-thumb-6.png', 4.4, 95, 2.50, 2.20, 12.00, 300, TRUE, FALSE, FALSE, FALSE, CURRENT_TIMESTAMP),

-- Snacks
(3, 'Crunchy Granola', 'Healthy granola with mixed nuts and honey.', 'images/product-thumb-7.png', 4.3, 180, 8.00, 6.50, 18.75, 120, FALSE, FALSE, TRUE, FALSE, CURRENT_TIMESTAMP),
(3, 'Potato Chips', 'Classic salted potato chips.', 'images/product-thumb-8.png', 4.2, 210, 1.50, 1.30, 13.33, 400, TRUE, FALSE, FALSE, TRUE, CURRENT_TIMESTAMP),
(3, 'Trail Mix', 'A blend of dried fruits, nuts, and seeds.', 'images/product-thumb-9.png', 4.5, 100, 5.00, 4.50, 10.00, 80, FALSE, TRUE, FALSE, FALSE, CURRENT_TIMESTAMP),

-- Fruits
(4, 'Fresh Bananas', 'Ripe and naturally sweet bananas.', 'images/product-thumb-10.png', 4.7, 300, 2.50, 2.20, 12.00, 250, FALSE, FALSE, TRUE, FALSE, CURRENT_TIMESTAMP),
(4, 'Apples', 'Crisp and juicy apples, perfect for snacking.', 'images/product-thumb-11.png', 4.6, 280, 3.00, 2.70, 10.00, 220, TRUE, FALSE, FALSE, TRUE, CURRENT_TIMESTAMP),
(4, 'Oranges', 'Sweet and tangy oranges packed with Vitamin C.', 'images/product-thumb-12.png', 4.5, 260, 2.80, 2.50, 10.71, 300, FALSE, TRUE, FALSE, FALSE, CURRENT_TIMESTAMP),

-- Vegetables
(5, 'Carrots', 'Crunchy and sweet carrots, perfect for salads.', 'images/product-thumb-13.png', 4.6, 150, 1.80, 1.50, 16.67, 300, TRUE, FALSE, FALSE, FALSE, CURRENT_TIMESTAMP),
(5, 'Spinach', 'Fresh and organic spinach leaves.', 'images/product-thumb-14.png', 4.7, 140, 2.50, 2.20, 12.00, 200, FALSE, TRUE, FALSE, TRUE, CURRENT_TIMESTAMP),
(5, 'Broccoli', 'Tender broccoli, perfect for steaming or stir-frying.', 'images/product-thumb-15.png', 4.8, 130, 3.50, 3.20, 8.57, 150, FALSE, FALSE, TRUE, FALSE, CURRENT_TIMESTAMP),

-- Beverages
(6, 'Fresh Orange Juice', 'Refreshing orange juice made from fresh oranges.', 'images/product-thumb-16.png', 4.8, 400, 4.50, 4.00, 11.11, 250, FALSE, TRUE, FALSE, TRUE, CURRENT_TIMESTAMP),
(6, 'Green Tea', 'Soothing green tea with natural antioxidants.', 'images/product-thumb-17.png', 4.6, 320, 3.50, 3.00, 14.29, 300, FALSE, FALSE, TRUE, FALSE, CURRENT_TIMESTAMP),
(6, 'Coffee Beans', 'Premium quality coffee beans for a rich brew.', 'images/product-thumb-18.png', 4.7, 280, 12.00, 10.50, 12.50, 150, TRUE, FALSE, FALSE, FALSE, CURRENT_TIMESTAMP),
(6, 'Sparkling Water', 'Refreshing and crisp sparkling water.', 'images/product-thumb-19.png', 4.5, 220, 1.50, 1.30, 13.33, 500, FALSE, TRUE, FALSE, TRUE, CURRENT_TIMESTAMP),
(6, 'Lemonade', 'Zesty and sweet homemade lemonade.', 'images/product-thumb-20.png', 4.6, 260, 3.00, 2.70, 10.00, 300, FALSE, FALSE, TRUE, FALSE, CURRENT_TIMESTAMP),

-- Most popular products
(1, 'Chocolate Chip Cookies', 'Delicious homemade chocolate chip cookies.', 'images/product-thumb-21.png', 4.9, 350, 5.00, 4.50, 10.00, 200, FALSE, TRUE, FALSE, FALSE, CURRENT_TIMESTAMP),
(2, 'Greek Yogurt', 'Creamy and tangy Greek yogurt.', 'images/product-thumb-22.png', 4.8, 300, 3.00, 2.70, 10.00, 150, FALSE, TRUE, FALSE, FALSE, CURRENT_TIMESTAMP),
(3, 'Salted Pretzels', 'Crispy and salty pretzels.', 'images/product-thumb-23.png', 4.7, 250, 2.50, 2.20, 12.00, 180, FALSE, TRUE, FALSE, FALSE, CURRENT_TIMESTAMP),
(4, 'Orange Juice', 'Freshly squeezed orange juice.', 'images/product-thumb-24.png', 4.8, 400, 4.00, 3.50, 12.50, 100, FALSE, TRUE, FALSE, FALSE, CURRENT_TIMESTAMP);

-- Insert sample orders
INSERT INTO orders (user_id, total_price, status) 
VALUES 
(1, 40.00, 'Pending'),
(2, 25.50, 'Completed');

-- Insert sample order details
INSERT INTO order_details (order_id, product_id, quantity, price) 
VALUES 
(1, 1, 2, 18.00),
(1, 3, 1, 5.50),
(2, 4, 3, 4.00);

-- Insert sample feedback
INSERT INTO feedback (user_id, message) 
VALUES 
(1, 'Great selection of products!'),
(2, 'Fast delivery and excellent customer service.');

-- Insert sample cart items
INSERT INTO cart (user_id, product_id, quantity) 
VALUES 
(1, 5, 3),
(2, 6, 2);
