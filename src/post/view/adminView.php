<div class="w-full max-w-6xl mx-auto p-6 bg-white rounded-lg shadow-md mt-8">
    <h1 class="text-3xl font-bold text-green-500 mb-6">Quản trị - Danh sách bài viết</h1>

    <!-- Thanh lọc và tìm kiếm -->
    <div class="mb-4 flex gap-4">
        <input type="text" id="searchInput" value="<?php echo htmlspecialchars($search); ?>" placeholder="Tìm kiếm theo tiêu đề hoặc nội dung..." class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500">
        <select id="statusFilter" class="px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500">
            <option value="">Tất cả trạng thái</option>
            <option value="published" <?php echo $status === 'published' ? 'selected' : ''; ?>>Published</option>
            <option value="draft" <?php echo $status === 'draft' ? 'selected' : ''; ?>>Draft</option>
        </select>
        <button onclick="filterPosts()" class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700">Lọc</button>
    </div>

    <!-- Bảng danh sách bài viết -->
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-200">
                    <th class="py-2 px-4 border-b">ID</th>
                    <th class="py-2 px-4 border-b">Tiêu đề</th>
                    <th class="py-2 px-4 border-b">Tác giả</th>
                    <th class="py-2 px-4 border-b">Trạng thái</th>
                    <th class="py-2 px-4 border-b">Lượt thích</th>
                    <th class="py-2 px-4 border-b">Lượt bình luận</th>
                    <th class="py-2 px-4 border-b">Lượt chia sẻ</th>
                    <th class="py-2 px-4 border-b">Hành động</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($posts)): ?>
                    <tr>
                        <td colspan="8" class="py-4 text-center text-gray-500">Không có bài viết nào.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($posts as $post): ?>
                        <tr class="border-b">
                            <td class="py-2 px-4"><?php echo $post['post_id']; ?></td>
                            <td class="py-2 px-4"><a href="/posts/<?php echo htmlspecialchars($post['slug']); ?>" class="text-blue-600 hover:underline"><?php echo htmlspecialchars($post['title']); ?></a></td>
                            <td class="py-2 px-4"><?php echo htmlspecialchars($post['full_name']) . ' (' . $post['username'] . ')'; ?></td>
                            <td class="py-2 px-4"><?php echo ucfirst($post['status']); ?></td>
                            <td class="py-2 px-4"><?php echo $post['like_count']; ?></td>
                            <td class="py-2 px-4"><?php echo $post['comment_count']; ?></td>
                            <td class="py-2 px-4"><?php echo $post['share_count']; ?></td>
                            <td class="py-2 px-4">
                                <button
                                    class="px-2 py-1 bg-blue-500 text-white rounded-md hover:bg-blue-600 text-sm mr-2"
                                    onclick="editPost(<?php echo $post['post_id']; ?>)">
                                    Sửa
                                </button>
                                <button
                                    class="px-2 py-1 bg-red-500 text-white rounded-md hover:bg-red-600 text-sm"
                                    onclick="deletePost(<?php echo $post['post_id']; ?>)">
                                    Xóa
                                </button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- Phân trang -->
    <?php
    // $totalPosts = count($post_model->getAllPosts(9999, 0, $status, $search)); // Giả sử lấy tổng số posts
    // $totalPages = ceil($totalPosts / $limit);
    ?>
    <div class="mt-6 text-center">
        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
            <a href="/admin?page=<?php echo $i; ?>&limit=<?php echo $limit; ?>&status=<?php echo urlencode($status ?? ''); ?>&search=<?php echo urlencode($search ?? ''); ?>" class="px-3 py-1 mx-1 <?php echo $page === $i ? 'bg-green-600 text-white' : 'bg-gray-200 text-gray-700'; ?> rounded-md hover:bg-green-700 hover:text-white">
                <?php echo $i; ?>
            </a>
        <?php endfor; ?>
    </div>

    <!-- Dialog chỉnh sửa bài viết (tùy chọn, có thể mở rộng) -->
    <div id="editPostDialog" class="fixed inset-0 z-50 bg-gray-800 bg-opacity-50 hidden flex items-center justify-center" onclick="closeEditPostDialog()">
        <div class="bg-white p-6 rounded-lg w-[90%] max-w-md" onclick="event.stopPropagation()">
            <h2 class="text-xl font-bold mb-4">Chỉnh sửa bài viết</h2>
            <form id="editPostForm" class="space-y-4">
                <input type="hidden" name="post_id" id="editPostId">
                <div class="mb-2">
                    <label class="block text-sm font-medium text-gray-700">Tiêu đề</label>
                    <input type="text" name="title" id="editPostTitle" class="w-full border border-gray-300 rounded-md p-2" required>
                </div>
                <div class="mb-2">
                    <label class="block text-sm font-medium text-gray-700">Trạng thái</label>
                    <select name="status" id="editPostStatus" class="w-full border border-gray-300 rounded-md p-2">
                        <option value="published">Published</option>
                        <option value="draft">Draft</option>
                    </select>
                </div>
                <div class="flex justify-end">
                    <button type="button" onclick="closeEditPostDialog()" class="px-4 py-2 mr-2 bg-gray-300 rounded-md">Hủy</button>
                    <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-md">Lưu</button>
                </div>
            </form>
        </div>
    </div>

    <?php if (isset($_SESSION['toast'])): ?>
        <div class="toast <?php echo $_SESSION['toast']['type']; ?> fixed top-4 right-4 p-4 rounded-md shadow-md">
            <?php echo htmlspecialchars($_SESSION['toast']['message']); ?>
        </div>
        <?php unset($_SESSION['toast']); ?>
    <?php endif; ?>

    <script src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
    <script>
        // Lọc danh sách bài viết
        function filterPosts() {
            const search = document.getElementById('searchInput').value;
            const status = document.getElementById('statusFilter').value;
            window.location.href = `/admin?search=${encodeURIComponent(search)}&status=${encodeURIComponent(status)}&page=1&limit=10`;
        }

        // Mở dialog chỉnh sửa bài viết
        function editPost(postId) {
            fetch(`/api/posts/${postId}`)
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        document.getElementById('editPostId').value = data.data.post_id;
                        document.getElementById('editPostTitle').value = data.data.title;
                        document.getElementById('editPostStatus').value = data.data.status;
                        document.getElementById('editPostDialog').classList.remove('hidden');
                        document.getElementById('editPostDialog').classList.add('flex');
                    } else {
                        Toastify({
                            text: data.message,
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
                        text: "Không thể tải thông tin bài viết.",
                        duration: 3000,
                        gravity: "top",
                        position: "right",
                        backgroundColor: '#ef4444',
                        stopOnFocus: true
                    }).showToast();
                });
        }

        // Đóng dialog chỉnh sửa bài viết
        function closeEditPostDialog() {
            document.getElementById('editPostDialog').classList.add('hidden');
            document.getElementById('editPostDialog').classList.remove('flex');
        }

        // Xử lý submit form chỉnh sửa bài viết qua AJAX
        document.getElementById('editPostForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const postId = document.getElementById('editPostId').value;
            const title = document.getElementById('editPostTitle').value;
            const status = document.getElementById('editPostStatus').value;

            fetch('/api/admin/update-post', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-Token': '<?php echo isset($_SESSION['csrf_token']) ? $_SESSION['csrf_token'] : ''; ?>'
                    },
                    body: JSON.stringify({
                        post_id: postId,
                        action: 'update',
                        title: title,
                        status: status
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
                        closeEditPostDialog();
                        window.location.reload(); // Tải lại trang để cập nhật danh sách
                    } else {
                        Toastify({
                            text: data.message,
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
                        text: "Có lỗi xảy ra khi cập nhật bài viết.",
                        duration: 3000,
                        gravity: "top",
                        position: "right",
                        backgroundColor: '#ef4444',
                        stopOnFocus: true
                    }).showToast();
                });
        });

        // Xóa bài viết qua AJAX
        function deletePost(postId) {
            if (!confirm('Bạn có chắc chắn muốn xóa bài viết này?')) {
                return;
            }

            fetch('/api/admin/update-post', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-Token': '<?php echo isset($_SESSION['csrf_token']) ? $_SESSION['csrf_token'] : ''; ?>'
                    },
                    body: JSON.stringify({
                        post_id: postId,
                        action: 'delete'
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
                        const row = document.querySelector(`tr[data-post-id="${postId}"]`);
                        if (row) {
                            row.remove();
                        }
                    } else {
                        Toastify({
                            text: data.message || "Lỗi khi xóa bài viết.",
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
                        text: "Có lỗi xảy ra khi xóa bài viết.",
                        duration: 3000,
                        gravity: "top",
                        position: "right",
                        backgroundColor: '#ef4444',
                        stopOnFocus: true
                    }).showToast();
                });
        }

        // Xử lý ẩn toast sau vài giây
        document.addEventListener('DOMContentLoaded', function() {
            const toast = document.querySelector('.toast');
            if (toast) {
                setTimeout(() => toast.style.display = 'none', 3000);
            }
        });
    </script>
</div>