<html>

<head>
    <title>Database Dashboard</title>
    <link href="../dist/output.css" rel="stylesheet">
    <!-- <link href="style.css" rel="stylesheet"> -->


</head>

<body class="flex justify-between gap-2">
    <!-- //Add Database form elements -->
    <div class="flex flex-col w-full bg-pink-300 gap-4">

        <h2 class='text-2xl'>Create a New Database</h2>
        <form action="index.php" method="post" class="flex flex-col gap-2 xl:w-3/4 px-2 justify-center" aria-disabled="">
            <input type='hidden' name="formType" value="dbForm"> <!-- Hidden field to identify the form -->
            <label for="">
                Database Name:
            </label>
            <input type=" text" name="db_name" class="h-12 outline-none px-2 rounded-md">
            <button type="submit" class="btn">Add Database</button>
        </form>


        <?php
        $host = "localhost";
        $username = "root";
        $password = "";
        $conn = new mysqli($host, $username, $password);

        //add database
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $formType = $_POST['formType'];
            if ($formType === "dbForm") {
                $newDbName = $_POST["db_name"];

                if ($conn->connect_error) {
                    die("Connection failed: " . $conn->connect_error);
                }
                $sql = "CREATE DATABASE $newDbName";

                if ($conn->query($sql) === TRUE) {
                    echo "Database created successfully";
                } else {
                    echo "Error creating database: " . $conn->error;
                }

                $conn->close();
            }
        }

        $conn = new mysqli($host, $username, $password);
        // Display the list of databases
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        $query = "SHOW DATABASES";
        $result = $conn->query($query);

        $databaseNames = [];
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $databaseNames[] = $row["Database"];
            }
        }
        echo "<pre>";
        print_r($databaseNames);

        ?>
    </div>

    <!-- //show databases and delete form -->
    <div class="w-full">
        <h2>Database List</h2>
        <ul class="flex flex-col gap-1 p-2">
            <?php foreach ($databaseNames as $dbName) : ?>
                <li class='w-full'>
                    <form action="index.php" method="post" class="ring-1 flex p-2 w-full justify-between"> <input type='hidden' name="formType" value="deleteDB"> <!-- Hidden field to identify the form -->
                        <a href="table.php?db=<?php echo $dbName; ?>"><?php echo $dbName ?></a>
                        <input type='hidden' name="databaseName" class=" bg-red-200" value="<?php echo $dbName; ?>"></input>
                        <button class="btn bg-red-300 hover:bg-red-500" type="submit">delete</button>
                    </form>
                </li>
            <?php endforeach; ?>

        </ul>
        <?php
        if ($_SERVER["REQUEST_METHOD"] === "POST") {

            // print_r($_POST);
            // echo "<pre>";
            // print_r($_SERVER);
            // echo "<pre>";
            $formType = $_POST['formType'];
            if ($formType === "deleteDB") {
                $databaseName = $_POST['databaseName'];
                // echo $databaseName;
                $query = "DROP DATABASE $databaseName";
                $result = $conn->query($query);
                if ($result) {
                    $query2 = "SHOW DATABASES";
                    $result = $conn->query($query2);

                    $databaseNames = [];
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            $databaseNames[] = $row["Database"];
                        }
                    }
                    // Search for the database name in the array
                    $index = array_search($databaseName, $databaseNames);

                    if ($index == false) {
                        $response = "success";
                    } else {
                        $response = "not_found";
                    }
                }
            }
        }
        ?>
    </div>


</body>

</html>