<?php
// Database Operations:
//-------------------------------------------------------------
CREATE DATABASE database_name;
DROP DATABASE database_name;
USE database_name;



// Table Operations:
//-------------------------------------------------------------

CREATE TABLE table_name (column1 datatype, column2 datatype, ...);
DROP TABLE table_name;
ALTER TABLE table_name ADD column_name datatype;
ALTER TABLE table_name DROP COLUMN column_name;

// Data Manipulation:
//-------------------------------------------------------------

INSERT INTO table_name (column1, column2, ...) VALUES (value1, value2, ...);
SELECT * FROM table_name WHERE condition;
UPDATE table_name SET column1 = value1, column2 = value2 WHERE condition;
DELETE FROM table_name WHERE condition;


// Data Retrieval:
//-------------------------------------------------------------

SELECT column1, column2 FROM table_name;
SELECT * FROM table_name ORDER BY column_name ASC/DESC;
SELECT COUNT(*) FROM table_name;
SELECT DISTINCT column_name FROM table_name;


// Filtering and Sorting:
//-------------------------------------------------------------

WHERE clause for filtering rows based on conditions.
GROUP BY clause for grouping results.
HAVING clause for filtering after grouping.
ORDER BY clause for sorting results.


// Joins and Relationships:
//-------------------------------------------------------------

INNER JOIN, LEFT JOIN, RIGHT JOIN for combining data from multiple tables.
ON clause to specify join conditions.
UNION and UNION ALL for combining result sets.


// Aggregation Functions:
//-------------------------------------------------------------

COUNT(), SUM(), AVG(), MAX(), MIN() for aggregate calculations.


// User and Privilege Management:
//-------------------------------------------------------------

CREATE USER 'username'@'localhost' IDENTIFIED BY 'password';
GRANT privilege ON database.table TO 'username'@'localhost';
REVOKE privilege ON database.table FROM 'username'@'localhost';


// Indexes:
//-------------------------------------------------------------

CREATE INDEX index_name ON table_name (column_name);
DROP INDEX index_name ON table_name;


// Stored Procedures and Functions:
//-------------------------------------------------------------

CREATE PROCEDURE procedure_name(parameters) BEGIN ... END;
CALL procedure_name(parameters);
CREATE FUNCTION function_name(parameters) RETURNS datatype BEGIN ... END;


// Transactions:
//-------------------------------------------------------------

START TRANSACTION;
COMMIT;
ROLLBACK;
?>