<div class="main">
    <div class=" flex ">
        <aside class="p-2 bg-green-300 min-h-[96vh]">
            <nav class='h-full'>
                <div>
                    <div class='flex flex-col gap-1  justify-center float-right ' id='homedownMain'>
                        <div class="icon flex h-16 justify-center items-center text-3xl w-16  " id='homedown'>
                            <span class="material-icons scale-150 rotate-180 text-sky-500">web_stories</span>
                        </div>
                        <ul class=" flex flex-col  p-2 gap-2 duration-700 animateRight xl:w-[12rem] " id="ulList">

                            <?php $branch->getTableName() ?>

                        </ul>
                    </div>
                    <div class="hidden w-full" id='homeupMain'>
                        <div class="icon flex h-16 justify-center items-center text-3xl w-16  " id='homeup'>
                            <span class="material-icons scale-150 ">web_stories</span>
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