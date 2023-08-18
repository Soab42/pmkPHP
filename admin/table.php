<html>

<head>
    <title>Database Dashboard</title>
    <link href="../dist/output.css" rel="stylesheet">
    <!-- <link href="style.css" rel="stylesheet"> -->


</head>

<body class="flex justify-between gap-2">
    <!-- //Add Database form elements -->
    <div class="flex flex-col w-full bg-pink-300 gap-4 p-2">
        <?php
        if (isset($_GET['db'])) {
            $selectedDatabase = $_GET['db'];
            echo "Selected database: " . $selectedDatabase . "<hr/><br/>";
            $host = "localhost";
            $username = "root";
            $password = "";
            $databaseName = $selectedDatabase;
            $conn = new mysqli($host, $username, $password, $databaseName);

            //add database
            if ($_SERVER["REQUEST_METHOD"] === "POST") {
                $formType = $_POST['formType'];
                if ($formType === "tableForm") {
                    $newTableName = $_POST["table_name"];
                    $columns = $_POST['columns'];
                    // echo '<pre>';
                    // print_r($columns);
                    if ($conn->connect_error) {
                        die("Connection failed: " . $conn->connect_error);
                    }
                    // Create the SQL statement for creating the table
                    $sql = "CREATE TABLE $newTableName (id INT AUTO_INCREMENT PRIMARY KEY";

                    foreach ($columns as $column) {
                        $sql .= ", $column VARCHAR(255)";
                    }

                    $sql .= ")";

                    // Execute the SQL query
                    if ($conn->query($sql) === TRUE) {
                        echo "<h1><span class='font-bold uppercase text-blue-600'>
                        $newTableName</span> Table created successfully</h1>";
                    } else {
                        echo "Error creating table";
                    }
                }
            }


            // Display the list of databases
            $query = "SELECT table_name FROM INFORMATION_SCHEMA.TABLES WHERE table_schema = '$selectedDatabase'";
            $result = $conn->query($query);

            $tablesNames = [];
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $tablesNames[] = $row["table_name"];
                }
            }

            // echo "<pre>";
            // print_r($tablesNames);

            echo '<hr/><h2 class="text-2xl">Create a New Table On <span class="font-bold uppercase text-blue-600">'
                . $selectedDatabase . '  </span>              
                    </h2><hr/>
                    <form action="table.php?db=' . $selectedDatabase . '" method="post" class="flex flex-col gap-2 xl:w-3/4 px-2 justify-center">
                        <input type="hidden" name="formType" value="tableForm"> <!-- Hidden field to identify the form -->
                        <label for="table_name">Table Name:</label>
                        <input type="text" id="table_name" name="table_name" class="h-12 outline-none px-2 rounded-md">
                        <div id="columns" class="flex flex-col">
                            <label for="column1">Column 1:</label>
                            <input type="text" id="column1" name="columns[]" class="h-12 outline-none px-2 rounded-md">
                        </div>
                        <button class="btn" type="button" id="addColumnBtn">More Columns</button>
                        <button class="btn" type="submit">Add Table</button>
                    </form>';
        } else {
            echo "No database selected.";
        }

        ?>
        <?php

        ?>
    </div>

    <!-- //show databases and delete form -->
    <?php

    if ($tablesNames) {
        echo '<div class="w-full p-2">
           <p class="font-bold min-w-full py-4 bg-red-300 text-center text-2xl">Table List of <span class="font-bold uppercase text-blue-600">'
            . $selectedDatabase . '  </span> </p>
           <ul class=" flex flex-col gap-0 p-2">';

        foreach ($tablesNames as $tablesName) {
            echo '<li class="w-full h-14 flex justify-center">
               <form action="table.php?db=' . $selectedDatabase . '&table=' . $tablesName . '" method="post" class="ring-1 flex p-2 w-full justify-between">
                   <input type="hidden" name="formType" value="deleteTable">
                   <a href="tableDetails.php?db=' . $selectedDatabase . '&table=' . $tablesName . '" class="m-0 p-0  capitalize">' . $tablesName . '</a>
                   <input type="hidden" name="tablesName" class="bg-red-200" value="' . $tablesName . '">
                   <button class=" bg-red-300 text-xs px-2  capitalize rounded-full hover:bg-red-500" type="submit">delete</button>
               </form>
           </li>';
        }

        echo '</ul>';
    } else {
        echo "<div class='font-bold w-full py-4 bg-red-300 text-center text-2xl'> No Table Created</div>";
    }

    // delete the table from the database table if it exists and is not deleted already by another process and the table is deleted successfully
    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        // print_r($_POST);
        // echo "<pre>";
        // print_r($_SERVER);
        // echo "<pre>";
        $formType = $_POST["formType"];
        if ($formType === "deleteTable") {
            $tablesName = $_POST["tablesName"];
            echo $tablesName;
            $conn->query("USE $databaseName");
            $query = "DROP TABLE $tablesName";
            $result = $conn->query($query);
            if ($result) {
                $index = array_search($tablesName, $tablesNames);
                if ($index == false) {
                    $response = " deleted successfully";
                } else {
                    $response = "not_found";
                }
                echo $response;
            }
        }
    }

    ?> </div>
    <script>
        const addColumnBtn = document.getElementById('addColumnBtn');
        const columnsDiv = document.getElementById('columns');

        let columnCount = 1;

        addColumnBtn.addEventListener('click', () => {
            const newColumn = document.createElement('div')
            newColumn.className = 'flex flex-col';
            newColumn.innerHTML = `
                <label for="column${columnCount + 1}">Column ${columnCount + 1}:</label>
                <input type="text" id="column${columnCount + 1}" name="columns[]" class="h-12 outline-none px-2 rounded-md">
            `;
            columnsDiv.appendChild(newColumn);
            columnCount++;
        });
    </script>


</body>

</html>