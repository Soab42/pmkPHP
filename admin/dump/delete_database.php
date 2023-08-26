<?php
// delete_database.php

// Sample database entries (replace this with your actual data source)
$host = "localhost";
$username = "root";
$password = "";
$conn = new mysqli($host, $username, $password);


if ($_SERVER["REQUEST_METHOD"] === "POST") {
    print_r($_POST);
    echo "<pre>";
    print_r($_SERVER);
    echo "<pre>";
    // $databaseName = $_POST['databaseName'];
    // $query = "DROP DATABASE $databaseName";
    // $result = $conn->query($query);
    // if ($result) {
    //     $query2 = "SHOW DATABASES";
    //     $result = $conn->query($query2);

    //     $databaseNames = [];
    //     if ($result->num_rows > 0) {
    //         while ($row = $result->fetch_assoc()) {
    //             $databaseNames[] = $row["Database"];
    //         }
    //     }
    //     // Search for the database name in the array
    //     $index = array_search($databaseName, $databaseNames);

    //     if ($index == false) {
    //         $response = "success";
    //     } else {
    //         $response = "not_found";
    //     }
    // }
} else {
    // $response = "error";
}

// echo $response;
