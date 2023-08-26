<?php
if (isset($_GET['table'])) {
    $selectedDatabase = $_GET['db'];
    $selectedTable = $_GET['table'];
    if (isset($_GET['Branch_Name'])) {
        $branch_name = $_GET['Branch_Name'];
    }

    // $branchName = $array['Branch_Name'] ?? 'Default Branch';

    // echo $branch_name;
    // Now you have the selected database value in the $selectedDatabase variable
    // You can use this variable to fetch tables or perform any other actions
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
    if ($branch_name) {

        $sql_data = "SELECT * FROM $selectedTable WHERE Branch_Name = '$branch_name'";

        $sql_total = "SELECT SUM(Paid_Amount) AS TotalValue , SUM(Product_Quantity) AS ProductTotal FROM $selectedTable WHERE Branch_Name = '$branch_name'";

        // echo json_encode($sql_total);
        $result_data = $conn->query($sql_data);
        $result_total = $conn->query($sql_total);
    } else {
        $sql_data = "SELECT * FROM $selectedTable";
        $result_data = $conn->query($sql_data);
        $sql_total = "SELECT SUM(Paid_Amount) AS TotalValue , SUM(Product_Quantity) AS ProductTotal FROM $selectedTable";
        $result_total = $conn->query($sql_total);
    }
    // Perform a SELECT query to fetch all data

    $sqlGroup = "SELECT Branch_name FROM $selectedTable group by Branch_Name";
    $branch_group = $conn->query($sqlGroup);
    $branch_names = [];
    // if ($branch_group->num_rows > 0) {
    //     while ($row = $branch_group->fetch_assoc()) {
    //         echo $row["Branch_Name"];
    //     }
    // }

    // Display the results
    while ($row = $branch_group->fetch_assoc()) {
        $branch_names[] = $row["Branch_name"];
    }




    if ($result_data->num_rows > 0) {
        echo "<div class='w-full '>
                    <h1 class='font-bold p-2'>Table Details of <span class='text-blue-400 capitalize'>" . $selectedTable . "</span></h1>
                    <form method='get' action='" . $_SERVER['PHP_SELF'] . "' class='w-full'>
                    <input type='hidden' name='db' value=" . $selectedDatabase . ">
                    <input type='hidden' name='table' value=" . $selectedTable . ">
                    <select name='Branch_Name' value=" . $branch_name . ">
                    <option value='select Branch_Name'>Select Branch Name</option>";

        foreach ($branch_names as $branch_name) {
            echo "<option value='" . $branch_name . "'>" . $branch_name . "</option>";
        }

        echo            "</select>
                    <button class='btn' type='submit'>Select Branch</button>
                    </form>
                </div>
                <div class='overflow-scroll h-[75vh] mb-1'>
                    <table class='flex flex-col text-xs min-w-fit w-full relative mb-4'>
                        <tr>";

        // Rest of your code for table headers and row content goes here



        // Display column names dynamically
        foreach ($column_names as $col) {
            echo "<th class='ring-1 capitalize sticky top-0 p-1.5  bg-green-400 '>$col</th>";
        }

        echo "</tr>";

        // Loop through rows and display data
        while ($row = $result_data->fetch_assoc()) {
            echo "<tr>";

            // Display data dynamically
            foreach ($column_names as $col) {
                echo "<td class='border px-0.5 text-center '>" . $row[$col] . "</td>";
            }

            echo "</tr>";
        }
        // echo ($branch_name);


        echo "<tr class=''>
        <td class='border px-0.5 text-center  '></td>
        <td class='border px-0.5 text-center  '></td>
        <td class='border px-0.5 text-center  '></td>
        <td class='border px-0.5 text-center  '></td>
        <td class='border px-0.5 text-center  '></td>
        <td class='border px-0.5 text-center  '></td>
                <tH class='border px-0.5 text-center  '>Total</tH>";

        while ($row2 = $result_total->fetch_assoc()) {
            echo "<th class='border px-0.5 text-center '>" . $row2['TotalValue'] . "</th>";
            echo "<th class='border px-0.5 text-center '>" . $row2['ProductTotal'] . "</th>";
        }

        echo   "</td>
                </tr>
        </table></div>";
    }
    $conn->close();
} else {
    echo "No database selected.";
}
