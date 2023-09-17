<?php
// require('../class/config.php');
include('../class/branch.php');
$branch = new Branch();

// echo ($hostName);
include("../init/header.php");
echo '<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.17.0/xlsx.full.min.js"></script>';


include("../utils/script.php");
// <!-- Include SheetJS from a CDN -->

include("../nav/navbar.php");

// Function to get query parameters by name
function getQueryParam($name)
{
    return isset($_GET[$name]) ? $_GET[$name] : '';
}

// Get values from query parameters
$fromDateParam = getQueryParam('from');
$toDateParam = getQueryParam('to');
$distributorName = isset($_GET['distributor']) ?  $_GET['distributor'] : 'Singer';
?>

<div class="main m-0 p-0 overflow-hidden h-[90vh] w-full">

    <div class="bg-green-300 w-full h-16 text-bold text-2xl text-center flex justify-center items-center">
        <h1>
            <?php echo $distributorName ?> Report
        </h1>
    </div>
    <div class='p-2 flex flex-col gap-2 min-w-full '>
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="GET" class='flex gap-2 p-1'>
            <select name="branch" class='px-2 w-36 text-center bg-green-300 rounded-md'>
                <option value="select branch" class='px-1 w-36 text-center cursor-pointer rounded-md'>
                    Select Branch
                </option>
                <?php $branch->getBranchNames(); ?>

            </select>
            <select name="distributor" class='px-2 w-36 text-center bg-green-300 rounded-md'>

                <?php $branch->getDistributorNames(); ?>

            </select>

            <input type="date" name="from" id="from" class='px-2 w-44 text-center bg-green-300 rounded-md' value="<?php echo $fromDateParam; ?>">
            <input type="date" name="to" id="to" class='px-2 w-44 text-center bg-green-300 rounded-md' value="<?php echo $toDateParam; ?>">

            <button class='btn w-36'>Search</button>
        </form>
        <button id="export-button" class='btn w-36'>Export to Excel</button>
        <div class="h-[90vh] overflow-scroll pb-56 w-full">
            <?php
            echo $branch->reportTable();

            ?>
        </div>

        <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.17.5/xlsx.full.min.js"></script> -->
        <script src='../js/exportToExcell.js'></script>

    </div>
</div>

<?php

include("../init/footer.php");
