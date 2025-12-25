DROP DATABASE IF EXISTS `jibk2.0`;
CREATE DATABASE IF NOT EXISTS `jibk2.0`;

USE `jibk2.0`;

DROP TABLE IF EXISTS users;
CREATE TABLE IF NOT EXISTS users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50) NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL
);

DROP Table IF EXISTS cards;
CREATE TABLE IF NOT EXISTS cards (
    id INT PRIMARY KEY AUTO_INCREMENT,
    bank VARCHAR(25) NOT NULL,
    type ENUM("mastercard", "visa"),
    is_main BOOLEAN NOT NULL DEFAULT 0,
    user_id INT NOT NULL,
    FOREIGN KEY (user_id) 
        REFERENCES users(id)
        ON DELETE CASCADE
);

DROP TABLE IF EXISTS expenses_categories;
CREATE TABLE IF NOT EXISTS expenses_categories (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(25) NOT NULL UNIQUE
);

DROP TABLE IF EXISTS incomes_categories;
CREATE TABLE IF NOT EXISTS incomes_categories (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(25) NOT NULL UNIQUE
);

DROP TABLE IF EXISTS expense_category_limit;
CREATE TABLE IF NOT EXISTS expense_category_limit (
    user_id int not NULL,
    category_id INT NOT NULL,
    `limit` DECIMAL(10, 2) NOT NULL,
    PRIMARY KEY(user_id, category_id),
    FOREIGN KEY(user_id) 
        REFERENCES users(id) 
        ON DELETE CASCADE,
    FOREIGN KEY(category_id) 
        REFERENCES expenses_categories(id) 
        ON DELETE CASCADE
);

DROP TABLE IF EXISTS expenses;
CREATE TABLE IF NOT EXISTS expenses (
    id INT PRIMARY KEY AUTO_INCREMENT,
    title VARCHAR(30) NOT NULL,
    amount DECIMAL(10, 2) NOT NULL,
    description TEXT DEFAULT NULL,
    date DATE DEFAULT (current_time),
    card_id INT NOT NULL,
    category_id INT DEFAULT NULL,

    FOREIGN KEY (card_id) 
        REFERENCES cards(id)
        ON DELETE CASCADE,

    FOREIGN KEY (category_id)
        REFERENCES expenses_categories(id)
        ON DELETE SET NULL
); 

DROP TABLE IF EXISTS incomes;
CREATE TABLE IF NOT EXISTS incomes (
    id INT PRIMARY KEY AUTO_INCREMENT,
    title VARCHAR(30) NOT NULL,
    amount DECIMAL(10, 2) NOT NULL,
    description TEXT DEFAULT NULL,
    date DATE DEFAULT (current_time),
    card_id INT NOT NULL,
    category_id INT DEFAULT NULL,
    FOREIGN KEY (card_id) 
        REFERENCES cards(id)
        ON DELETE CASCADE,
        
    FOREIGN KEY (category_id)
        REFERENCES incomes_categories(id)
        ON DELETE SET NULL
);

DROP TABLE IF EXISTS otp;
CREATE TABLE IF NOT EXISTS otp (
    id INT PRIMARY KEY AUTO_INCREMENT,
    otp int not null,
    expire_at DATETIME NOT NULL,
    user_id int NOT NULL,
    FOREIGN KEY (user_id)
        REFERENCES users(id)
        ON DELEte CASCADE
);

DROP TABLE IF EXISTS incomes_events;
CREATE TABLE IF NOT EXISTS incomes_events (
    id INT PRIMARY KEY AUTO_INCREMENT,
    income_id INT NOT NULL,
    FOREIGN KEY (income_id)
        REFERENCES incomes(id)
        ON DELETE CASCADE
);

DROP TABLE IF EXISTS expenses_events;
CREATE TABLE IF NOT EXISTS expenses_events (
    id INT PRIMARY KEY AUTO_INCREMENT,
    expense_id INT NOT NULL,
    FOREIGN KEY (expense_id)
        REFERENCES expenses(id)
        ON DELETE CASCADE
);

DROP TABLE IF EXISTS transfers;
CREATE TABLE IF NOT EXISTS transfers (
    id INT PRIMARY KEY AUTO_INCREMENT,
    sender_id int not null,
    receiver_id int not null,
    amount DECIMAL(10,2) NOT NULL,
    date DATE DEFAULT (CURRENT_TIME),
    FOREIGN KEY (sender_id)
        REFERENCES users(id)
        ON DELETE CASCADE,

    FOREIGN KEY(receiver_id)
        REFERENCES users(id)
        ON DELETE CASCADE
);

INSERT INTO incomes_categories (id, name) VALUES
(1, 'Salary'),
(2, 'Hourly Wages'),
(3, 'Freelance / Contract'),
(4, 'Business Income'),
(5, 'Bonuses'),
(6, 'Commissions'),
(8, 'Dividends'),
(9, 'Interest'),
(10, 'Rental Income'),
(12, 'Government Benefits'),
(13, 'Pension'),
(15, 'Refunds'),
(16, 'Side Hustle'),
(17, 'Other Income');

INSERT INTO expenses_categories (id, name) VALUES
(1, 'Housing'),
(2, 'Rent'),
(3, 'Mortgage'),
(5, 'Electricity'),
(6, 'Water'),
(7, 'Internet'),
(8, 'Mobile Phone'),
(10, 'Groceries'),
(11, 'Dining Out'),
(12, 'Transportation'),
(14, 'Public Transport'),
(15, 'Vehicle Maintenance'),
(16, 'Insurance'),
(17, 'Health Insurance'),
(18, 'Medical Expenses'),
(19, 'Education'),
(21, 'Books & Supplies'),
(22, 'Entertainment'),
(24, 'Travel'),
(25, 'Clothing'),
(26, 'Personal Care'),
(27, 'Fitness'),
(28, 'Savings'),
(30, 'Debt Repayment'),
(31, 'Credit Card Payment'),
(32, 'Loans'),
(33, 'Donations'),
(34, 'Gifts'),
(35, 'Childcare'),
(37, 'Other Expenses');