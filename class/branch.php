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

        foreach ($tablesNames as $tablesName) {
            echo " <li class='btn' id='li'>
                    <a href=" . ($tablesName) . ".php>";

            $cleanedTableName = str_replace('_', ' ', str_ireplace('consumer', '', $tablesName));
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
            echo "<option value=" . htmlspecialchars($branchName) . ">" . $branchName . "</option>";
        }
    }
    public function getDistributorNames()
    {
        $query = "SELECT `Distributor Name` FROM $this->order group by `Distributor Name`";

        // Update with your actual table name
        $result = mysqli_query($this->dbConnect, $query);
        print_r($result);

        $DistributorNames = array();
        while ($row = mysqli_fetch_assoc($result)) {

            $DistributorNames[] = $row['Distributor Name'];
        }
        // print_r($branchNames);
        foreach ($DistributorNames as $DistributorName) {
            echo "<option value=" . $DistributorName . ">" . $DistributorName . "</option>";
        }
    }
    public function getProductsType()
    {
        $query = "SELECT `Product Name` FROM $this->product group by `Product Name`";

        // Update with your actual table name
        $result = mysqli_query($this->dbConnect, $query);
        print_r($result);

        $DistributorNames = array();
        while ($row = mysqli_fetch_assoc($result)) {

            $DistributorNames[] = $row['Product Name'];
        }
        // print_r($branchNames);
        foreach ($DistributorNames as $DistributorName) {
            echo "<option value=" . htmlspecialchars($DistributorName) . ">" . $DistributorName . "</option>";
        }
    }

    public function getOrderList()

    {

        $tableName = $this->order;

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

        echo "<table class='border-collapse  border-gray-300 text-center  overflow-scroll relative'>";
        echo "<tr class=' p-1 sticky top-0 bg-teal-500'>";
        foreach (array_keys($data[0]) as $columnName) {
            echo "<th class='border-2 border-black  p-1 text-xs capitalize'>$columnName</th>";
        }
        // echo "</tr>";


        $totalOrderQuantity = 0;
        $totalShippedQuantity = 0;
        $totalReceivedQuantity = 0;

        foreach ($data as $value) {
            echo "<tr class='border p-1'>";
            foreach ($value as $key => $val) {
                echo "<td class='border p-1 text-xs'>$val</td>";
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
        echo "<tr class='border p-1'>";
        echo "<th colspan='9' class='border p-1 text-xs'>Total</th>";
        echo "<th colspan='' class='border p-1 text-xs'>$totalOrderQuantity</th>";

        echo "<th colspan='' class='border p-1 text-xs'> $totalShippedQuantity</th>";

        echo "<th colspan='' class='border p-1 text-xs'> $totalReceivedQuantity</th>";
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

        echo "<table class='border-collapse  border-gray-300 text-center  overflow-scroll relative'>";
        echo "<tr class=' p-1 sticky top-0 bg-teal-500'>";
        foreach (array_keys($data[0]) as $columnName) {
            echo "<th class='border-2 border-black  p-1 text-xs capitalize'>$columnName</th>";
        }
        // echo "</tr>";



        $totalPaid = 0;

        foreach ($data as $value) {
            echo "<tr class='border p-1'>";
            foreach ($value as $key => $val) {
                echo "<td class='border p-1 text-xs'>$val</td>";
                if ($key === 'Paid_Amount') {
                    $totalPaid += $val;
                }
            }
            // echo "</tr>";
        }

        // Display the total row
        echo "<tr class='border p-1'>";
        echo "<th colspan='7' class='border p-1 text-xs'>Total</th>";
        echo "<th colspan='' class='border p-1 text-xs'>$totalPaid</th>";
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

        echo "<table class='border-collapse  border-gray-300 text-center  overflow-scroll relative'>";
        echo "<tr class=' p-1 sticky top-0 bg-teal-500'>";
        foreach (array_keys($data[0]) as $columnName) {
            echo "<th class='border-2 border-black  p-1 text-xs capitalize'>$columnName</th>";
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
            echo "<tr class='border p-1'>";
            foreach ($value as $key => $val) {
                echo "<td class='border p-1 text-xs'>$val</td>";
                if ($key === 'Purchase Price') {
                    $totalPurchase += $val;
                }
                if ($key === 'Purchase Price (Trans Rec Prod)') {
                    $totalTransfer += $val;
                }
                if ($key == 'Payable Amount To HO') {

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
        echo "<tr class='border p-1'>";
        echo "<th colspan='2' class='border p-1 text-xs'>Total</th>";
        echo "<th colspan='' class='border p-1 text-xs'>$totalPurchase</th>";
        echo "<th colspan='' class='border p-1 text-xs'>$totalTransfer</th>";
        echo "<th colspan='' class='border p-1 text-xs'>$totalHoPayment</th>";
        echo "<th colspan='' class='border p-1 text-xs'>$totalMIS</th>";
        echo "<th colspan='' class='border p-1 text-xs'>$totalBalance</th>";
        echo "<th colspan='' class='border p-1 text-xs'>$totalTotalAIS</th>";
        echo "<th colspan='' class='border p-1 text-xs'>$totalHo</th>";
        echo "<th colspan='' class='border p-1 text-xs'>$totalVariance</th>";
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

        echo "<table class='border-collapse  border-gray-300 text-center  overflow-scroll relative'>";
        echo "<tr class=' p-1 sticky top-0 bg-teal-500'>";
        foreach (array_keys($data[0]) as $columnName) {
            echo "<th class='border-2 border-black  p-1 text-xs capitalize'>$columnName</th>";
        }
        // echo "</tr>";


        $totalShippedQuantity = 0;
        $totalReceivedQuantity = 0;
        $totalNonReceivedQuantity = 0;
        $purchasePrice = 0;
        $paidAmount = 0;
        $dueAmount = 0;


        foreach ($data as $value) {
            echo "<tr class='border p-1'>";
            foreach ($value as $key => $val) {
                echo "<td class='border p-1 text-xs'>$val</td>";
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
        echo "<tr class='border p-1'>";
        echo "<th colspan='4' class='border p-1 text-xs'>Total</th>";
        echo "<th colspan='' class='border p-1 text-xs'>$totalShippedQuantity</th>";
        echo "<th colspan='' class='border p-1 text-xs'>$totalReceivedQuantity</th>";
        echo "<th colspan='' class='border p-1 text-xs'>$totalNonReceivedQuantity</th>";
        echo "<th colspan='' class='border p-1 text-xs'>$purchasePrice</th>";

        echo "<th colspan='' class='border p-1 text-xs'> $paidAmount</th>";

        echo "<th colspan='' class='border p-1 text-xs'> $dueAmount</th>";
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

        echo "<table class='border-collapse  border-gray-300 text-center  overflow-scroll relative'>";
        echo "<tr class=' p-1 sticky top-0 bg-teal-500'>";
        foreach (array_keys($data[0]) as $columnName) {
            echo "<th class='border-2 border-black  p-1 text-xs capitalize'>$columnName</th>";
        }
        // echo "</tr>";


        $totalOrderQuantity = 0;
        $totalShippedQuantity = 0;
        $totalReceivedQuantity = 0;

        foreach ($data as $value) {
            echo "<tr class='border p-1'>";
            foreach ($value as $key => $val) {
                echo "<td class='border p-1 text-xs'>$val</td>";
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
        echo "<tr class='border p-1'>";
        echo "<th colspan='9' class='border p-1 text-xs'>Total</th>";
        echo "<th colspan='' class='border p-1 text-xs'>$totalOrderQuantity</th>";

        echo "<th colspan='' class='border p-1 text-xs'> $totalShippedQuantity</th>";

        echo "<th colspan='' class='border p-1 text-xs'> $totalReceivedQuantity</th>";
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

        echo "<table class='border-collapse  border-gray-300 text-center  overflow-scroll relative'>";
        echo "<tr class=' p-1 sticky top-0 bg-teal-500'>";
        foreach (array_keys($data[0]) as $columnName) {
            echo "<th class='border-2 border-black  p-1 text-xs capitalize'>$columnName</th>";
        }
        // echo "</tr>";


        $totalOrderQuantity = 0;
        $totalShippedQuantity = 0;
        $totalReceivedQuantity = 0;

        foreach ($data as $value) {
            echo "<tr class='border p-1'>";
            foreach ($value as $key => $val) {
                echo "<td class='border p-1 text-xs'>$val</td>";
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
        echo "<tr class='border p-1'>";
        echo "<th colspan='9' class='border p-1 text-xs'>Total</th>";
        echo "<th colspan='' class='border p-1 text-xs'>$totalOrderQuantity</th>";

        echo "<th colspan='' class='border p-1 text-xs'> $totalShippedQuantity</th>";

        echo "<th colspan='' class='border p-1 text-xs'> $totalReceivedQuantity</th>";
        // echo "</tr>";
        echo "</table>";
    }
}
