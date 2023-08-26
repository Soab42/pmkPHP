<!-- 
echo "<pre>";
print_r($_SERVER);
echo "</pre>";
 -->



<?php

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Get the submitted database name from the form
    $newDbName = $_POST["db_name"];
    $host = "localhost";
    $username = "root";
    $password = "";
    $conn = new mysqli($host, $username, $password);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    print_r($_POST);
    $sql = "CREATE DATABASE $newDbName";

    if ($conn->query($sql) === TRUE) {
        echo "Database created successfully";
    } else {
        echo "Error creating database: " . $conn->error;
    }

    $conn->close();
} else {
    echo "Invalid request.";
}
?>


$query = "SHOW DATABASES";
$result = $conn->query($query);

$databaseNames = [];
if ($result->num_rows > 0) {
while ($row = $result->fetch_assoc()) {
$databaseNames[] = $row["Database"];
}
}
?>
<h1>Database List</h1>
<ul>
    <?php foreach ($databaseNames as $dbName) : ?>
        <li><?php echo $dbName; ?></li>
    <?php endforeach; ?>
</ul>