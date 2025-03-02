<div class="w-full max-w-4xl mx-auto p-6 bg-white rounded-lg shadow-md mt-8">
    <h1 class="text-3xl font-bold text-green-500 mb-6">Bài viết đã lưu</h1>

    <?php
    $bookmarks = $data['bookmarks'];
    ?>

    <!-- Danh sách bài viết đã lưu -->
    <div class="space-y-6">
        <?php if (empty($bookmarks)): ?>
            <p class="text-gray-500">Bạn chưa lưu bài viết nào.</p>
        <?php else: ?>
            <?php foreach ($bookmarks as $bookmark): ?>
                <div class="border border-gray-200 p-4 rounded-lg shadow-sm">
                    <h3 class="text-xl font-semibold text-gray-800">
                        <a href="/posts/<?php echo htmlspecialchars($bookmark['slug']); ?>" class="hover:underline hover:text-blue-600">
                            <?php echo htmlspecialchars($bookmark['title']); ?>
                        </a>
                    </h3>
                    <p class="text-gray-600 mt-2"><?php echo htmlspecialchars(substr($bookmark['content'], 0, 100)) . (strlen($bookmark['content']) > 100 ? '...' : ''); ?></p>
                    <?php if (!empty($bookmark['featured_image'])): ?>
                        <img src="<?php echo htmlspecialchars($bookmark['featured_image']); ?>" alt="Featured Image" class="mt-2 w-full h-auto object-cover rounded-md">
                    <?php endif; ?>
                    <div class="mt-4 text-gray-500 flex items-center gap-4">
                        <span><i class="fa-regular fa-thumbs-up"></i> <?php echo $bookmark['like_count']; ?></span>
                        <span><i class="fa-regular fa-comment"></i> <?php echo $bookmark['comment_count']; ?></span>
                        <span><i class="fa-solid fa-share"></i> <?php echo $bookmark['share_count']; ?></span>
                        <span>Đăng lúc: <?php echo (new DateTime($bookmark['published_at']))->format('d/m/Y H:i'); ?></span>
                        <button
                            id="removeBookmark_<?php echo $bookmark['post_id']; ?>"
                            class="ml-4 px-3 py-1 bg-red-500 text-white rounded-md hover:bg-red-600 text-sm"
                            onclick="removeBookmark(<?php echo $bookmark['post_id']; ?>)">
                            Bỏ lưu
                        </button>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

    <?php if (!empty($bookmarks) && count($bookmarks) >= 10): ?>
        <div class="mt-6 text-center">
            <a href="/saved?page=2" class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700">Xem thêm</a>
        </div>
    <?php endif; ?>

    <?php if (isset($_SESSION['toast'])): ?>
        <div class="toast <?php echo $_SESSION['toast']['type']; ?> fixed top-4 right-4 p-4 rounded-md shadow-md">
            <?php echo htmlspecialchars($_SESSION['toast']['message']); ?>
        </div>
        <?php unset($_SESSION['toast']); ?>
    <?php endif; ?>

    <script src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const toast = document.querySelector('.toast');
            if (toast) {
                setTimeout(() => toast.style.display = 'none', 3000);
            }
        });

        function removeBookmark(postId) {
            const userId = '<?php echo isset($_SESSION['user']['user_id']) ? $_SESSION['user']['user_id'] : 0; ?>';

            if (!userId) {
                Toastify({
                    text: "Bạn cần đăng nhập để thực hiện thao tác này.",
                    duration: 3000,
                    gravity: "top",
                    position: "right",
                    backgroundColor: '#ef4444',
                    stopOnFocus: true
                }).showToast();
                return;
            }

            fetch('/api/toggle-bookmark', {
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
                        Toastify({
                            text: data.message,
                            duration: 3000,
                            gravity: "top",
                            position: "right",
                            backgroundColor: '#22c55e',
                            stopOnFocus: true
                        }).showToast();
                        // Xóa bài post khỏi giao diện
                        const bookmarkElement = document.querySelector(`#removeBookmark_${postId}`).closest('.border');
                        if (bookmarkElement) {
                            bookmarkElement.remove();
                        }
                    } else {
                        Toastify({
                            text: data.message || "Lỗi khi bỏ lưu bài viết.",
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
                        text: "Có lỗi xảy ra khi bỏ lưu bài viết.",
                        duration: 3000,
                        gravity: "top",
                        position: "right",
                        backgroundColor: '#ef4444',
                        stopOnFocus: true
                    }).showToast();
                });
        }
    </script>
</div>