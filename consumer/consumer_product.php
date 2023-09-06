<?php
// require('../class/config.php');
include('../class/branch.php');
$branch = new Branch();

// echo ($hostName);
include("../init/header.php");

include("../utils/script.php");

include("../nav/navbar.php");

?>
<div class="mainDiv m-0 p-0 overflow-hidden h-[90vh]">

    <div class="bg-green-300 w-full h-16 text-bold text-2xl text-center flex justify-center items-center">
        <h1 class='uppercase font-bold  shadow-lg px-6 p-2 rounded-full'
            style='font-family:ADLaM Display;color: lightseagreen;'>
            Consumer Product List
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
            <select name="distributor" class='px-2 w-44 text-center bg-green-300 rounded-md'>
                <option value="select distributor" class='px-1 w-36 text-center  cursor-pointer rounded-md'>
                    Select distributor</option>
                <?php $branch->getDistributorNames(); ?>

            </select>
            <select name="status" class='px-2 w-44 text-center bg-green-300 rounded-md'>
                <option value="select product status" class='px-1 w-36 text-center  cursor-pointer rounded-md'>
                    Select product status</option>
                <option value="soldout"
                    <?php if (isset($_GET['status']) && $_GET['status'] == 'soldout') echo 'selected'; ?>
                    class='px-1 w-36 text-center  cursor-pointer rounded-md'>
                    soldout</option>
                <option value="stock in"
                    <?php if (isset($_GET['status']) && $_GET['status'] == 'stock in') echo 'selected'; ?>
                    class='px-1 w-36 text-center  cursor-pointer rounded-md'>
                    stock in</option>
            </select>

            <select name="type" class='px-2 w-44 text-center bg-green-300 rounded-md'>
                <option value="select product type" class='px-1 w-44 text-center  cursor-pointer rounded-md'>
                    Select product Type</option>
                <?php $branch->getProductsType(); ?>

            </select>
            <button class='btn w-36 bg-[lightseagreen] text-slate-200 duration-500'>Search</button>
        </form>

        <div class="h-[90vh] overflow-scroll pb-56">
            <?php
            echo $branch->getProductList();
            // $branch->getBranchNames();
            ?>
        </div>

    </div>
</div>

<?php

include("../init/footer.php");