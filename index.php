<?php
// require('../class/config.php');
include('./class/branch.php');
$branch = new Branch();

// echo ($hostName);
include("./init/header.php");

include("./utils/script.php");

include("./nav/navbar.php");

?>
<div class="main m-0 p-0 w-full ">

    <div class="bg-green-300 w-full h-16 text-bold text-2xl text-center flex justify-center items-center">
        <h1>
            Consumer Dashboard
        </h1>
    </div>
    <div class='p-2 flex flex-col gap-2 '>

        <div class=" ">
            <?php
            echo $branch->test();
            // $branch->getBranchNames();
            ?>
        </div>

    </div>
</div>

<?php

include("./init/footer.php");
