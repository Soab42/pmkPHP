<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <h1>Table Details</h1>
    <?php
    if (isset($_GET['table'])) {
        $selectedDatabase = $_GET['db'];
        $selectedTable = $_GET['table'];
        // Now you have the selected database value in the $selectedDatabase variable
        // You can use this variable to fetch tables or perform any other actions
        echo "<pre>";
        echo "Selected DB: " . $selectedDatabase;
        echo "<pre>";
        echo "Selected Table: " . $selectedTable;

        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = $selectedDatabase;

        // Create connection
        $conn = new mysqli($servername, $username, $password, $dbname);

        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Select the appropriate database
        $conn->query("USE $dbname");

        // Perform a SELECT query to fetch column names
        $sql_columns = "SHOW COLUMNS FROM $selectedTable";
        $result_columns = $conn->query($sql_columns);

        $column_names = array();

        if ($result_columns->num_rows > 0) {
            while ($row = $result_columns->fetch_assoc()) {
                $column_names[] = $row['Field'];
            }
        }

        // Perform a SELECT query to fetch all data
        $sql_data = "SELECT * FROM $selectedTable";
        $result_data = $conn->query($sql_data);

        if ($result_data->num_rows > 0) {
            echo "<table class='flex flex-col'>";
            echo "<tr >";

            // Display column names dynamically
            foreach ($column_names as $col) {
                echo "<th class='ring-1'>$col</th>";
            }

            echo "</tr>";

            // Loop through rows and display data
            while ($row = $result_data->fetch_assoc()) {
                echo "<tr>";

                // Display data dynamically
                foreach ($column_names as $col) {
                    echo "<td>" . $row[$col] . "</td>";
                }

                echo "</tr>";
            }

            echo "</table>";
        }
        $conn->close();
    } else {
        echo "No database selected.";
    } ?>
</body>

</html>