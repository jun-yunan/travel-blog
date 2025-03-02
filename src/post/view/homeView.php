<div class="w-full h-full flex">
    <?php include "src/post/view/layouts/side-bar.php"; ?>
    <?php
    ini_set('memory_limit', '128M');
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
                <div class="w-[70%] flex flex-col border-[1.5px] border-green-500  p-3 gap-y-3 rounded-lg shadow-sm">
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

                            <!-- <img width="48" height="48" src="/assets/images/placeholder.jpg" alt="user" /> -->
                            <img class="w-[54px] h-[54px] hover:opacity-70 cursor-pointer rounded-full object-cover" src="<?php echo isset($post['profile_picture']) ?  htmlspecialchars($post['profile_picture']) : '/assets/images/placeholder.jpg' ?>" alt="user" />

                            <div class="flex flex-col">
                                <a class="text-base font-medium text-gray-800 hover:underline cursor-pointer"><?php echo htmlspecialchars($post['full_name']) ?></a>
                                <p class="text-sm font-normal text-gray-500"><?php echo htmlspecialchars($formatted) ?></p>
                            </div>
                        </div>
                        <div class="flex items-center gap-x-3">
                            <!-- <div class="flex items-center gap-x-1 hover:bg-green-600 transition duration-500 text-green-500 hover:text-white border-[1.5px] border-green-500 py-[2px] px-3 cursor-pointer rounded-full">
                                <i class="fa-solid fa-bookmark"></i>
                                <p class="text-sm font-medium">Lưu bài</p>
                            </div> -->
                            <?php
                            $isBookmarked = $post['bookmarked'] > 0 ? 'text-white bg-green-500' : 'text-green-500';
                            ?>
                            <div id="bookmark_<?php echo $post['post_id']; ?>" class="flex items-center gap-x-1 hover:bg-green-600 <?php echo $isBookmarked ?> transition duration-500  hover:text-white border-[1.5px] border-green-500 py-[2px] px-3 cursor-pointer rounded-full bookmark-btn" data-post-id="<?php echo $post['post_id']; ?>">
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
                        <a href="/posts/view-post?slug=<?php echo htmlspecialchars($post['slug']); ?>" class="text-lg font-semibold  text-gray-800 hover:underline hover:text-blue-500"><?php echo htmlspecialchars($post['title']); ?></a>
                        <p class="text-base text-gray-500"><?php echo htmlspecialchars($post['content']); ?></p>
                        <div class="rounded-lg overflow-hidden hover:opacity-75 transition-all cursor-pointer duration-500 hover:scale-105">
                            <img class="object-cover w-full h-auto" src="<?php echo htmlspecialchars($post['featured_image']); ?>" alt="">
                        </div>
                    </div>

                    <div class="w-full flex items-center justify-between">
                        <a href="/" class="flex hover:underline hover:text-blue-600 text-sm font-medium text-gray-700 items-center gap-x-1">
                            <i class="fa-regular fa-thumbs-up"></i>
                            <p id="like_count_ref_<?php echo $post['post_id']; ?>" data-post-id="<?php echo $post['post_id']; ?> "><?php echo htmlspecialchars($post['like_count']) ?></p>
                        </a>
                        <div class=" flex items-center gap-x-3">
                            <a href="/" class="flex hover:underline hover:text-blue-600 text-sm font-medium text-gray-700 items-center gap-x-1">
                                <i class="fa-regular fa-comment"></i>
                                <p class=""><?php echo htmlspecialchars($post['comment_count']) ?></p>
                            </a>
                            <a href="/" class="flex hover:underline hover:text-blue-600 text-sm font-medium text-gray-700 items-center gap-x-1">
                                <i class="fa-solid fa-share"></i>
                                <p id="share_count_ref_<?php echo $post['post_id']; ?>" data-post-id="<?php echo $post['post_id']; ?>" class=""><?php echo htmlspecialchars($post['share_count']); ?></p>
                            </a>

                        </div>
                    </div>

                    <div class=" h-[1px] w-full bg-gray-200">
                    </div>

                    <div class="flex w-full justify-around">
                        <?php
                        $isUserLiked = $post['liked'] > 0 ? 'text-blue-600' : '';
                        $postUrl = "http://travel-blog/posts/" . $post['slug'] . "?id=" . $post['post_id'];

                        ?>

                        <button id="like_<?php echo $post['post_id']; ?>" class="hover:bg-gray-200 <?php echo $isUserLiked; ?> transition duration-300 py-1 px-3 rounded-full flex items-center gap-x-2 text-base font-medium text-gray-700 like-btn" data-post-id="<?php echo $post['post_id']; ?>">
                            <i class="text-lg fa-regular fa-thumbs-up"></i>
                            <!-- <span id="like_count_<?php echo $post['post_id']; ?>"><?php echo $post['like_count'] ?? 0; ?></span> -->
                            <span id="like_count_<?php echo $post['post_id']; ?>">Thích</span>
                        </button>
                        <button id="comment_<?php echo $post['post_id']; ?>" class="hover:bg-gray-200  transition duration-300 py-1 px-3 rounded-full flex items-center gap-x-2 text-base font-medium text-gray-700 comment-btn" data-post-id="<?php echo $post['post_id']; ?>">
                            <i class="text-lg fa-regular fa-comment"></i>
                            <p>Bình luận</p>
                        </button>
                        <!-- <button class="hover:bg-gray-200 transition duration-300 py-1 px-3 rounded-full flex items-center gap-x-2 text-base font-medium text-gray-700">
                        <i class="text-lg fa-solid fa-share"></i>
                        <p>Share</p>
                    </button> -->

                        <!-- <button id="share_<?php echo $post['post_id']; ?>" class="hover:bg-gray-200 transition duration-300 py-1 px-3 rounded-full flex items-center gap-x-2 text-base font-medium text-gray-700 share-btn" data-post-url="<?php echo $postUrl; ?>" data-post-id="<?php echo $post['post_id']; ?>">
                        <i class="text-lg fa-solid fa-share"></i>
                        <p>Share</p>
                    </button> -->

                        <button id="share_<?php echo $post['post_id']; ?>" class="hover:bg-gray-200 transition duration-300 py-1 px-3 rounded-full flex items-center gap-x-2 text-base font-medium text-gray-700 share-btn" data-post-url="<?php echo $postUrl ?>" data-post-id="<?php echo $post['post_id']; ?>">
                            <i class="text-lg fa-solid fa-share"></i>
                            <!-- <span id="share_count_<?php echo $post['post_id']; ?>"><?php echo $post['share_count'] ?? 0; ?></span> -->
                            <span id="share_count_<?php echo $post['post_id']; ?>">Chia sẻ</span>
                        </button>
                    </div>

                    <div id="dialog_share_<?php echo $post['post_id']; ?>" class="fixed inset-0 z-50 bg-gray-800 bg-opacity-50 hidden items-center justify-center" onclick="closeShareDialog(<?php echo $post['post_id']; ?>)">
                        <div class="bg-white p-6 rounded-lg w-[90%] max-w-2xl" onclick="event.stopPropagation()">
                            <h2 class="text-xl font-bold mb-4">Chia sẻ bài viết</h2>
                            <div class="space-y-4">
                                <div class="flex w-full items-center gap-x-2">
                                    <input id="postLink_<?php echo $post['post_id']; ?>" type="text" class="w-full px-4 py-2 border border-gray-300 rounded-md" value="<?php echo $postUrl; ?>" readonly>
                                    <button onclick="copyLink(<?php echo $post['post_id']; ?>)" class="px-4 py-2.5 min-w-[150px] justify-center bg-green-500 text-white rounded-md text-sm font-medium flex items-center">
                                        <i class="fa-solid fa-copy mr-2"></i>
                                        <p>Sao chép</p>
                                    </button>
                                </div>
                                <div class="flex items-center gap-x-3">
                                    <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo urlencode($postUrl); ?>" target="_blank" class="px-4 py-2 bg-blue-600 text-white rounded-md flex items-center gap-2" onclick="handleShare(<?php echo $post['post_id']; ?>, 'facebook')">
                                        <i class="fab fa-facebook-f"></i> Facebook
                                    </a>
                                    <a href="https://twitter.com/intent/tweet?url=<?php echo urlencode($postUrl); ?>&text=<?php echo urlencode($post['title']); ?>" target="_blank" class="px-4 py-2 bg-blue-200 text-white rounded-md flex items-center gap-2" onclick="handleShare(<?php echo $post['post_id']; ?>, 'twitter')">
                                        <i class="fab fa-twitter"></i> Twitter
                                    </a>
                                    <a href="https://www.instagram.com/?url=<?php echo urlencode($postUrl); ?>" target="_blank" class="px-4 py-2 bg-pink-600 text-white rounded-md flex items-center gap-2" onclick="handleShare(<?php echo $post['post_id']; ?>, 'instagram')">
                                        <i class="fab fa-instagram"></i> Instagram
                                    </a>
                                </div>
                            </div>
                            <button onclick="closeShareDialog(<?php echo $post['post_id']; ?>)" class="mt-4 px-4 py-2 bg-gray-300 rounded-md">Đóng</button>
                        </div>
                    </div>


                    <div id="dialog_comment_<?php echo $post['post_id']; ?>" class="fixed inset-0 z-50 bg-gray-800 bg-opacity-50 hidden items-center justify-center" onclick="closeCommentDialog(<?php echo $post['post_id']; ?>)">
                        <div class="bg-white p-6 rounded-lg w-[90%] max-w-md" onclick="event.stopPropagation()">
                            <h2 class="text-xl font-bold mb-4">Bình luận cho bài viết: <?php echo htmlspecialchars($post['title']); ?></h2>
                            <div id="commentList_<?php echo $post['post_id']; ?>" class="mb-4 max-h-[300px] overflow-y-auto">
                            </div>
                            <input type="hidden" id="postId_<?php echo $post['post_id']; ?>" value="<?php echo $post['post_id']; ?>">
                            <input type="hidden" id="userId_<?php echo $post['post_id']; ?>" value="<?php echo htmlspecialchars($user['user_id']); ?>">
                            <div class="mb-2">
                                <label class="block text-sm font-medium text-gray-700">Nội dung bình luận</label>
                                <textarea id="commentContent_<?php echo $post['post_id']; ?>" class="w-full border border-gray-300 rounded-md p-2" rows="4" placeholder="Nhập bình luận của bạn..."></textarea>
                            </div>
                            <div class="flex justify-end">
                                <button onclick="closeCommentDialog(<?php echo $post['post_id']; ?>)" class="px-4 py-2 mr-2 bg-gray-300 rounded-md">Hủy</button>
                                <button id="submitComment_<?php echo $post['post_id']; ?>" class="px-4 py-2 bg-green-600 text-white rounded-md">Gửi</button>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
            <a href="" class="bg-green-500 text-white hover:bg-green-600 cursor-pointer px-3 py-2 rounded-md">Xem thêm</a>
        </div>
    </main>
    <?php include "src/post/view/layouts/friends.php"; ?>
    <script>
        document.querySelectorAll('.share-btn').forEach(button => {
            button.addEventListener('click', function() {
                const postId = this.getAttribute('data-post-id');
                openShareDialog(postId);
            });
        });

        function openShareDialog(postId) {
            document.getElementById(`dialog_share_${postId}`).classList.remove('hidden');
            document.getElementById(`dialog_share_${postId}`).classList.add('flex');
        }

        function closeShareDialog(postId) {
            document.getElementById(`dialog_share_${postId}`).classList.add('hidden');
            document.getElementById(`dialog_share_${postId}`).classList.remove('flex');
        }


        function copyLink(postId) {
            const linkInput = document.getElementById(`postLink_${postId}`);
            navigator.clipboard.writeText(linkInput.value).then(() => {
                Toastify({
                    text: "Đã sao chép liên kết!",
                    duration: 3000,
                    gravity: "top",
                    position: "right",
                    backgroundColor: '#22c55e',
                    stopOnFocus: true
                }).showToast();
            }).catch(err => {
                console.error('Lỗi sao chép:', err);
                Toastify({
                    text: "Không thể sao chép liên kết.",
                    duration: 3000,
                    gravity: "top",
                    position: "right",
                    backgroundColor: '#ef4444',
                    stopOnFocus: true
                }).showToast();
            });
        }


        function handleShare(postId, platform) {
            const userId = '<?php echo isset($_SESSION['user']['user_id']) ? $_SESSION['user']['user_id'] : 0; ?>';
            const postUrl = document.getElementById(`share_${postId}`).getAttribute('data-post-url');

            if (!userId) {
                Toastify({
                    text: "Bạn cần đăng nhập để chia sẻ bài viết.",
                    duration: 3000,
                    gravity: "top",
                    position: "right",
                    backgroundColor: '#ef4444',
                    stopOnFocus: true
                }).showToast();
                return false;
            }

            fetch('/api/toggle-share', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-Token': '<?php echo isset($_SESSION['csrf_token']) ? $_SESSION['csrf_token'] : ''; ?>'
                    },
                    body: JSON.stringify({
                        post_id: postId,
                        user_id: userId
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        const shareCountElement = document.getElementById(`share_count_${postId}`);
                        // shareCountElement.textContent = data.share_count;
                        document.getElementById(`share_count_ref_${postId}`).textContent = data.share_count;
                        Toastify({
                            text: data.message,
                            duration: 3000,
                            gravity: "top",
                            position: "right",
                            backgroundColor: '#22c55e',
                            stopOnFocus: true
                        }).showToast();
                    } else {
                        Toastify({
                            text: data.message || "Lỗi khi chia sẻ bài viết.",
                            duration: 3000,
                            gravity: "top",
                            position: "right",
                            backgroundColor: '#ef4444',
                            stopOnFocus: true
                        }).showToast();
                    }
                })
                .catch(error => {
                    console.error('Lỗi:', error);
                    Toastify({
                        text: "Có lỗi xảy ra khi chia sẻ bài viết.",
                        duration: 3000,
                        gravity: "top",
                        position: "right",
                        backgroundColor: '#ef4444',
                        stopOnFocus: true
                    }).showToast();
                });

            return true;
        }


        // Xử lý sự kiện click vào nút "Lưu bài"
        document.querySelectorAll('.bookmark-btn').forEach(button => {
            button.addEventListener('click', async function() {
                const postId = this.getAttribute('data-post-id');
                const userId = '<?php echo isset($_SESSION['user']['user_id']) ? $_SESSION['user']['user_id'] : 0; ?>';

                if (!userId) {
                    Toastify({
                        text: "Bạn cần đăng nhập để lưu bài viết.",
                        duration: 3000,
                        gravity: "top",
                        position: "right",
                        backgroundColor: '#ef4444',
                        stopOnFocus: true
                    }).showToast();
                    return;
                }

                await fetch('/api/toggle-bookmark', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-Token': '<?php echo isset($_SESSION['csrf_token']) ? $_SESSION['csrf_token'] : ''; ?>'
                        },
                        body: JSON.stringify({
                            post_id: postId,
                            user_id: userId
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.status === 'success') {
                            const bookmarkButton = document.getElementById(`bookmark_${postId}`);
                            if (data.bookmarked) {
                                bookmarkButton.classList.remove('text-green-500');
                                bookmarkButton.classList.add('text-white', 'bg-green-500');
                                // bookmarkButton.querySelector('i').classList.add('fa-solid');
                            } else {
                                bookmarkButton.classList.remove('text-white', 'bg-green-500');
                                bookmarkButton.classList.add('text-green-500');
                                // bookmarkButton.querySelector('i').classList.remove('fa-solid');
                            }
                            Toastify({
                                text: data.message,
                                duration: 3000,
                                gravity: "top",
                                position: "right",
                                backgroundColor: '#22c55e',
                                stopOnFocus: true
                            }).showToast();
                        } else {
                            Toastify({
                                text: data.message || "Lỗi khi lưu bài viết.",
                                duration: 3000,
                                gravity: "top",
                                position: "right",
                                backgroundColor: '#ef4444',
                                stopOnFocus: true
                            }).showToast();
                        }
                    })
                    .catch(error => {
                        console.error('Lỗi:', error);
                        Toastify({
                            text: "Có lỗi xảy ra khi lưu bài viết.",
                            duration: 3000,
                            gravity: "top",
                            position: "right",
                            backgroundColor: '#ef4444',
                            stopOnFocus: true
                        }).showToast();
                    });
            });
        });

        document.querySelectorAll('.like-btn').forEach(button => {
            button.addEventListener('click', function() {
                const postId = this.getAttribute('data-post-id');
                const userId = '<?php echo isset($_SESSION['user']['user_id']) ? $_SESSION['user']['user_id'] : 0; ?>';

                if (!userId) {
                    Toastify({
                        text: "Bạn cần đăng nhập để thích bài viết.",
                        duration: 3000,
                        gravity: "top",
                        position: "right",
                        backgroundColor: '#ef4444',
                        stopOnFocus: true
                    }).showToast();
                    return;
                }

                fetch('/api/toggle-like', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-Token': '<?php echo isset($_SESSION['csrf_token']) ? $_SESSION['csrf_token'] : ''; ?>'
                        },
                        body: JSON.stringify({
                            post_id: postId,
                            user_id: userId
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.status === 'success') {
                            const likeCountElement = document.getElementById(`like_count_${postId}`);
                            const likeButton = document.getElementById(`like_${postId}`);

                            document.getElementById(`like_count_ref_${postId}`).textContent = data.like_count;

                            if (data.liked) {
                                // likeButton.classList.remove('fa-regular');
                                // likeButton.classList.add('fa-solid');
                                likeButton.classList.add('text-blue-600');
                            } else {
                                // likeButton.classList.remove('fa-solid', 'text-blue-600');
                                // likeButton.classList.add('fa-regular');
                                likeButton.classList.remove('text-blue-600');
                            }

                            // likeCountElement.textContent = data.like_count;
                            Toastify({
                                text: data.message,
                                duration: 3000,
                                gravity: "top",
                                position: "right",
                                backgroundColor: '#22c55e',
                                stopOnFocus: true
                            }).showToast();
                        } else {
                            Toastify({
                                text: data.message || "Lỗi khi xử lý lượt thích.",
                                duration: 3000,
                                gravity: "top",
                                position: "right",
                                backgroundColor: '#ef4444',
                                stopOnFocus: true
                            }).showToast();
                        }
                    })
                    .catch(error => {
                        console.error('Lỗi:', error);
                        Toastify({
                            text: "Có lỗi xảy ra khi xử lý lượt thích.",
                            duration: 3000,
                            gravity: "top",
                            position: "right",
                            backgroundColor: '#ef4444',
                            stopOnFocus: true
                        }).showToast();
                    });
            });
        });

        document.querySelectorAll('.comment-btn').forEach(button => {
            button.addEventListener('click', function() {
                const postId = this.getAttribute('data-post-id');
                openCommentDialog(postId);
            });
        });

        function openCommentDialog(postId) {
            document.getElementById(`dialog_comment_${postId}`).classList.remove('hidden');
            document.getElementById(`dialog_comment_${postId}`).classList.add('flex');

            fetch(`/api/comments/get?post_id=${postId}`)
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        const commentList = document.getElementById(`commentList_${postId}`);
                        if (data.data.length > 0) {
                            commentList.innerHTML = data.data.map(comment => `
                       <div class="p-2 flex flex-col items-start gap-y-2 border-b border-gray-200">
                            <div class="flex items-center gap-2">
                                <img src="${comment.profile_picture || '/assets/images/placeholder.jpg'}" class="w-10 h-10 rounded-full object-cover" alt="Avatar">
                                <div class="flex flex-col items-start justify-start gap-1">
                                    <p class="text-sm font-medium text-gray-800">${comment.full_name} (${comment.username})</p>
                                    <p class="text-xs text-gray-500">${comment.created_at}</p>
                                </div>
                            </div>
                            <p class="ml-12 text-base font-medium text-gray-600">${comment.content}</p>
                        </div>
                    `).join('');
                        } else {
                            commentList.innerHTML = '<p class="text-gray-500">Không có bình luận nào.</p>';
                        }
                    } else {
                        document.getElementById(`commentList_${postId}`).innerHTML = '<p class="text-red-500">Có lỗi xảy ra: ' + data.message + '</p>';
                    }
                })
                .catch(error => {
                    console.error('Lỗi:', error);
                    document.getElementById(`commentList_${postId}`).innerHTML = '<p class="text-red-500">Không thể tải bình luận.</p>';
                });
        }

        function closeCommentDialog(postId) {
            document.getElementById(`dialog_comment_${postId}`).classList.add('hidden');
            document.getElementById(`dialog_comment_${postId}`).classList.remove('flex');
        }

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

        document.querySelectorAll('[id^="submitComment_"]').forEach(button => {
            button.addEventListener('click', function() {
                const postId = this.id.split('_')[1]; // Lấy postId từ id của nút (submitComment_<post_id>)
                const userId = document.getElementById(`userId_${postId}`).value;
                const content = document.getElementById(`commentContent_${postId}`).value.trim();

                if (!content) {
                    Toastify({
                        text: "Nội dung bình luận không được để trống.",
                        duration: 3000,
                        gravity: "top",
                        position: "right",
                        backgroundColor: '#ef4444',
                        stopOnFocus: true
                    }).showToast();
                    return;
                }

                fetch('/api/comments/create', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-Token': '<?php echo isset($_SESSION['csrf_token']) ? $_SESSION['csrf_token'] : ''; ?>'
                        },
                        body: JSON.stringify({
                            post_id: postId,
                            user_id: userId,
                            content: content
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.status === 'success') {
                            Toastify({
                                text: "Bình luận của bạn đã được gửi.",
                                duration: 3000,
                                gravity: "top",
                                position: "right",
                                backgroundColor: '#22c55e',
                                stopOnFocus: true
                            }).showToast();
                            closeCommentDialog(postId);
                            // Cập nhật số lượng bình luận
                            const commentCountElement = document.querySelector(`#comment_count_${postId}`);
                            if (commentCountElement) {
                                const currentCount = parseInt(commentCountElement.textContent) || 0;
                                commentCountElement.textContent = currentCount + 1;
                            }
                            // Tải lại danh sách bình luận
                            openCommentDialog(postId);
                        } else {
                            Toastify({
                                text: data.message || "Gửi bình luận thất bại.",
                                duration: 3000,
                                gravity: "top",
                                position: "right",
                                backgroundColor: '#ef4444',
                                stopOnFocus: true
                            }).showToast();
                        }
                    })
                    .catch(error => {
                        console.error('Lỗi:', error);
                        Toastify({
                            text: "Có lỗi xảy ra khi gửi bình luận.",
                            duration: 3000,
                            gravity: "top",
                            position: "right",
                            backgroundColor: '#ef4444',
                            stopOnFocus: true
                        }).showToast();
                    });
            });
        });
    </script>
</div>