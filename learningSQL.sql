-- Create tables
CREATE TABLE categories (
    id INT PRIMARY KEY,
    name VARCHAR(50) NOT NULL
);

CREATE TABLE products (
    id INT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    category_id INT,
    price DECIMAL(10, 2),
    FOREIGN KEY (category_id) REFERENCES categories(id)
);

CREATE TABLE sales (
    id INT PRIMARY KEY,
    product_id INT,
    sale_date DATE,
    quantity INT,
    amount DECIMAL(10, 2),
    FOREIGN KEY (product_id) REFERENCES products(id)
);

-- Insert sample data
INSERT INTO categories (id, name) VALUES (1, 'Electronics');
INSERT INTO products (id, name, category_id, price) VALUES (1, 'Laptop', 1, 999.99);
INSERT INTO sales (id, product_id, sale_date, quantity, amount) VALUES (1, 1, '2023-08-01', 5, 4999.95);

-- Normalize data (you can add more normalization steps as needed)
-- Create a stored procedure to insert a product
DELIMITER //
CREATE PROCEDURE InsertProduct(
    IN productName VARCHAR(255),
    IN categoryId INT,
    IN productPrice DECIMAL(10, 2)
)
BEGIN
    INSERT INTO products (name, category_id, price)
    VALUES (productName, categoryId, productPrice);
END //
DELIMITER ;

-- Create a trigger to update product price in sales table after update
CREATE TRIGGER UpdateSaleAmount
AFTER UPDATE ON products
FOR EACH ROW
BEGIN
    UPDATE sales SET amount = NEW.price * quantity WHERE product_id = NEW.id;
END;

-- Create indexes for better performance
CREATE INDEX idx_category_id ON products (category_id);
CREATE INDEX idx_product_id ON sales (product_id);

-- Pivot sales data for monthly sales report
SET SESSION group_concat_max_len = 1000000;

SELECT
    GROUP_CONCAT(
        DISTINCT CONCAT(
            'SUM(CASE WHEN MONTH(sale_date) = ', m, ' THEN amount ELSE 0 END) AS "', DATE_FORMAT(sale_date, '%b'), '"'
        )
    ) INTO @pivot_columns
FROM sales;

SET @pivot_query = CONCAT(
    'SELECT p.name, ', @pivot_columns, ' 
    FROM sales s
    JOIN products p ON s.product_id = p.id
    GROUP BY p.name'
);

-- Query to retrieve product information along with category name
SELECT p.id, p.name AS product_name, c.name AS category_name, p.price
FROM products p
JOIN categories c ON p.category_id = c.id;

-- Query to retrieve monthly sales per category
SET SESSION group_concat_max_len = 1000000;

SELECT
    GROUP_CONCAT(
        DISTINCT CONCAT(
            'SUM(CASE WHEN MONTH(s.sale_date) = ', m, ' THEN s.amount ELSE 0 END) AS "', DATE_FORMAT(s.sale_date, '%b'), '"'
        )
    ) INTO @pivot_columns
FROM sales s;

SET @pivot_query = CONCAT(
    'SELECT c.name AS category_name, ', @pivot_columns, ' 
    FROM categories c
    LEFT JOIN products p ON c.id = p.category_id
    LEFT JOIN sales s ON p.id = s.product_id
    GROUP BY c.name'
);

PREPARE stmt FROM @pivot_query;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;
