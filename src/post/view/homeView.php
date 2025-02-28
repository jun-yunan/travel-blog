<div class="w-full h-full flex">
    <?php include "src/post/view/layouts/side-bar.php"; ?>
    <?php
    $pathname = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    $active_home = $pathname == '/' ? 'bg-green-600 text-white' : '';
    $active_saved = $pathname == '/saved' ? 'bg-green-600 text-white' : '';
    $active_schedule = $pathname == '/schedule' ? 'bg-green-600 text-white' : '';
    $user = isset($_SESSION['user']) ? $_SESSION['user'] : null;

    ?>
    <main class="flex-1 flex flex-col items-center">

        <div class="flex items-center justify-between w-[70%] gap-x-2">
            <a href="/saved" class="flex-1 shadow-sm <?php echo $active_saved; ?> hover:bg-green-600 hover:text-white hover:scale-105 transition-all duration-500 flex items-center justify-center px-3 py-2 text-base font-medium border-[1.5px] border-green-500 text-green-500 rounded-md"><i class="fa-solid fa-bookmark mr-2"></i>Đã lưu</a>
            <a href="/" class="flex-1 shadow-sm <?php echo $active_home; ?> hover:bg-green-600 hover:text-white hover:scale-105 transition-all duration-500 flex items-center justify-center px-3 py-2 text-base font-medium border-[1.5px] border-green-500 text-green-500 rounded-md"><i class="fa-solid fa-house mr-2"></i>Trang chủ</a>
            <a href="/schedule" class="flex-1 shadow-sm <?php echo $active_schedule; ?> hover:bg-green-600 hover:text-white hover:scale-105 transition-all duration-500 flex items-center justify-center px-3 py-2 text-base font-medium border-[1.5px] border-green-500 text-green-500 rounded-md"><i class="fa-solid fa-location-dot mr-2"></i>Lịch trình</a>
        </div>

        <!-- <div class="my-3 w-[70%] h-[50px] flex flex-col items-center justify-center shadow-sm border rounded-md">Bộ Lọc</div> -->

        <div class="w-[70%] mt-3 flex items-center gap-x-4 cursor-pointer">
            <div class="flex shadow-sm items-center w-full border-[1.5px] border-green-500 px-3 rounded-md overflow-hidden hover:scale-105 transition-all duration-500">
                <ion-icon name="pencil-outline"></ion-icon>
                <input type="text" readonly placeholder="Chia sẻ trải nghiệm du lịch của bạn ngay nào! 🌍✈️...." class="border-none focus:ring-0 w-full" onclick="openDialog()">
            </div>
            <button class="px-3 py-2 shadow-sm hover:bg-green-100 hover:text-white transition-all duration-500 hover:scale-105 border-[1.5px] border-green-500 rounded-md"><i class="fa-solid fa-filter text-green-500"></i></button>
        </div>

        <!-- Dialog chứa form -->
        <div id="postDialog" class="fixed inset-0 z-50 bg-gray-800 bg-opacity-50 hidden flex items-center justify-center" onclick="closeDialog()">
            <div class="bg-white p-6 rounded-lg w-[90%] max-w-md" onclick="event.stopPropagation()">
                <h2 class="text-xl font-bold mb-4">Tạo bài post du lịch</h2>
                <form action="/posts/create" method="POST" id="postForm">
                    <input type="hidden" name="user_id" value="<?php echo htmlspecialchars($user['user_id']) ?>">
                    <div class="mb-2">
                        <label class="block text-sm font-medium text-gray-700">Tiêu đề</label>
                        <input type="text" name="title" class="w-full border border-gray-300 rounded-md p-2" required>
                    </div>
                    <div class="mb-2">
                        <label class="block text-sm font-medium text-gray-700">Nội dung</label>
                        <textarea name="content" class="w-full border border-gray-300 rounded-md p-2" rows="4" required></textarea>
                    </div>
                    <div class="mb-2">
                        <label class="block text-sm font-medium text-gray-700">Ảnh nổi bật</label>
                        <input type="file" id="featuredImage" accept="image/*" class="w-full">
                        <img id="preview" class="mt-2 max-w-xs h-[100px] object-cover hidden" alt="Preview">
                        <input type="text" name="featured_image" id="selectedImage" class="hidden">
                    </div>
                    <div class="mb-2">
                        <input type="checkbox" id="published" name="status" value="published" class="mr-2">
                        <label class="text-sm font-medium text-gray-700" for="published">Đăng ngay</label>

                        <input type="checkbox" id="draft" name="status" value="draft" class="mr-2 ml-4">
                        <label class="text-sm font-medium text-gray-700" for="draft">Lưu nháp</label>
                    </div>
                    <div class="mb-2">
                        <label class="block text-sm font-medium text-gray-700">Tags</label>
                        <input type="text" name="tags" class="w-full border border-gray-300 rounded-md p-2">
                    </div>
                    <div class="flex justify-end">
                        <button type="button" onclick="closeDialog()" class="px-4 py-2 mr-2 bg-gray-300 rounded-md">Hủy</button>
                        <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-md">Đăng</button>
                    </div>
                </form>
            </div>
        </div>

        <div class="flex flex-col space-y-6 w-full items-center mt-8">
            <?php if (empty($data['posts'])): ?>
                <p>No posts found.</p>
            <?php endif; ?>
            <?php foreach ($data['posts'] as $post): ?>
                <?php
                $datetime = $post['published_at'];
                $date = new DateTime($datetime);
                $formatted = $date->format("d/m/Y H:i");
                ?>
                <div class="w-[70%] flex flex-col border-2 border-gray-300 p-3 gap-y-3 rounded-lg shadow-sm">
                    <div class="flex items-center w-full justify-between">
                        <p class="text-sm font-medium text-gray-600">Bài viết</p>
                        <div class="flex items-center gap-x-3">
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
                            <!-- <button class=" hover:bg-gray-300 transition duration-500">
                                <ion-icon name="close"></ion-icon>
                            </button> -->
                        </div>
                    </div>
                    <div class="h-[1px] w-full bg-gray-200"></div>
                    <div class="flex w-full items-center justify-between">
                        <div class="flex items-center gap-x-2">
                            <div class="rounded-full overflow-hidden w-[48px] h-[48px] border">
                                <img width="48" height="48" src="/assets/images/placeholder.jpg" alt="user" />
                            </div>
                            <div class="flex flex-col">
                                <a class="text-base font-medium text-gray-800 hover:underline cursor-pointer"><?php echo htmlspecialchars($post['full_name']) ?></a>
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
                        </div>
                    </div>

                    <div class="flex flex-col justify-start gap-y-3">
                        <a href="/posts/view-post?slug=<?php echo htmlspecialchars($post['slug']) ?>" class="text-lg font-semibold underline text-gray-800 hover:underline hover:text-blue-500 transition-all duration-300"><?php echo htmlspecialchars($post['title']); ?></a>
                        <p class="text-base text-gray-500"><?php echo htmlspecialchars($post['content']); ?></p>
                        <div class="rounded-lg overflow-hidden hover:opacity-75 transition-all cursor-pointer duration-500 hover:scale-105">
                            <img class="object-cover w-full h-auto" src="<?php echo htmlspecialchars($post['featured_image']) ?>" alt="">
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
                        <button id="comment_ref" class="hover:bg-gray-200 transition duration-300 py-1 px-3 rounded-full flex items-center gap-x-2 text-base font-medium text-gray-700">
                            <i class="text-lg fa-regular fa-comment"></i>
                            <p>Comment</p>
                        </button>
                        <button class="hover:bg-gray-200 transition duration-300 py-1 px-3 rounded-full flex items-center gap-x-2 text-base font-medium text-gray-700">
                            <i class="text-lg fa-solid fa-share"></i>
                            <p>Share</p>
                        </button>
                    </div>

                    <!-- Dialog comment -->
                    <div id="dialog_comment_ref" class="fixed inset-0 z-50 bg-gray-800 bg-opacity-50 hidden items-center justify-center" onclick="closeDialog()">
                        <div class="bg-white w-[90%] max-w-md" onclick="event.stopPropagation()">
                            <p>Comment</p>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>

        </div>
    </main>
    <?php include "src/post/view/layouts/friends.php"; ?>
    <script>
        const dialogCommentRef = document.getElementById('dialog_comment_ref');

        document.getElementById('comment_ref').addEventListener('click', function() {
            dialogCommentRef.classList.remove('hidden');
            dialogCommentRef.classList.add('flex');
        });

        document.getElementById('published').addEventListener('change', function() {
            if (this.checked) {
                document.getElementById('draft').checked = false;
            }
        });

        document.getElementById('draft').addEventListener('change', function() {
            if (this.checked) {
                document.getElementById('published').checked = false;
            }
        });

        const selectedImage = document.getElementById('selectedImage');

        document.getElementById('featuredImage').addEventListener('change', function(event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();

                reader.onload = function(e) {
                    const base64String = e.target.result;
                    document.getElementById('preview').src = base64String;
                    document.getElementById('preview').classList.remove('hidden');
                    selectedImage.value = base64String;
                    console.log(base64String);
                };

                reader.readAsDataURL(file);
            }
        });

        function openDialog() {
            document.getElementById('postDialog').classList.remove('hidden');
        }

        function closeDialog() {
            document.getElementById('postDialog').classList.add('hidden');
        }

        document.getElementById('postForm').addEventListener('submit', async function(e) {

        });
    </script>
</div>