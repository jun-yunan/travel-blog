<?php
$datetime = $post['published_at'];
$date = new DateTime($datetime);
$formatted = $date->format("d/m/Y H:i");
?>
<div class="w-screen flex">
    <div class="w-[25%]"></div>
    <div class="flex-1 w-[50%] min-h-[500px] border-[2px] border-gray-300 rounded-md shadow p-6">
        <div>
            <p class="text-4xl font-bold text-gray-800"><?php echo htmlspecialchars($data['title']) ?></p>
        </div>
        <div class="mt-[28px] w-full flex items-center justify-between">
            <div class="flex items-center gap-x-3">
                <img class="w-[48px] h-[48px] rounded-full cursor-pointer hover:opacity-75 transition-all duration-500 hover:scale-105" src="/assets/images/placeholder.jpg" alt="">
                <div class="flex flex-col items-start justify-between">
                    <p class="text-base font-semibold text-gray-800"><?php echo htmlspecialchars($data['full_name']) ?></p>
                    <p class="text-sm font-normal text-gray-500"><?php echo htmlspecialchars($formatted) ?></p>
                </div>
            </div>

            <div class="flex items-center gap-x-3">
                <div class="flex items-center gap-x-1 hover:bg-orange-600 transition duration-500 text-orange-500 hover:text-white border-[1.5px] border-orange-500 py-[2px] px-3 cursor-pointer rounded-full">
                    <i class="fa-solid fa-bookmark"></i>
                    <p class="text-sm font-medium">Lưu bài</p>
                </div>
                <div class="flex items-center gap-x-1 hover:bg-sky-600 transition duration-500 text-sky-600 hover:text-white border-[1.5px] border-sky-600 py-[2px] px-3 cursor-pointer rounded-full">
                    <i class="fa-solid fa-plus "></i>
                    <p class="text-sm font-medium ">Theo dõi</p>
                </div>

                <div class="dropdown">
                    <button class="" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <ion-icon name="ellipsis-vertical"></ion-icon>
                    </button>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="#"><i class="fa-solid fa-bell-slash mr-2"></i> Tắt thông báo</a></li>
                        <li><a class="dropdown-item" href="#"><i class="fa-solid fa-eye-slash mr-2"></i> Ẩn bài viết</a></li>
                        <li><a class="dropdown-item" href="#"><i class="fa-solid fa-flag mr-2"></i>Báo cáo bài viết</a></li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="w-full mt-[28px] flex flex-col gap-y-4">
            <p class="text-lg text-gray-800"><?php echo htmlspecialchars($data['content']) ?></p>
            <img class="w-full h-auto object-cover rounded-md hover:opacity-75 transition-all duration-500 cursor-pointer hover:scale-105" src="<?php echo htmlspecialchars($data['featured_image']) ?>" alt="">
        </div>

        <div class="flex mt-[28px] w-full justify-around">
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

        <div class="w-full h-[1.5px] my-10 bg-gray-400"></div>

        <div class="w-full flex gap-x-3">
            <?php if (count($data['tags']) == 3): ?>
                <div class="border-[1px] border-gray-900 px-2 rounded-md inline-block">
                    <p class="text-sm font-semibold text-gray-900">#<?php echo htmlspecialchars($data['tags']['name']) ?></p>
                </div>
            <?php else: ?>
                <?php foreach ($data['tags'] as $tag): ?>
                    <div class="border-[1px] border-gray-900 px-2 rounded-md inline-block">
                        <p class="text-sm font-semibold text-gray-900">#<?php echo htmlspecialchars($tag['name']) ?></p>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

        <div class="w-full mt-[28px]">
            <p class="text-lg font-semibold text-gray-800">Bài viết cùng tác giả:</p>
        </div>

        <div class="w-full my-[28px]">
            <p class="text-lg font-semibold text-gray-800">Các bài viết liên quan khác:</p>
        </div>
    </div>
    <div class="w-[25%]">

    </div>
</div>