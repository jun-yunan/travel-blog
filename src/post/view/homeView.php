<div class="w-full h-full flex">
    <?php include "src/post/view/layouts/side-bar.php"; ?>
    <?php
    $pathname = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    $active_home = $pathname == '/' ? 'bg-green-600 text-white' : '';
    $active_saved = $pathname == '/saved' ? 'bg-green-600 text-white' : '';
    $active_schedule = $pathname == '/schedule' ? 'bg-green-600 text-white' : '';
    ?>
    <main class="flex-1 flex flex-col items-center">

        <div class="flex items-center justify-between w-[70%] gap-x-2">
            <a href="/saved" class="flex-1 <?php echo $active_saved; ?> hover:bg-green-600 hover:text-white hover:scale-105 transition-all duration-500 flex items-center justify-center px-3 py-2 text-base font-medium border rounded-md"><i class="fa-solid fa-bookmark mr-2"></i>Đã lưu</a>
            <a href="/" class="flex-1 <?php echo $active_home; ?> hover:bg-green-600 hover:text-white hover:scale-105 transition-all duration-500 flex items-center justify-center px-3 py-2 text-base font-medium border rounded-md"><i class="fa-solid fa-house mr-2"></i>Trang chủ</a>
            <a href="/schedule" class="flex-1 <?php echo $active_schedule; ?> hover:bg-green-600 hover:text-white hover:scale-105 transition-all duration-500 flex items-center justify-center px-3 py-2 text-base font-medium border rounded-md"><i class="fa-solid fa-location-dot mr-2"></i>Lịch trình</a>
        </div>

        <div class="my-3 w-[70%] h-[50px] flex flex-col items-center justify-center border rounded-md">Bộ Lọc</div>

        <div class="w-[70%] flex items-center justify-center cursor-pointer">
            <div class="flex items-center w-full border-2 border-gray-200 px-3 rounded-md overflow-hidden hover:scale-110 transition-all duration-500 hover:shadow">
                <ion-icon name="pencil-outline"></ion-icon>
                <input type="text" readonly placeholder="Share your travel adventures and inspire others on our blog! 🌍✈️...." class="border-none focus:ring-0 w-full">
            </div>
        </div>

        <div class="flex flex-col space-y-6 w-full items-center mt-8">
            <?php if (empty($blogs)): ?>
                <p>No blogs found.</p>
            <?php endif; ?>
            <?php foreach ($blogs as $blog): ?>
                <div class="w-[70%] flex flex-col border-2 border-gray-300 p-3 gap-y-3 rounded-lg shadow">
                    <div class="flex items-center w-full justify-between">
                        <p class="text-sm font-medium text-gray-600">Suggested</p>
                        <div class="flex items-center gap-x-3">
                            <div class="dropdown">
                                <button class="" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    <ion-icon name="ellipsis-vertical"></ion-icon>
                                </button>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="#">Action</a></li>
                                    <li><a class="dropdown-item" href="#">Another action</a></li>
                                    <li><a class="dropdown-item" href="#">Something else here</a></li>
                                </ul>
                            </div>
                            <button class=" hover:bg-gray-300 transition duration-500">
                                <ion-icon name="close"></ion-icon>
                            </button>
                        </div>
                    </div>
                    <div class="h-[1px] w-full bg-gray-200"></div>
                    <div class="flex w-full items-center justify-between">
                        <div class="flex items-center gap-x-2">
                            <div class="rounded-full overflow-hidden w-[48px] h-[48px] border">
                                <img width="48" height="48" src="https://img.icons8.com/parakeet-line/96/user.png" alt="user" />
                            </div>
                            <div class="flex flex-col">
                                <a class="text-base font-medium text-gray-800 hover:underline cursor-pointer">Jun Yunan</a>
                                <p class="text-sm font-normal text-gray-500">2h ago</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-x-3">
                            <div class="flex items-center gap-x-1 hover:bg-rose-600 transition duration-500 text-rose-600 hover:text-white border-2 border-rose-600 py-[2px] px-3 cursor-pointer rounded-full">
                                <i class="fa-regular fa-heart"></i>
                                <p class="text-sm font-medium ">Favorites</p>
                            </div>
                            <div class="flex items-center gap-x-1 hover:bg-sky-600 transition duration-500 text-sky-600 hover:text-white border-2 border-sky-600 py-[2px] px-3 cursor-pointer rounded-full">
                                <i class="fa-solid fa-plus "></i>
                                <p class="text-sm font-medium ">Follow</p>
                            </div>
                        </div>
                    </div>

                    <div class="flex flex-col justify-start gap-y-3">
                        <a href="/" class="text-lg font-semibold underline text-sky-600"><?php echo htmlspecialchars($blog['title']); ?></a>
                        <p class="text-base text-gray-500"><?php echo htmlspecialchars($blog['content']); ?></p>
                        <div class="rounded-lg overflow-hidden hover:opacity-75 transition-all cursor-pointer duration-500 hover:scale-105">
                            <img class="object-cover" src="/assets/images/ha_long_1.jpg" alt="">
                        </div>
                    </div>

                    <div class="w-full flex items-center justify-between">
                        <a href="/" class="flex hover:underline hover:text-blue-600 text-sm font-medium text-gray-700 items-center gap-x-1">
                            <i class="fa-regular fa-thumbs-up"></i>
                            <p class="">39</p>
                        </a>
                        <div class="flex items-center gap-x-3">
                            <a href="/" class="flex hover:underline hover:text-blue-600 text-sm font-medium text-gray-700 items-center gap-x-1">
                                <i class="fa-regular fa-comment"></i>
                                <p class="">12</p>
                            </a>
                            <a href="/" class="flex hover:underline hover:text-blue-600 text-sm font-medium text-gray-700 items-center gap-x-1">
                                <i class="fa-solid fa-share"></i>
                                <p class="">5</p>
                            </a>
                        </div>
                    </div>

                    <div class="h-[1px] w-full bg-gray-200"></div>

                    <div class="flex w-full justify-around">
                        <button class="hover:bg-gray-200 transition duration-300 py-1 px-3 rounded-full flex items-center gap-x-2 text-base font-medium text-gray-700">
                            <i class="text-lg fa-regular fa-thumbs-up"></i>
                            <p>Like</p>
                        </button>
                        <button class="hover:bg-gray-200 transition duration-300 py-1 px-3 rounded-full flex items-center gap-x-2 text-base font-medium text-gray-700">
                            <i class="text-lg fa-regular fa-comment"></i>
                            <p>Comment</p>
                        </button>
                        <button class="hover:bg-gray-200 transition duration-300 py-1 px-3 rounded-full flex items-center gap-x-2 text-base font-medium text-gray-700">
                            <i class="text-lg fa-solid fa-share"></i>
                            <p>Share</p>
                        </button>
                    </div>

                </div>
            <?php endforeach; ?>

        </div>
    </main>
    <?php include "src/post/view/layouts/friends.php"; ?>
</div>