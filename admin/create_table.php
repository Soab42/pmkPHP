<!-- echo "
<pre>";
print_r($_SERVER);
echo "</pre>"; -->




<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $dbName = $_POST["db_name"];
    $tableName = $_POST["table_name"];
    $host = "localhost";
    $username = "root";
    $password = "";
    $conn = new mysqli($host, $username, $password, $dbName);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $sql = "CREATE TABLE $tableName (
id INT AUTO_INCREMENT PRIMARY KEY,
name VARCHAR(255) NOT NULL,
title VARCHAR(255) NOT NULL
)";

    if ($conn->query($sql) === TRUE) {
        echo "Table created successfully";
    } else {
        echo "Error creating table: " . $conn->error;
    }

    $conn->close();
}
?>