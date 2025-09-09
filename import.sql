
CREATE DATABASE IF NOT EXISTS personal_budget;

USE personal_budget;

CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(255) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL
);

CREATE TABLE IF NOT EXISTS budgets (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    amount DECIMAL(10, 2) NOT NULL,
    month DATE NOT NULL,
    FOREIGN KEY (user_id) REFERENCES users(id)
);

CREATE TABLE IF NOT EXISTS categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL
);

CREATE TABLE IF NOT EXISTS transactions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    type ENUM('income', 'expense') NOT NULL,
    amount DECIMAL(10, 2) NOT NULL,
    category_id INT NOT NULL,
    description TEXT,
    transaction_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (category_id) REFERENCES categories(id)
);


-- Example data

INSERT INTO users (username, password) VALUES
('testuser', '$2y$10$c.E4mQU5jk1OxeE59VxQ1Os8r38TIP7xSDMFvwlC7y.z6tLjaXB4q'), -- "password123"
('johny', '$2y$10$c.E4mQU5jk1OxeE59VxQ1Os8r38TIP7xSDMFvwlC7y.z6tLjaXB4q'); -- "password123"

INSERT INTO categories (name) VALUES
('Salary'),
('Groceries'),
('Transport'),
('Dining Out'),
('Health'),
('Education'),
('Travel'),
('Insurance'),
('Savings'),
('Investments'),
('Miscellaneous');

INSERT INTO budgets (user_id, amount, month) VALUES
(1, 1000.00, '2023-06-01'),
(1, 1200.00, '2023-07-01'),
(2, 3000.00, '2023-06-01'),
(2, 3500.00, '2023-07-01');

INSERT INTO transactions (user_id, type, amount, category_id, description, transaction_date) VALUES
(1, 'income', 2000.00, 1, 'Salary for June', '2023-06-10 08:47:23'),
(1, 'expense', 180.00, 2, 'Bought groceries at local market', '2023-06-12 14:32:15'),
(1, 'expense', 45.00, 3, 'Bus ticket to office', '2023-06-15 17:12:08'),
(1, 'income', 1500.00, 1, 'Salary for July', '2023-07-01 09:15:45'),
(1, 'expense', 120.00, 4, 'Dinner with friends', '2023-07-03 20:28:30'),
(2, 'income', 5000.00, 1, 'Monthly salary from employer', '2023-06-01 09:23:45'),
(2, 'expense', 500.00, 2, 'Grocery shopping at local store', '2023-06-05 12:17:22'),
(2, 'expense', 300.00, 3, 'Gas station fuel', '2023-06-10 15:45:10'),
(2, 'expense', 400.00, 5, 'Doctor visit and medication', '2023-06-15 18:32:55'),
(2, 'income', 5200.00, 1, 'July salary payment', '2023-07-01 09:08:30'),
(2, 'expense', 600.00, 6, ' Education', '2023-06-20 10:00:00'),
(2, 'expense', 700.00, 7, ' Travel', '2023-06-25 14:00:00'),
(2, 'expense', 200.00, 8, ' Insurance', '2023-07-05 16:00:00'),
(2, 'income', 1000.00, 9, ' Savings', '2023-07-10 11:00:00'),
(2, 'expense', 800.00, 10, ' Investments', '2023-07-15 13:00:00'),
(2, 'expense', 150.00, 11, ' Miscellaneous', '2023-07-20 17:00:00'),
(2, 'income', 5300.00, 1, ' Salary August', '2023-08-01 09:00:00'),
(2, 'expense', 450.00, 2, ' Groceries August', '2023-08-05 12:00:00'),
(2, 'expense', 350.00, 3, ' Transport August', '2023-08-10 15:00:00'),
(2, 'expense', 500.00, 4, ' Dining Out August', '2023-08-15 20:00:00'),
(2, 'expense', 300.00, 5, ' Health August', '2023-08-20 18:00:00'),
(2, 'income', 2000.00, 9, ' Savings August', '2023-08-25 11:00:00'),
(2, 'expense', 400.00, 6, ' Education August', '2023-08-30 10:00:00'),
(2, 'expense', 600.00, 7, ' Travel August', '2023-09-05 14:00:00'),
(2, 'income', 5400.00, 1, ' Salary September', '2023-09-01 09:00:00'),
(2, 'expense', 250.00, 8, ' Insurance September', '2023-09-10 16:00:00'),
(2, 'expense', 900.00, 10, ' Investments September', '2023-09-15 13:00:00'),
(2, 'expense', 200.00, 11, ' Miscellaneous September', '2023-09-20 17:00:00'),
(2, 'income', 1500.00, 9, ' Savings September', '2023-09-25 11:00:00');
