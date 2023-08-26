<?php
// require('../class/config.php');
include('../class/branch.php');
$branch = new Branch();

// echo ($hostName);
include("../init/header.php");

include("../utils/script.php");

include("../nav/navbar.php");

?>
<div class="main m-0 p-0 overflow-hidden h-[90vh]">

    <div class="bg-green-300 w-full h-16 text-bold text-2xl text-center flex justify-center items-center">
        <h1>
            Consumer Dashboard
        </h1>
    </div>
    <div class='p-2 flex flex-col gap-2 '>
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="GET" class='flex gap-2 p-1'>
            <select name="branch" class='px-2 w-36 text-center bg-green-300 rounded-md'>
                <option value="select branch" class='px-1 w-36 text-center cursor-pointer rounded-md'>
                    Select Branch
                </option>
                <?php $branch->getBranchNames(); ?>

            </select>
            <select name="distributor" class='px-2 w-36 text-center bg-green-300 rounded-md'>
                <option value="select distributor" class='px-1 w-36 text-center  cursor-pointer rounded-md'>
                    Select distributor</option>
                <?php $branch->getDistributorNames(); ?>

            </select>
            <select name="bill" class='px-2 w-36 text-center bg-green-300 rounded-md'>
                <option value="select Bill" class='px-1 w-36 text-center  cursor-pointer rounded-md'>
                    Select Bill</option>
                <option value="Paid" class='px-1 w-36 text-center  cursor-pointer rounded-md'>
                    Paid</option>
                <option value="Unpaid" class='px-1 w-36 text-center  cursor-pointer rounded-md'>
                    Unpaid</option>

            </select>
            <button class='btn w-36'>Search</button>
        </form>
        <div class="h-[90vh] overflow-scroll pb-56">
            <?php
            echo $branch->getOrderList();
            // $branch->getBranchNames();
            ?>
        </div>

    </div>
</div>

<?php

include("../init/footer.php");
