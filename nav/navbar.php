<div class="main">
    <div class="container flex ">
        <aside class="p-2 bg-[#62e844] min-h-[96vh]">
            <nav class='h-full'>
                <div>
                    <div class='flex flex-col gap-1  justify-center float-right ' id='homedownMain'>
                        <div id='homedown'>
                            <?php include('../assests/svg/homelove.php') ?>
                        </div>
                        <ul class=" flex flex-col  p-2 gap-2 duration-700 animateRight w-[14rem]" id="ulList">

                            <?php $branch->getTableName() ?>

                        </ul>
                    </div>
                    <div class="hidden w-full" id='homeupMain'>
                        <div class="icon flex justify-end" id='homeup'><?php include('../assests/svg/homelove.php') ?>
                        </div>
                        <ul class=" flex flex-col  p-2 gap-2 duration-700 animateRight w-[14rem] bg-[#21a28274] h-full rounded-lg" id="ulList">
                            <li class="btn" id='li'>
                                <a href="http://localhost/pmk/dashboard">Admin Dashboard</a>
                            </li>
                            <li class="btn" id='li'>
                                <a href="http://localhost/pmk/accounts">Accounts Module</a>
                            </li>
                            <li class="btn" id='li'>
                                <a href="http://localhost/pmk/health" class="">Health Program</a>
                            </li>
                            <li class="btn" id='li'>
                                <a href="http://localhost/pmk/consumer">Consumer Program</a>
                            </li>
                            <li class="btn" id='li'>
                                <a href="http://localhost/pmk/support">Support Center</a>
                            </li>
                        </ul>
                    </div>
                </div>

            </nav>
        </aside>