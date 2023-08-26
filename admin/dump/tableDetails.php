<?php
require('../../utils/conn.php')
?>
<html lang="en">

<head>
    <title>Document</title>
    <?php include("../../utils/script.php") ?>
</head>

<body class="w-full overflow-hidden">
    <nav class='pl-10 bg-green-300 flex justify-center h-[10vh] overflow-hidden'>
        <?php include('../../utils/nav.php'); ?>
    </nav>
    <main class=" flex w-full h-[85vh] overflow-hidden">
        <aside class="w-[20%]">
            <?php
            include("../../utils/leftBar.php") ?>
        </aside>
        <container class="w-[80%] overflow-hidden ">
            <?php include("../table/tablerender.php"); ?>
        </container>
    </main>
    <footer class=" h-[5vh] py-1 bg-green-300 text-center">
        <?php include('../../utils/footer.php'); ?>
    </footer>
</body>

</html>