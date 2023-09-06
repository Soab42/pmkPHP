<?php

require('config.php');
class Branch extends dbConfig
{
    protected $hostName;
    protected $userName;
    protected $password;
    protected $dbName;

    private $billPaid = 'consumer_bill_paid';
    private $billPaidReport = 'consumer_bill_paid_report';
    private $billPaidReport2 = 'consumer_bill_paid_report2';
    private $branchReport = 'consumer_branch_report';
    private $order = 'consumer_order';
    private $product = 'consumer_product';
    private $extraOrder = 'consumer_transaction_from_extra';
    private $transFrom = 'consumer_trans_from';
    private $TransTo = 'consumer_trans_this';
    private $SoldOut = 'consumer_soldout';

    private $dbConnect = false;

    public function __construct()
    {
        if (!$this->dbConnect) {
            $database = new dbConfig();
            $this->hostName = $database->serverName;
            $this->userName = $database->userName;
            $this->password = $database->password;
            $this->dbName = $database->dbName;

            // echo "$database->serverName";

            $conn = new mysqli($this->hostName, $this->userName, $this->password, $this->dbName);
            if ($conn->connect_error) {
                die("Error failed to connect to MySQL: " . $conn->connect_error);
            } else {
                $this->dbConnect = $conn;
            }
        }
    }
    private function getData($sqlQuery)
    {
        $result = mysqli_query($this->dbConnect, $sqlQuery);
        if (!$result) {
            die('Error in query: ' . mysqli_error());
        }
        $data = array();
        while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
            $data[] = $row;
        }
        return $data;
    }

    public function getTableName()
    {
        // Display the list of databases
        $query = "SELECT table_name FROM INFORMATION_SCHEMA.TABLES WHERE table_schema = '$this->dbName'";
        $result = $this->dbConnect->query($query);

        $tablesNames = [];
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                // Clean up the table name by removing "consumer" prefix and replacing underscores with spaces

                $tablesNames[] = $row["table_name"];
            }
        }

        $currentURL = $_SERVER['REQUEST_URI'];
        $prefixToRemove = "pmk/consumer/";
        // Remove the prefix and extract the path without query string
        $pathWithoutPrefix = str_replace($prefixToRemove, "", $currentURL);
        $pathInfo = parse_url($pathWithoutPrefix, PHP_URL_PATH);
        $currentFilename = pathinfo($pathInfo, PATHINFO_FILENAME);



        foreach ($tablesNames as $tableName) {
            echo "<li class='btn" . ($tableName === $currentFilename ? " active" : "") . "'>
                    <a href=" . $tableName . ".php>";

            $cleanedTableName = str_replace('_', ' ', str_ireplace('consumer', '', $tableName));
            echo $cleanedTableName;

            echo "</a></li>";
        }
    }

    public function getBranchNames()
    {
        $query = "SELECT `Branch Name` FROM $this->order group by `Branch Name`";

        // Update with your actual table name
        $result = mysqli_query($this->dbConnect, $query);
        print_r($result);

        $branchNames = array();
        while ($row = mysqli_fetch_assoc($result)) {
            $branchNames[] = $row['Branch Name'];
        }
        // print_r($branchNames);
        foreach ($branchNames as $branchName) {
            $selected = ($_GET['branch'] == $branchName) ? 'selected' : '';
            echo "<option value='$branchName' $selected>$branchName</option>";
        }
    }
    public function getDistributorNames()
    {
        $query = "SELECT `Distributor Name` FROM $this->order group by `Distributor Name`";

        // Update with your actual table name
        $result = mysqli_query($this->dbConnect, $query);
        // print_r($result);

        $DistributorNames = array();
        while ($row = mysqli_fetch_assoc($result)) {

            $DistributorNames[] = $row['Distributor Name'];
        }
        // print_r($branchNames);
        foreach ($DistributorNames as $distributorName) {
            $selected = ($_GET['distributor'] == $distributorName) ? 'selected' : '';
            echo "<option value='$distributorName' $selected>$distributorName</option>";
        }
    }
    public function getProductsType()
    {
        $query = "SELECT `Product Name` FROM $this->product group by `Product Name`";

        // Update with your actual table name
        $result = mysqli_query($this->dbConnect, $query);
        // print_r($result);

        $productTypes = array();
        while ($row = mysqli_fetch_assoc($result)) {

            $productTypes[] = $row['Product Name'];
        }
        // print_r($branchNames);
        foreach ($productTypes as $productType) {
            $selected = ($_GET['type'] == $productType) ? 'selected' : '';
            echo "<option value='$productType' $selected>$productType</option>";
        }
    }

    public function getOrderList()

    {

        $tableName = $this->order;

        // get search parameters
        $branchName = isset($_GET['branch']) ? mysqli_real_escape_string($this->dbConnect, $_GET['branch']) : '';
        $distributorName = isset($_GET['distributor']) ? mysqli_real_escape_string($this->dbConnect, $_GET['distributor']) : '';
        $productStatus = isset($_GET['bill']) ? mysqli_real_escape_string($this->dbConnect, $_GET['bill']) : '';
        $productType = isset($_GET['type']) ? mysqli_real_escape_string($this->dbConnect, $_GET['type']) : '';
        // initiaL SQL
        $sql = "SELECT co.*, IFNULL(cbp.Paid_Amount, 0) AS Paid_Amount
        FROM consumer_order co
        LEFT JOIN consumer_bill_paid cbp
        ON co.`Order ID` = cbp.Order_Code
          WHERE 1";
        // Add conditions if branch or distributor is selected
        if (!empty($branchName) || !empty($distributorName) || !empty($productStatus)) {

            if (!empty($distributorName)) {
                if ($distributorName !== 'select distributor') {
                    $sql .= " AND `Distributor Name` = '$distributorName'";
                }
            }

            if (!empty($branchName)) {
                if ($branchName !== 'select branch') {
                    $sql .= " AND `Branch Name` like '$branchName%'";
                }
            }
            if (!empty($productStatus)) {
                if ($productStatus !== 'select Bill') {
                    $sql .= " AND `Bill Pay` = '$productStatus'";
                }
            }
            if (!empty($productType)) {
                if ($productType !== 'select type') {
                    $sql .= " AND `Product Name` like '$productType%'";
                }
            }
        }
        // echo $sql . "<br>";

        $result = mysqli_query($this->dbConnect, $sql);

        if (!$result) {
            // Handle database query error
            return;
        }

        $data = array();
        while ($row = mysqli_fetch_assoc($result)) {
            $data[] = $row;
        }

        if (empty($data)) {
            echo "No records found.";
            return;
        }

        echo "<table class='border-2 border-black px-1-collapse  px-1-gray-300 text-center  overflow-scroll relative'>";
        echo "<tr class=' p-1 sticky top-0 bg-green-300'>";
        foreach (array_keys($data[0]) as $columnName) {
            echo "<th class='border-2 border-black px-1-2 px-1-black  p-1 text-xs capitalize'>$columnName</th>";
        }
        // echo "</tr>";


        $totalOrderQuantity = 0;
        $totalShippedQuantity = 0;
        $totalReceivedQuantity = 0;
        $countPaidQuantity = 0;
        $countUnPaidQuantity = 0;
        $totalPurchase = 0;
        $totalPaid = 0;

        foreach ($data as $value) {
            echo "<tr class='border-2 border-black px-1 p-1'>";
            foreach ($value as $key => $val) {
                echo "<td class='border-2 border-black px-1 p-1 text-xs'>$val</td>";
                if ($key === 'Order Quantity') {
                    $totalOrderQuantity += $val;
                } elseif ($key === 'Shiped Quantity') {
                    $totalShippedQuantity += $val;
                } elseif ($key === 'Received Quantity') {
                    $totalReceivedQuantity += $val;
                } elseif ($key === 'Bill Pay') {
                    if ($val === 'Paid') {
                        $countPaidQuantity++;
                    } else {
                        $countUnPaidQuantity++;
                    }
                } elseif ($key === 'Purchase Price') {
                    if (is_numeric($val)) {

                        $totalPurchase += $val;
                    }
                } elseif ($key === 'Paid_Amount') {
                    if (is_numeric($val)) {

                        $totalPaid += $val;
                    }
                }
            }
            // echo "</tr>";
        }

        // Display the total row
        echo "<tr class='border-2 border-black px-1 p-1'>";
        echo "<th colspan='8' class='border-2 border-black px-1 p-1 text-xs'>Total (Total Paid = $countPaidQuantity, Total Unpaid = $countUnPaidQuantity )</th>";
        echo "<th colspan='' class='border-2 border-black px-1 p-1 text-xs'>$totalOrderQuantity</th>";

        echo "<th colspan='' class='border-2 border-black px-1 p-1 text-xs'> $totalShippedQuantity</th>";

        echo "<th colspan='' class='border-2 border-black px-1 p-1 text-xs'> $totalReceivedQuantity</th>";
        echo "<th colspan='' class='border-2 border-black px-1 p-1 text-xs'> $totalPurchase</th>";
        echo "<th colspan='3' class='border-2 border-black px-1 p-1 text-xs'> </th>";
        echo "<th colspan='' class='border-2 border-black px-1 p-1 text-xs'> $totalPaid</th>";
        // echo "</tr>";
        echo "</table>";
    }
    public function getBillPaidRegister()

    {

        $tableName = $this->billPaid;

        // get search parameters
        $branchName = isset($_GET['branch']) ? mysqli_real_escape_string($this->dbConnect, $_GET['branch']) : '';

        // initiaL SQL
        $sql = "SELECT * FROM $tableName WHERE 1";

        // Add conditions if branch or distributor is selected
        if (!empty($branchName)) {


            if (!empty($branchName)) {
                if ($branchName !== 'select branch') {
                    $sql .= " AND `Branch_Name` like '$branchName%'";
                }
            }
        }
        // echo $sql . "<br>";

        $result = mysqli_query($this->dbConnect, $sql);

        if (!$result) {
            // Handle database query error
            return;
        }

        $data = array();
        while ($row = mysqli_fetch_assoc($result)) {
            $data[] = $row;
        }

        if (empty($data)) {
            echo "No records found.";
            return;
        }

        echo "<table class=' px-1-collapse  border-2 border-black px-1-gray-300 text-center  overflow-scroll relative'>";
        echo "<tr class=' p-1 sticky top-0 bg-teal-500'>";
        foreach (array_keys($data[0]) as $columnName) {
            echo "<th class=' px-1-2 border-2 border-black px-1-black  p-1 text-xs capitalize'>$columnName</th>";
        }
        // echo "</tr>";



        $totalPaid = 0;

        foreach ($data as $value) {
            echo "<tr class='border-2 border-black px-1 p-1'>";
            foreach ($value as $key => $val) {
                echo "<td class='border-2 border-black px-1 p-1 text-xs'>$val</td>";
                if ($key === 'Paid_Amount') {
                    $totalPaid += $val;
                }
            }
            // echo "</tr>";
        }

        // Display the total row
        echo "<tr class='border-2 border-black px-1 p-1'>";
        echo "<th colspan='8' class='border-2 border-black px-1 p-1 text-xs'>Total</th>";
        echo "<th colspan='' class='border-2 border-black px-1 p-1 text-xs'>$totalPaid</th>";
        // echo "</tr>";
        echo "</table>";
    }
    public function getBillPaidReport()

    {

        $tableName = $this->billPaidReport;
        // initiaL SQL
        $sql = "SELECT * FROM $tableName WHERE 1";

        $result = mysqli_query($this->dbConnect, $sql);

        if (!$result) {
            // Handle database query error
            return;
        }

        $data = array();
        while ($row = mysqli_fetch_assoc($result)) {
            $data[] = $row;
        }

        if (empty($data)) {
            echo "No records found.";
            return;
        }

        echo "<table class='border-2 border-black px-1-collapse  px-1-gray-300 text-center  overflow-scroll relative'>";
        echo "<tr class=' p-1 sticky top-0 bg-teal-500'>";
        foreach (array_keys($data[0]) as $columnName) {
            echo "<th class='border-2 border-black px-1-2 px-1-black  p-1 text-xs capitalize'>$columnName</th>";
        }
        // echo "</tr>";

        $totalPurchase = 0;
        $totalTransfer = 0;
        $totalHoPayment = 0;
        $totalMIS = 0;
        $totalBalance = 0;
        $totalTotalAIS = 0;
        $totalHo = 0;
        $totalVariance = 0;

        foreach ($data as $value) {
            echo "<tr class='border-2 border-black px-1 p-1'>";
            foreach ($value as $key => $val) {
                echo "<td class='border-2 border-black px-1 p-1 text-xs'>$val</td>";
                if ($key === 'Purchase Price') {
                    $totalPurchase += $val;
                }
                if ($key === 'Purchase Price (Trans Rec Prod)') {
                    $totalTransfer += $val;
                }
                if ($key === 'Payable Amount To HO') {
                    $totalHoPayment += $val;
                }
                if ($key === 'Paid Amount (MIS)') {
                    $totalMIS += $val;
                }
                if ($key === 'Balance') {
                    $totalTotalAIS += $val;
                }
                if ($key === 'Paid Amount (Accounts)') {
                    $totalBalance += $val;
                }
                if ($key === 'Paid Amount (HO)') {
                    $totalHo += $val;
                }
                if ($key === 'Varience MIS & AIS') {
                    $totalVariance += $val;
                }
            }
            // echo "</tr>";
        }

        // Display the total row
        echo "<tr class='border-2 border-black px-1 p-1'>";
        echo "<th colspan='2' class='border-2 border-black px-1 p-1 text-xs'>Total</th>";
        echo "<th colspan='' class='border-2 border-black px-1 p-1 text-xs'>$totalPurchase</th>";
        echo "<th colspan='' class='border-2 border-black px-1 p-1 text-xs'>$totalTransfer</th>";
        echo "<th colspan='' class='border-2 border-black px-1 p-1 text-xs'>$totalHoPayment</th>";
        echo "<th colspan='' class='border-2 border-black px-1 p-1 text-xs'>$totalMIS</th>";
        echo "<th colspan='' class='border-2 border-black px-1 p-1 text-xs'>$totalBalance</th>";
        echo "<th colspan='' class='border-2 border-black px-1 p-1 text-xs'>$totalTotalAIS</th>";
        echo "<th colspan='' class='border-2 border-black px-1 p-1 text-xs'>$totalHo</th>";
        echo "<th colspan='' class='border-2 border-black px-1 p-1 text-xs'>$totalVariance</th>";
        // echo "</tr>";
        echo "</table>";
    }
    public function getBillPaidReport2()

    {

        $tableName = $this->billPaidReport2;

        // get search parameters
        $branchName = isset($_GET['branch']) ? mysqli_real_escape_string($this->dbConnect, $_GET['branch']) : '';
        $distributorName = isset($_GET['distributor']) ? mysqli_real_escape_string($this->dbConnect, $_GET['distributor']) : '';


        // initiaL SQL
        $sql = "SELECT * FROM $tableName WHERE 1";

        // Add conditions if branch or distributor is selected
        if (!empty($branchName) || !empty($distributorName) || !empty($productStatus)) {

            if (!empty($distributorName)) {
                if ($distributorName !== 'select distributor') {
                    $sql .= " AND `Brand Name` = '$distributorName'";
                }
            }

            if (!empty($branchName)) {
                if ($branchName !== 'select branch') {
                    $sql .= " AND `Branch Name` like '$branchName%'";
                }
            }
        }
        // echo $sql . "<br>";

        $result = mysqli_query($this->dbConnect, $sql);

        if (!$result) {
            // Handle database query error
            return;
        }

        $data = array();
        while ($row = mysqli_fetch_assoc($result)) {
            $data[] = $row;
        }

        if (empty($data)) {
            echo "No records found.";
            return;
        }

        echo "<table class='border-2 border-black px-1-collapse  px-1-gray-300 text-center  overflow-scroll relative'>";
        echo "<tr class=' p-1 sticky top-0 bg-teal-500'>";
        foreach (array_keys($data[0]) as $columnName) {
            echo "<th class='border-2 border-black px-1-2 px-1-black  p-1 text-xs capitalize'>$columnName</th>";
        }
        // echo "</tr>";


        $totalShippedQuantity = 0;
        $totalReceivedQuantity = 0;
        $totalNonReceivedQuantity = 0;
        $purchasePrice = 0;
        $paidAmount = 0;
        $dueAmount = 0;


        foreach ($data as $value) {
            echo "<tr class='border-2 border-black px-1 p-1'>";
            foreach ($value as $key => $val) {
                echo "<td class='border-2 border-black px-1 p-1 text-xs'>$val</td>";
                if ($key === 'Shiped Quantity') {
                    $totalShippedQuantity += $val;
                } elseif ($key === 'Recieved Quantity') {
                    $totalReceivedQuantity += $val;
                } elseif ($key === 'Non-Rec Quantity') {
                    $totalNonReceivedQuantity += $val;
                } elseif ($key === 'Purchase Price') {
                    $purchasePrice += $val;
                } elseif ($key === 'Paid Amount') {
                    $paidAmount += $val;
                } elseif ($key === 'Due Amount') {
                    $dueAmount += $val;
                }
            }
            // echo "</tr>";
        }

        // Display the total row
        echo "<tr class='border-2 border-black px-1 p-1'>";
        echo "<th colspan='4' class='border-2 border-black px-1 p-1 text-xs'>Total</th>";
        echo "<th colspan='' class='border-2 border-black px-1 p-1 text-xs'>$totalShippedQuantity</th>";
        echo "<th colspan='' class='border-2 border-black px-1 p-1 text-xs'>$totalReceivedQuantity</th>";
        echo "<th colspan='' class='border-2 border-black px-1 p-1 text-xs'>$totalNonReceivedQuantity</th>";
        echo "<th colspan='' class='border-2 border-black px-1 p-1 text-xs'>$purchasePrice</th>";

        echo "<th colspan='' class='border-2 border-black px-1 p-1 text-xs'> $paidAmount</th>";

        echo "<th colspan='' class='border-2 border-black px-1 p-1 text-xs'> $dueAmount</th>";
        // echo "</tr>";
        echo "</table>";
    }
    public function getBranchReport()

    {

        $tableName = $this->branchReport;

        // get search parameters
        $branchName = isset($_GET['branch']) ? mysqli_real_escape_string($this->dbConnect, $_GET['branch']) : '';
        $distributorName = isset($_GET['distributor']) ? mysqli_real_escape_string($this->dbConnect, $_GET['distributor']) : '';
        $productStatus = isset($_GET['bill']) ? mysqli_real_escape_string($this->dbConnect, $_GET['bill']) : '';

        // initiaL SQL
        $sql = "SELECT * FROM $tableName WHERE 1";

        // Add conditions if branch or distributor is selected
        if (!empty($branchName) || !empty($distributorName) || !empty($productStatus)) {

            if (!empty($distributorName)) {
                if ($distributorName !== 'select distributor') {
                    $sql .= " AND `Distributor Name` = '$distributorName'";
                }
            }

            if (!empty($branchName)) {
                if ($branchName !== 'select branch') {
                    $sql .= " AND `Branch Name` like '$branchName%'";
                }
            }
            if (!empty($productStatus)) {
                if ($productStatus !== 'select Bill') {
                    $sql .= " AND `Bill Pay` = '$productStatus'";
                }
            }
        }
        // echo $sql . "<br>";

        $result = mysqli_query($this->dbConnect, $sql);

        if (!$result) {
            // Handle database query error
            return;
        }

        $data = array();
        while ($row = mysqli_fetch_assoc($result)) {
            $data[] = $row;
        }

        if (empty($data)) {
            echo "No records found.";
            return;
        }

        echo "<table class='border-2 border-black px-1-collapse  px-1-gray-300 text-center  overflow-scroll relative'>";
        echo "<tr class=' p-1 sticky top-0 bg-teal-500'>";
        foreach (array_keys($data[0]) as $columnName) {
            echo "<th class='border-2 border-black px-1-2 px-1-black  p-1 text-xs capitalize'>$columnName</th>";
        }
        // echo "</tr>";


        $totalOrderQuantity = 0;
        $totalShippedQuantity = 0;
        $totalReceivedQuantity = 0;

        foreach ($data as $value) {
            echo "<tr class='border-2 border-black px-1 p-1'>";
            foreach ($value as $key => $val) {
                echo "<td class='border-2 border-black px-1 p-1 text-xs'>$val</td>";
                if ($key === 'Order Quantity') {
                    $totalOrderQuantity += $val;
                } elseif ($key === 'Shiped Quantity') {
                    $totalShippedQuantity += $val;
                } elseif ($key === 'Received Quantity') {
                    $totalReceivedQuantity += $val;
                }
            }
            // echo "</tr>";
        }


        // echo "</tr>";
        echo "</table>";
    }
    public function getProductList()

    {

        $tableName = $this->product;

        // get search parameters
        $branchName = isset($_GET['branch']) ? mysqli_real_escape_string($this->dbConnect, $_GET['branch']) : '';
        $distributorName = isset($_GET['distributor']) ? mysqli_real_escape_string($this->dbConnect, $_GET['distributor']) : '';

        $productStatus = isset($_GET['status']) ? mysqli_real_escape_string($this->dbConnect, $_GET['status']) : '';

        $productType = isset($_GET['type']) ? mysqli_real_escape_string($this->dbConnect, $_GET['type']) : '';

        // initiaL SQL
        $sql = "SELECT * FROM $tableName WHERE 1";

        // Add conditions if branch or distributor is selected
        if (!empty($branchName) || !empty($distributorName) || !empty($productStatus) || (!empty($productType))) {

            if (!empty($distributorName)) {
                if ($distributorName !== 'select distributor') {
                    $sql .= " AND `Distributor Name` = '$distributorName'";
                }
            }

            if (!empty($branchName)) {
                if ($branchName !== 'select branch') {
                    $sql .= " AND `Branch Name` like '$branchName%'";
                }
            }
            if (!empty($productStatus)) {
                if ($productStatus !== 'select product status') {
                    $sql .= " AND `Product Status` = '$productStatus'";
                }
            }
            if (!empty($productType)) {
                if ($productType !== 'select product type') {
                    $sql .= " AND `Product Name` like '$productType%'";
                }
            }
        }
        // echo $sql . "<br>";

        $result = mysqli_query($this->dbConnect, $sql);

        if (!$result) {
            // Handle database query error
            return;
        }

        $data = array();
        while ($row = mysqli_fetch_assoc($result)) {
            $data[] = $row;
        }

        if (empty($data)) {
            echo "No records found.";
            return;
        }

        echo "<table class='border-2 border-black px-1-collapse  px-1-gray-300 text-center  overflow-scroll relative'>";
        echo "<tr class=' p-1 sticky top-0 bg-[lightseagreen] text-slate-700 shadow-lg'>";
        foreach (array_keys($data[0]) as $columnName) {
            echo "<th class='border-2 border-black px-1   p-1 text-xs capitalize'>$columnName</th>";
        }
        // echo "</tr>";
        $countStockIn = 0;
        $countSoldOut = 0;
        $countTransferred = 0;
        $totalProduct = count($data);
        $totalPurchasePrice = 0;
        $totalSaleablePrice = 0;
        $totalStaffCommission = 0;

        foreach ($data as $value) {
            echo "<tr class='border-2 border-black px-1 p-1'>";
            foreach ($value as $key => $val) {
                echo "<td class='border-2 border-black px-1 p-1 text-xs'>$val</td>";
                if ($key === 'Product Purchase Price') {
                    $totalPurchasePrice += $val;
                } elseif ($key === 'Product Salable Price') {
                    $totalSaleablePrice += $val;
                } elseif ($key === 'Staff Comission') {
                    $totalStaffCommission += $val;
                } elseif ($key === 'Product Status') {
                    if ($val === 'Stock in') {
                        $countStockIn++;
                    } elseif ($val === 'Soldout') {
                        $countSoldOut++;
                    } else {
                        $countTransferred++;
                    }
                }
            }
            // echo "</tr>";
        }

        // Display the total row
        echo "<tr class='border-2 border-black px-1 p-1 text-slate-700 shadow-lg bg-[lightseagreen]'>";

        echo "<th colspan='11' class='border-2 border-black px-1 text-xs p-2'>Total   (Total Product = $totalProduct , Total SoldOut= $countSoldOut, Total  StockIn = $countStockIn ,Total Transferred = $countTransferred)</th>";


        echo "<th colspan='' class='border-2 border-black px-1 p-1 text-xs'>$totalPurchasePrice</th>";

        echo "<th colspan='' class='border-2 border-black px-1 p-1 text-xs'> $totalSaleablePrice</th>";

        echo "<th colspan='' class='border-2 border-black px-1 p-1 text-xs'> $totalStaffCommission</th><th colspan='5'></th>";
        // echo "</tr>";
        echo "</table>";
    }
    public function getProductListExtra()

    {

        $tableName = $this->extraOrder;

        // get search parameters
        $branchName = isset($_GET['branch']) ? mysqli_real_escape_string($this->dbConnect, $_GET['branch']) : '';
        $distributorName = isset($_GET['distributor']) ? mysqli_real_escape_string($this->dbConnect, $_GET['distributor']) : '';



        // initiaL SQL
        $sql = "SELECT * FROM $tableName WHERE 1";

        // Add conditions if branch or distributor is selected
        if (!empty($branchName) || !empty($distributorName) || !empty($productStatus) || (!empty($productType))) {

            if (!empty($distributorName)) {
                if ($distributorName !== 'select distributor') {
                    $sql .= " AND `Distributor Name` = '$distributorName'";
                }
            }

            if (!empty($branchName)) {
                if ($branchName !== 'select branch') {
                    $sql .= " AND `Branch Name` like '$branchName%'";
                }
            }
        }
        // echo $sql . "<br>";

        $result = mysqli_query($this->dbConnect, $sql);

        if (!$result) {
            // Handle database query error
            return;
        }

        $data = array();
        while ($row = mysqli_fetch_assoc($result)) {
            $data[] = $row;
        }

        if (empty($data)) {
            echo "No records found.";
            return;
        }

        echo "<table class='border-2 border-black px-1-collapse  px-1-gray-300 text-center  overflow-scroll relative'>";
        echo "<tr class=' p-1 sticky top-0 bg-teal-500'>";
        foreach (array_keys($data[0]) as $columnName) {
            echo "<th class='border-2 border-black px-1-2 px-1-black  p-1 text-xs capitalize'>$columnName</th>";
        }
        // echo "</tr>";


        $totalOrderQuantity = 0;
        $totalShippedQuantity = 0;
        $totalReceivedQuantity = 0;

        foreach ($data as $value) {
            echo "<tr class='border-2 border-black px-1 p-1'>";
            foreach ($value as $key => $val) {
                echo "<td class='border-2 border-black px-1 p-1 text-xs'>$val</td>";
                if ($key === 'Order Quantity') {
                    $totalOrderQuantity += $val;
                } elseif ($key === 'Shiped Quantity') {
                    $totalShippedQuantity += $val;
                } elseif ($key === 'Received Quantity') {
                    $totalReceivedQuantity += $val;
                }
            }
            // echo "</tr>";
        }

        // Display the total row
        echo "<tr class='border-2 border-black px-1 p-1'>";

        echo "<th colspan='9' class='border-2 border-black px-1 p-1 text-xs'>Total   </th>";


        echo "<th colspan='' class='border-2 border-black px-1 p-1 text-xs'>$totalOrderQuantity</th>";

        echo "<th colspan='' class='border-2 border-black px-1 p-1 text-xs'> $totalShippedQuantity</th>";

        echo "<th colspan='' class='border-2 border-black px-1 p-1 text-xs'> $totalReceivedQuantity</th>";
        // echo "</tr>";
        echo "</table>";
    }
    public function getTransferListFrom()

    {
        $tableName = $this->transFrom;

        // initiaL SQL
        $sql = "SELECT * FROM $tableName WHERE 1";
        // echo $sql . "<br>";
        $result = mysqli_query($this->dbConnect, $sql);

        if (!$result) {
            // Handle database query error
            return;
        }

        $data = array();
        while ($row = mysqli_fetch_assoc($result)) {
            $data[] = $row;
        }

        if (empty($data)) {
            echo "No records found.";
            return;
        }

        echo "<table class='border-2 border-black px-1-collapse  px-1-gray-300 text-center  overflow-scroll relative'>";
        echo "<tr class=' p-1 sticky top-0 bg-teal-500'>";
        foreach (array_keys($data[0]) as $columnName) {
            echo "<th class='border-2 border-black px-1-2 px-1-black  p-1 text-xs capitalize'>$columnName</th>";
        }
        // echo "</tr>";

        foreach ($data as $value) {
            echo "<tr class='border-2 border-black px-1 p-1'>";
            foreach ($value as $key => $val) {
                echo "<td class='border-2 border-black px-1 p-1 text-xs'>$val</td>";
            }
            // echo "</tr>";
        }

        // echo "</tr>";
        echo "</table>";
    }
    public function getTransferListThis()

    {
        $tableName = $this->TransTo;

        // initiaL SQL
        $sql = "SELECT * FROM $tableName WHERE 1";
        // echo $sql . "<br>";
        $result = mysqli_query($this->dbConnect, $sql);

        if (!$result) {
            // Handle database query error
            return;
        }

        $data = array();
        while ($row = mysqli_fetch_assoc($result)) {
            $data[] = $row;
        }

        if (empty($data)) {
            echo "No records found.";
            return;
        }

        echo "<table class='border-2 border-black px-1-collapse  px-1-gray-300 text-center  overflow-scroll relative'>";
        echo "<tr class=' p-1 sticky top-0 bg-teal-500'>";
        foreach (array_keys($data[0]) as $columnName) {
            echo "<th class='border-2 border-black px-1-2 px-1-black  p-1 text-xs capitalize'>$columnName</th>";
        }
        // echo "</tr>";

        foreach ($data as $value) {
            echo "<tr class='border-2 border-black px-1 p-1'>";
            foreach ($value as $key => $val) {
                echo "<td class='border-2 border-black px-1 p-1 text-xs'>$val</td>";
            }
            // echo "</tr>";
        }

        // echo "</tr>";
        echo "</table>";
    }
    public function getSoldout()
    { {

            $tableName = $this->SoldOut;

            // get search parameters
            $branchName = isset($_GET['branch']) ? mysqli_real_escape_string($this->dbConnect, $_GET['branch']) : '';
            $distributorName = isset($_GET['distributor']) ? mysqli_real_escape_string($this->dbConnect, $_GET['distributor']) : '';

            $productStatus = isset($_GET['status']) ? mysqli_real_escape_string($this->dbConnect, $_GET['status']) : '';

            $productType = isset($_GET['type']) ? mysqli_real_escape_string($this->dbConnect, $_GET['type']) : '';

            // initiaL SQL
            $sql = "SELECT * FROM $tableName WHERE 1";

            // Add conditions if branch or distributor is selected
            if (!empty($branchName) || !empty($distributorName) || !empty($productStatus) || (!empty($productType))) {

                if (!empty($distributorName)) {
                    if ($distributorName !== 'select distributor') {
                        $sql .= " AND `Distributor Name` = '$distributorName'";
                    }
                }

                if (!empty($branchName)) {
                    if ($branchName !== 'select branch') {
                        $sql .= " AND `Branch Name` like '$branchName%'";
                    }
                }
                if (!empty($productStatus)) {
                    if ($productStatus !== 'select product status') {
                        $sql .= " AND `Product Status` = '$productStatus'";
                    }
                }
                if (!empty($productType)) {
                    if ($productType !== 'select type') {
                        $sql .= " AND `Product Name` like '$productType%'";
                    }
                }
            }
            // echo $sql . "<br>";

            $result = mysqli_query($this->dbConnect, $sql);

            if (!$result) {
                // Handle database query error
                return;
            }

            $data = array();
            while ($row = mysqli_fetch_assoc($result)) {
                $data[] = $row;
            }

            if (empty($data)) {
                echo "No records found.";
                return;
            }

            echo "<table class='border-2 border-black px-1-collapse  px-1-gray-300 text-center  overflow-scroll relative'>";
            echo "<tr class=' p-1 sticky top-0 bg-[lightseagreen] text-slate-700 shadow-lg'>";
            foreach (array_keys($data[0]) as $columnName) {
                echo "<th class='border-2 border-black px-1   p-1 text-xs capitalize'>$columnName</th>";
            }
            // echo "</tr>";

            $totalProduct = count($data);
            $totalPurchasePrice = 0;
            $totalSaleablePrice = 0;
            $totalStaffCommission = 0;
            $netProfit = 0;

            foreach ($data as $value) {
                echo "<tr class='border-2 border-black px-1 p-1'>";
                foreach ($value as $key => $val) {
                    echo "<td class='border-2 border-black px-1 p-1 text-xs'>$val</td>";
                    if ($key === 'Purchase Price') {
                        $totalPurchasePrice += $val;
                    } elseif ($key === 'Salable Price') {
                        $totalSaleablePrice += $val;
                    } elseif ($key === 'Staff Comission') {
                        $totalStaffCommission += $val;
                    }
                    $netProfit = $totalSaleablePrice - $totalPurchasePrice - $totalStaffCommission;
                }
                // echo "</tr>";
            }

            // Display the total row
            echo "<tr class='border-2 border-black px-1 p-1 text-slate-700 shadow-lg bg-[lightseagreen]'>";

            echo "<th colspan='10' class='border-2 border-black px-1 text-xs p-2'>Total   (Total Product = $totalProduct )</th>";


            echo "<th colspan='' class='border-2 border-black px-1 p-1 text-xs'> $totalSaleablePrice</th>";
            echo "<th colspan='' class='border-2 border-black px-1 p-1 text-xs'>$totalPurchasePrice</th>";


            echo "<th colspan='' class='border-2 border-black px-1 p-1 text-xs'> $totalStaffCommission</th>";
            echo "<th colspan='' class='border-2 border-black px-1 p-1 text-xs'> Net Profit : $netProfit</th><th colspan='5'></th>";
            // echo "</tr>";
            echo "</table>";
        }
    }
    public function test2()
    {


        // initiaL SQL
        $sql = "SELECT DISTINCT `Branch name` FROM `consumer_product`;
        ";
        // echo $sql . "<br>";
        $result = mysqli_query($this->dbConnect, $sql);

        if (!$result) {
            // Handle database query error
            return;
        }

        $data = array();
        while ($row = mysqli_fetch_assoc($result)) {
            $data[] = $row;
        }

        if (empty($data)) {
            echo "No records found.";
            return;
        }
        // print_r($data);
        //totalCount
        $totalTvCount = 0;
        $totalAcCount = 0;
        $totalFridgeCount = 0;
        $totalSwingCount = 0;
        $totalWashCount = 0;
        $totalOthersCount = 0;

        //Total purchase price
        $totalTvPrice = 0;
        $totalAcPrice = 0;
        $totalFridgePrice = 0;
        $totalSwingPrice = 0;
        $totalWashPrice = 0;
        $totalOthersPrice = 0;
        echo "<table>
        <tbody>
            <tr class='row'>
                <th class='border-2 border-black px-1' rowspan='2'>sl</th>
                <th class='border-2 border-black px-1'  rowspan='2'>Branch Name</th>
                <th class='border-2 border-black px-1'  rowspan='2'>Products Details</th>
                <th class='border-2 border-black px-1' colspan ='2'>Cumilitive Recived</th>
                <th class='border-2 border-black px-1' colspan ='5'>Current Month sold</th>
                <th class='border-2 border-black px-1' colspan ='5'>Cumilitive sold</th>
                <th class='border-2 border-black px-1' colspan ='2'>InStock</th>
                <th class='border-2 border-black px-1' colspan ='2'>Paid To Ho</th>
                <th class='border-2 border-black px-1' rowspan='2'>Remarks</th>
       
            </tr>
            <tr>
                <th class='border-2 border-black px-1'>Num of product</th>
                <th class='border-2 border-black px-1'>pruchase Value</th>
                <th class='border-2 border-black px-1'>Num of product</th>
                <th class='border-2 border-black px-1'>Soldout Value</th>
                <th class='border-2 border-black px-1'>Purchase Value</th>
                <th class='border-2 border-black px-1'>Staff Incentive</th>
                <th class='border-2 border-black px-1'>Net Profit</th>
                <th class='border-2 border-black px-1'>Num of product</th>
                <th class='border-2 border-black px-1'>Soldout Value</th>
                <th class='border-2 border-black px-1'>Purchase Value</th>
                <th class='border-2 border-black px-1'>Staff Incentive</th>
                <th class='border-2 border-black px-1'>Net Profit</th>
                <th class='border-2 border-black px-1'>Number Of Product</th>
                <th class='border-2 border-black px-1'>Amount</th>
                <th class='border-2 border-black px-1'>Number Of Product</th>
                <th class='border-2 border-black px-1'>Amount</th>
            </tr>";
        foreach ($data as $key => $value) {
            $branchName = $value["Branch name"];
            echo "<tr>
                <td class='border-2 border-black px-1' rowspan='7'>" . $key + 1 . "</td>
                <td class='border-2 border-black px-1' rowspan='7'>" . $branchName . "</td>";


            $sqlParBranch = "SELECT cp.`Product Name`,
            COUNT(cp.`Product Name`) as `Product_Count`,
            SUM(cp.`Product Purchase Price`) as `Purchase_Value`,
            COUNT(cs.`Product Name`) as `soldCount`,
            SUM(cs.`Salable Price`) as `soldPrice`,
            SUM(cs.`Purchase Price`) as `PurchasePrice`,
            SUM(cs.`Staff Comission`) as `StaffComission`,
            SUM(cs.`Salable Price` - cs.`Purchase Price` - cs.`Staff Comission`) as `Net Profit`,
            COUNT(cp.`Product Name`) - COUNT(cs.`Product Name`) as `InStockCount`,
            SUM(cp.`Product Purchase Price`) - SUM(cs.`Purchase Price`) as `InStockValue`
            FROM `consumer_product` cp
            LEFT JOIN `consumer_soldout` cs
            ON cp.`Recieved ID` = cs.`Product ID`
            WHERE cp.`Branch name` = 'Badda'
            GROUP BY cp.`Product Name`;
                ";
            $result2 = mysqli_query($this->dbConnect, $sqlParBranch);
            if (!$result2) {
                // Handle database query error
                return;
            }

            $data2 = array();
            while ($row = mysqli_fetch_assoc($result2)) {
                $data2[] = $row;
            }

            if (empty($data2)) {
                echo "No records found.";
                return;
            }
            // print_r($data2);
            // product Count
            $tvCount = 0;
            $acCount = 0;
            $fridgeCount = 0;
            $swingCount = 0;
            $washCount = 0;
            $othersCount = 0;

            //purchase price
            $tvPrice = 0;
            $acPrice = 0;
            $fridgePrice = 0;
            $swingPrice = 0;
            $washPrice = 0;
            $othersPrice = 0;

            // Cumilitive sold 

            $soldTvCount = 0;
            $soldAcCount = 0;
            $soldFridgeCount = 0;
            $soldSwingCount = 0;
            $soldWashCount = 0;
            $soldOthersCount = 0;

            //Saleable price
            $soldtvPrice = 0;
            $soldacPrice = 0;
            $soldfridgePrice = 0;
            $soldswingPrice = 0;
            $soldwashPrice = 0;
            $soldothersPrice = 0;

            //purchase price
            $soldpurchasetvPrice = 0;
            $soldpurchaseacPrice = 0;
            $soldpurchasefridgePrice = 0;
            $soldpurchaseswingPrice = 0;
            $soldpurchasewashPrice = 0;
            $soldpurchaseothersPrice = 0;

            //purchase Comissions
            $soldtvComission = 0;
            $soldacComission = 0;
            $soldfridgeComission = 0;
            $soldswingComission = 0;
            $soldwashComission = 0;
            $soldothersComission = 0;

            //Net Profit
            $tvNetProfit = 0;
            $acNetProfit = 0;
            $fridgeNetProfit = 0;
            $swingNetProfit = 0;
            $washNetProfit = 0;
            $othersNetProfit = 0;

            //stock product Count
            $tvCountStock = 0;
            $acCountStock = 0;
            $fridgeCountStock = 0;
            $swingCountStock = 0;
            $washCountStock = 0;
            $othersCountStock = 0;

            //Stock purchase price
            $tvPriceStock = 0;
            $acPriceStock = 0;
            $fridgePriceStock = 0;
            $swingPriceStock = 0;
            $washPriceStock = 0;
            $othersPriceStock = 0;


            foreach ($data2 as $row) {
                if ($row['Product Name'] == 'Smart TV') {
                    $tvCount += $row['Product_Count'];
                    $tvPrice += $row['Purchase_Value'];
                    $totalTvCount += $row['Product_Count'];
                    $totalTvPrice += $row['Purchase_Value'];
                }
                if ($row['Product Name'] == 'LED TV') {
                    $tvCount += $row['Product_Count'];
                    $tvPrice += $row['Purchase_Value'];
                    $totalTvCount += $row['Product_Count'];
                    $totalTvPrice += $row['Purchase_Value'];
                }
                if ($row['Product Name'] == 'Refrigerator') {
                    $fridgeCount = $row['Product_Count'];
                    $fridgePrice = $row['Purchase_Value'];
                    $totalFridgeCount += $row['Product_Count'];
                    $totalFridgePrice += $row['Purchase_Value'];
                }
                if ($row['Product Name'] == 'Microwave Oven') {
                    $othersCount += $row['Product_Count'];
                    $othersPrice += $row['Purchase_Value'];
                    $totalOthersCount += $row['Product_Count'];
                    $totalOthersPrice += $row['Purchase_Value'];
                }
                if ($row['Product Name'] == 'Sewing Machine') {
                    $swingCount = $row['Product_Count'];
                    $swingPrice =   $row['Purchase_Value'];
                    $totalSwingCount += $row['Product_Count'];
                    $totalSwingPrice +=   $row['Purchase_Value'];
                }
                if ($row['Product Name'] == 'Washing Machine') {
                    $washCount = $row['Product_Count'];
                    $washPrice = $row['Purchase_Value'];
                    $totalWashCount += $row['Product_Count'];
                    $totalWashPrice += $row['Purchase_Value'];
                }
                if ($row['Product Name'] == 'Air Conditioner') {
                    $acCount = $row['Product_Count'];
                    $acPrice = $row['Purchase_Value'];
                    $totalAcCount += $row['Product_Count'];
                    $totalAcPrice += $row['Purchase_Value'];
                }
            }


            echo "
             <tr>     
            <td class='border-2 border-black px-1'>TV</td>
            <td class='border-2 border-black px-1'>$tvCount</td>
            <td class='border-2 border-black px-1'>$tvPrice</td>
            <td class='border border-black px-1' colspan='5'></td>
            
        </tr>
            </tr>
            <tr>     
                <td class='border-2 border-black px-1'>Fridge</td>
                <td class='border-2 border-black px-1'>$fridgeCount</td>
                <td class='border-2 border-black px-1'>$fridgePrice</td>
                <td class='border border-black px-1' colspan='5'></td>
            </tr>
            <tr>     
                <td class='border-2 border-black px-1'>AC</td>
                <td class='border-2 border-black px-1'>$acCount</td>
                <td class='border-2 border-black px-1'>$acPrice</td>      <td class='border border-black px-1' colspan='5'></td>         
            </tr>
            <tr>     
                <td class='border-2 border-black px-1'>Washing machine</td>
                <td class='border-2 border-black px-1'>$washCount</td>
                <td class='border-2 border-black px-1'>$washPrice</td>
   <td class='border border-black px-1' colspan='5'></td>
            </tr>
            <tr>     
                <td class='border-2 border-black px-1'>Swing Machine</td>
                <td class='border-2 border-black px-1'>$swingCount</td>
                <td class='border-2 border-black px-1'>$swingPrice</td>
<td class='border border-black px-1' colspan='5'></td>
            </tr>
            <tr>     
                <td class='border-2 border-black px-1'>Others</td>
                <td class='border-2 border-black px-1'>$othersCount</td>
                <td class='border-2 border-black px-1'>$othersPrice</td>
<td class='border border-black px-1' colspan='5'></td>
                </tr>";
        }
        echo " 
                <td class='border-2 border-black px-1' rowspan='7' colspan='2'>Grand Total</td><tr>     
        <td class='border-2 border-black px-1'>TV</td>
        <td class='border-2 border-black px-1'>$totalTvCount</td>
        <td class='border-2 border-black px-1'>$totalTvPrice</td>
        <td class='border border-black px-1' colspan='5'></td>
        
    </tr>
        </tr>
        <tr>     
            <td class='border-2 border-black px-1'>Fridge</td>
            <td class='border-2 border-black px-1'>$totalFridgeCount</td>
            <td class='border-2 border-black px-1'>$totalFridgePrice</td><td class='border border-black px-1' colspan='5'></td>
        </tr>
        <tr>     
            <td class='border-2 border-black px-1'>AC</td>
            <td class='border-2 border-black px-1'>$totalAcCount</td>
            <td class='border-2 border-black px-1'>$totalAcPrice</td>  <td class='border border-black px-1' colspan='5'></td>             
        </tr>
        <tr>     
            <td class='border-2 border-black px-1'>Washing machine</td>
            <td class='border-2 border-black px-1'>$totalWashCount</td>
            <td class='border-2 border-black px-1'>$totalWashPrice</td>
<td class='border border-black px-1' colspan='5'></td>
        </tr>
        <tr>     
            <td class='border-2 border-black px-1'>Swing Machine</td>
            <td class='border-2 border-black px-1'>$totalSwingCount</td>
            <td class='border-2 border-black px-1'>$totalSwingPrice</td>
<td class='border border-black px-1' colspan='5'></td>
        </tr>
        <tr>     
            <td class='border-2 border-black px-1'>Others</td>
            <td class='border-2 border-black px-1'>$totalOthersCount</td>
            <td class='border-2 border-black px-1'>$totalOthersPrice</td><td class='border border-black px-1' colspan='5'></td>
            </tr> </tbody>
                 </table>";
    }
    public function test()
    {
        // Define an array to store product data
        $products = [
            'TV' => ['Smart TV', 'LED TV'],
            'Fridge' => ['Refrigerator'],
            'AC' => ['Air Conditioner'],
            'WashingMachine' => ['Washing Machine'],
            'SwingMachine' => ['Sewing Machine'],
            'Others' => ['Microwave Oven', 'Rice Cooker']
        ];

        // Initialize HTML for the table
        $html = '<table class="report">';
        // ... (Table header HTML here)
        $html .= " <tbody>
        <tr class='row'>
            <th class='border-2 border-black px-1' rowspan='2'>sl</th>
            <th class='border-2 border-black px-1'  rowspan='2'>Branch Name</th>
            <th class='border-2 border-black px-1'  rowspan='2'>Products Details</th>
            <th class='border-2 border-black px-1' colspan ='2'>Cumilitive Recived</th>
            <th class='border-2 border-black px-1' colspan ='5'>Current Month sold</th>
            <th class='border-2 border-black px-1' colspan ='5'>Cumilitive sold</th>
            <th class='border-2 border-black px-1' colspan ='2'>InStock</th>
            <th class='border-2 border-black px-1' colspan ='2'>Paid To Ho</th>
            <th class='border-2 border-black px-1' rowspan='2'>Remarks</th>
   
        </tr>
        <tr>
            <th class='border-2 border-black px-1'>Num of product</th>
            <th class='border-2 border-black px-1'>pruchase Value</th>
            <th class='border-2 border-black px-1'>Num of product</th>
            <th class='border-2 border-black px-1'>Soldout Value</th>
            <th class='border-2 border-black px-1'>Purchase Value</th>
            <th class='border-2 border-black px-1'>Staff Incentive</th>
            <th class='border-2 border-black px-1'>Net Profit</th>
            <th class='border-2 border-black px-1'>Num of product</th>
            <th class='border-2 border-black px-1'>Soldout Value</th>
            <th class='border-2 border-black px-1'>Purchase Value</th>
            <th class='border-2 border-black px-1'>Staff Incentive</th>
            <th class='border-2 border-black px-1'>Net Profit</th>
            <th class='border-2 border-black px-1'>Number Of Product</th>
            <th class='border-2 border-black px-1'>Amount</th>
            <th class='border-2 border-black px-1'>Number Of Product</th>
            <th class='border-2 border-black px-1'>Amount</th>
        </tr>";
        // Initialize totals arrays
        // ... (Previous code)

        // Initialize totals arrays
        $totalCounts = [];
        $totalPrices = [];
        $totalSalesCounts = [];
        $totalSalesPrices = [];
        $totalPurchasePrices = [];
        $totalStaffCommissions = [];
        $totalNetProfits = [];
        $totalInStockCounts = [];
        $totalInStockValues = [];
        $totalPaidCounts = [];
        $totalPaidValues = [];

        // Initialize branch data array
        $branchData = [];

        // Query the database to get branch names
        $sql = "SELECT DISTINCT `Branch name` FROM `consumer_product`";
        $result = mysqli_query($this->dbConnect, $sql);

        if (!$result) {
            // Handle database query error
            return;
        }

        while ($row = mysqli_fetch_assoc($result)) {
            $branchName = $row["Branch name"];

            // Initialize branch-specific variables
            $branchCounts = [];
            $branchPrices = [];
            $branchSalesCounts = [];
            $branchSalesPrices = [];
            $branchPurchasePrices = [];
            $branchStaffCommissions = [];
            $branchNetProfits = [];
            $branchInStockCounts = [];
            $branchInStockValues = [];
            $branchPaidCounts = [];
            $branchPaidValues = [];

            // Loop through product categories
            foreach ($products as $category => $productNames) {
                // Initialize category-specific variables
                $categoryCount = 0;
                $categoryPrice = 0;
                $categorySalesCount = 0;
                $categorySalesPrice = 0;
                $categoryPurchasePrice = 0;
                $categoryStaffComission = 0;
                $categoryNetProfit = 0;
                $categoryInStockCount = 0;
                $categoryInStockValue = 0;
                $categoryPaidCount = 0;
                $categoryPaidValue = 0;

                // Loop through product names in the category
                foreach ($productNames as $productName) {
                    // Execute SQL query to get product data for the branch and category
                    $sql = "SELECT
                cp.`Product Name`,
                COUNT(cp.`Product Name`) as `Product_Count`,
                SUM(cp.`Product Purchase Price`) as `Purchase_Value`,
                COUNT(cs.`Product Name`) as `SalesCount`,
                SUM(cs.`Salable Price`) as `SalesPrice`,
                SUM(cs.`Purchase Price`) as `PurchasePrice`,
                SUM(cs.`Staff Comission`) as `StaffComission`,
                SUM(cs.`Salable Price` - cs.`Purchase Price` - cs.`Staff Comission`) as `Net Profit`,
                COUNT(cp.`Product Name`) - COUNT(cs.`Product Name`) as `InStockCount`,
                SUM(cp.`Product Purchase Price`) - SUM(cs.`Purchase Price`) as `InStockValue`,
                COALESCE(billPaid.totalPaidCount, 0) AS `totalPaidCount`,
                COALESCE(billPaid.TotalPaidAmount, 0) AS `TotalPaidAmount`
            FROM
                `consumer_product` cp
            LEFT JOIN
                `consumer_soldout` cs ON cp.`Recieved ID` = cs.`Product ID`
            LEFT JOIN
                (
                SELECT
                    co.`Product Name`,
                    co.`Branch name`,
                    SUM(`co`.`Received Quantity`) AS totalPaidCount,
                    SUM(bp.Paid_Amount) AS TotalPaidAmount
                FROM
                    `consumer_order` co
                LEFT JOIN
                    `consumer_bill_paid` bp ON co.`Order ID` = bp.`Order_Code`
                     WHERE co.`Distributor Name`='Singer'
                GROUP BY
                    co.`Product Name`, co.`Branch name`
                   
                ) AS billPaid ON cp.`Branch name` = billPaid.`Branch name` AND cp.`Product Name` = billPaid.`Product Name`
                WHERE cp.`Branch name` = '$branchName'
                        AND cp.`Product Name` = '$productName' and cp.`Distributor Name`='Singer'";


                    $result2 = mysqli_query($this->dbConnect, $sql);

                    if (!$result2) {
                        // Handle database query error
                        return;
                    }

                    $row2 = mysqli_fetch_assoc($result2);
                    $categoryCount += $row2['Product_Count'];
                    $categoryPrice += $row2['Purchase_Value'];
                    $categorySalesCount += $row2['SalesCount'];
                    $categorySalesPrice += $row2['SalesPrice'];
                    $categoryPurchasePrice += $row2['PurchasePrice'];
                    $categoryStaffComission += $row2['StaffComission'];
                    $categoryNetProfit += $row2['Net Profit'];
                    $categoryInStockCount += $row2['InStockCount'];
                    $categoryInStockValue += $row2['InStockValue'];
                    $categoryPaidCount += $row2['totalPaidCount'];
                    $categoryPaidValue += $row2['TotalPaidAmount'];
                    // Update branch totals
                    if (!isset($branchCounts[$category])) {
                        $branchCounts[$category] = 0;
                        $branchPrices[$category] = 0;
                        $branchSalesCounts[$category] = 0;
                        $branchSalesPrices[$category] = 0;
                        $branchPurchasePrices[$category] = 0;
                        $branchStaffCommissions[$category] = 0;
                        $branchNetProfits[$category] = 0;
                        $branchInStockCounts[$category] = 0;
                        $branchInStockValues[$category] = 0;
                        $branchPaidCounts[$category] = 0;
                        $branchPaidValues[$category] = 0;
                    }
                    $branchCounts[$category] += $row2['Product_Count'];
                    $branchPrices[$category] += $row2['Purchase_Value'];
                    $branchSalesCounts[$category] += $row2['SalesCount'];
                    $branchSalesPrices[$category] += $row2['SalesPrice'];
                    $branchPurchasePrices[$category] += $row2['PurchasePrice'];
                    $branchStaffCommissions[$category] += $row2['StaffComission'];
                    $branchNetProfits[$category] += $row2['Net Profit'];
                    $branchInStockCounts[$category] += $row2['InStockCount'];
                    $branchInStockValues[$category] += $row2['InStockValue'];
                    $branchPaidCounts[$category] += $row2['totalPaidCount'];
                    $branchPaidValues[$category] += $row2['TotalPaidAmount'];

                    // Update grand totals
                    if (!isset($totalCounts[$category])) {
                        $totalCounts[$category] = 0;
                        $totalPrices[$category] = 0;
                        $totalSalesCounts[$category] = 0;
                        $totalSalesPrices[$category] = 0;
                        $totalPurchasePrices[$category] = 0;
                        $totalStaffCommissions[$category] = 0;
                        $totalNetProfits[$category] = 0;
                        $totalInStockCounts[$category] = 0;
                        $totalInStockValues[$category] = 0;
                        $totalPaidCounts[$category] = 0;
                        $totalPaidValues[$category] = 0;
                    }
                    $totalCounts[$category] += $row2['Product_Count'];
                    $totalPrices[$category] += $row2['Purchase_Value'];
                    $totalSalesCounts[$category] += $row2['SalesCount'];
                    $totalSalesPrices[$category] += $row2['SalesPrice'];
                    $totalPurchasePrices[$category] += $row2['PurchasePrice'];
                    $totalStaffCommissions[$category] += $row2['StaffComission'];
                    $totalNetProfits[$category] += $row2['Net Profit'];
                    $totalInStockCounts[$category] += $row2['InStockCount'];
                    $totalInStockValues[$category] += $row2['InStockValue'];
                    $totalPaidCounts[$category] += $row2['totalPaidCount'];
                    $totalPaidValues[$category] += $row2['TotalPaidAmount'];
                }

                // Append category data to branchData array
                $branchData[$branchName][$category]['count'] = $categoryCount;
                $branchData[$branchName][$category]['price'] = $categoryPrice;
                $branchData[$branchName][$category]['salesCount'] = $categorySalesCount;
                $branchData[$branchName][$category]['salesPrice'] = $categorySalesPrice;
                $branchData[$branchName][$category]['purchasePrice'] = $categoryPurchasePrice;
                $branchData[$branchName][$category]['staffComission'] = $categoryStaffComission;
                $branchData[$branchName][$category]['netProfit'] = $categoryNetProfit;
                $branchData[$branchName][$category]['inStockCount'] = $categoryInStockCount;
                $branchData[$branchName][$category]['inStockValue'] = $categoryInStockValue;
                $branchData[$branchName][$category]['paidCount'] = $categoryPaidCount;
                $branchData[$branchName][$category]['paidValue'] = $categoryPaidValue;
            }
            // Append branch data to the HTML table
            $html .= "<tr>";
            $html .= "<td class='border border-black px-1' rowspan='7'>" . count($branchData) . "</td>";
            $html .= "<td class='border border-black px-1' rowspan='7'>$branchName</td>";
            $html .= "</tr>";

            foreach ($branchData[$branchName] as $category => $data) {
                $html .= "<td class='border border-black px-1'>$category</td>";
                $html .= "<td class='border border-black px-1'>{$data['count']}</td>";
                $html .= "<td class='border border-black px-1'>{$data['price']}</td>";
                $html .= "<td class='border border-black px-1' colspan='5'></td>"; // Empty columns
                $html .= "<td class='border border-black px-1'>{$data['salesCount']}</td>";
                $html .= "<td class='border border-black px-1'>{$data['salesPrice']}</td>";
                $html .= "<td class='border border-black px-1'>{$data['purchasePrice']}</td>";
                $html .= "<td class='border border-black px-1'>{$data['staffComission']}</td>";
                $html .= "<td class='border border-black px-1'>{$data['netProfit']}</td>";
                $html .= "<td class='border border-black px-1'>{$data['inStockCount']}</td>";
                $html .= "<td class='border border-black px-1'>{$data['inStockValue']}</td>";
                $html .= "<td class='border border-black px-1'>{$data['paidCount']}</td>";
                $html .= "<td class='border border-black px-1'>{$data['paidValue']}</td>";
                $html .= "</tr>";
            }
        }



        // ... (Append Grand Total row here)
        $html .= "<tr>";
        $html .= "<td class='border border-black px-1' colspan='2' rowspan='7'>Grand Total</td>";
        $html .= "</tr>";
        foreach ($products as $category => $productNames) {
            $html .= "<tr>";
            $html .= "<td class='border border-black px-1'>$category</td>";

            $html .= "<td class='border border-black px-1'>{$totalCounts[$category]}</td>";
            $html .= "<td class='border border-black px-1'>{$totalPrices[$category]}</td>";
            $html .= "<td class='border border-black px-1' colspan='5'></td>";
            $html .= "<td class='border border-black px-1'>{$totalSalesCounts[$category]}</td>";
            $html .= "<td class='border border-black px-1'>{$totalSalesPrices[$category]}</td>";
            $html .= "<td class='border border-black px-1'>{$totalPurchasePrices[$category]}</td>";
            $html .= "<td class='border border-black px-1'>{$totalStaffCommissions[$category]}</td>";
            $html .= "<td class='border border-black px-1'>{$totalNetProfits[$category]}</td>";
            $html .= "<td class='border border-black px-1'>{$totalInStockCounts[$category]}</td>";
            $html .= "<td class='border border-black px-1'>{$totalInStockValues[$category]}</td>";
            $html .= "<td class='border border-black px-1'>{$totalPaidCounts[$category]}</td>";
            $html .= "<td class='border border-black px-1'>{$totalPaidValues[$category]}</td>";
            $html .= "</tr>";
            $html .= "</tr>"; // Empty columns
        }

        // Close the table HTML
        $html .= '</table>';

        // print_r($branchData);
        // Return the generated HTML
        return $html;
    }
}
