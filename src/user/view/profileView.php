<div class="w-full max-w-4xl mx-auto p-6 bg-white rounded-lg shadow-md mt-8">
    <h1 class="text-3xl font-bold text-green-500 mb-6">Trang cá nhân</h1>

    <?php
    $user = $data['user'];
    $posts = $data['posts'];
    ?>

    <!-- Cover Image -->
    <div class="relative h-60 mb-8 rounded-lg overflow-hidden shadow-md">
        <img
            src="<?php echo htmlspecialchars($user['cover_image'] ?? '/assets/images/default-cover.jpg'); ?>"
            alt="Cover Image"
            class="w-full h-full object-cover">
        <div class="absolute bottom-0 left-0 w-full bg-gradient-to-t from-black to-transparent opacity-50 h-20"></div>
    </div>

    <!-- Thông tin người dùng -->
    <div class="flex items-center gap-6 mb-8 relative">
        <img
            src="<?php echo htmlspecialchars($user['profile_picture'] ?? '/assets/images/placeholder.jpg'); ?>"
            alt="Avatar"
            class="w-28 h-28 rounded-full object-cover border-2 border-gray-200 shadow-md z-10">
        <div class="z-10">
            <h2 class="text-2xl font-semibold text-gray-800"><?php echo htmlspecialchars($user['full_name']); ?></h2>
            <p class="text-gray-600">Username: <?php echo htmlspecialchars($user['username']); ?></p>
            <p class="text-gray-500">Tham gia từ: <?php echo htmlspecialchars($user['created_at']); ?></p>
            <p class="text-gray-500">Email: <?php echo htmlspecialchars($user['email'] ?? 'Chưa cập nhật'); ?></p>
        </div>
        <button
            id="editProfileBtn"
            class="absolute top-4 right-4 px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 z-10"
            onclick="openEditProfileDialog()">
            Sửa hồ sơ
        </button>
    </div>

    <!-- Danh sách bài viết -->
    <div class="space-y-6">
        <h2 class="text-2xl font-bold text-gray-800 mb-4">Bài viết của tôi</h2>
        <?php if (empty($posts)): ?>
            <p class="text-gray-500">Bạn chưa có bài viết nào.</p>
        <?php else: ?>
            <?php foreach ($posts as $post): ?>
                <div class="border border-gray-200 p-4 rounded-lg shadow-sm relative">
                    <div class="absolute top-2 right-2">
                        <button
                            id="postDropdown_<?php echo $post['post_id']; ?>"
                            class="hover:bg-gray-200 p-2 rounded-full focus:outline-none"
                            onclick="toggleDropdown(<?php echo $post['post_id']; ?>)">
                            <i class="fa-solid fa-ellipsis-vertical"></i>
                        </button>
                        <div
                            id="dropdownMenu_<?php echo $post['post_id']; ?>"
                            class="absolute right-0 mt-2 w-32 bg-white rounded-md shadow-lg opacity-0 invisible transform translate-y-2 transition-all duration-300 z-10">
                            <ul class="py-1">
                                <li>
                                    <button
                                        class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900 w-full text-left"
                                        onclick="editPost(<?php echo $post['post_id']; ?>)">
                                        Sửa
                                    </button>
                                </li>
                                <li>
                                    <button
                                        class="block px-4 py-2 text-sm text-red-600 hover:bg-gray-100 hover:text-red-700 w-full text-left"
                                        onclick="deletePost(<?php echo $post['post_id']; ?>)">
                                        Xóa
                                    </button>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-800">
                        <a href="/posts/<?php echo htmlspecialchars($post['slug']); ?>" class="hover:underline hover:text-blue-600">
                            <?php echo htmlspecialchars($post['title']); ?>
                        </a>
                    </h3>
                    <p class="text-gray-600 mt-2"><?php echo htmlspecialchars(substr($post['content'], 0, 100)) . (strlen($post['content']) > 100 ? '...' : ''); ?></p>
                    <?php if (!empty($post['featured_image'])): ?>
                        <img src="<?php echo htmlspecialchars($post['featured_image']); ?>" alt="Featured Image" class="mt-2 w-full h-auto object-cover rounded-md">
                    <?php endif; ?>
                    <div class="mt-4 text-gray-500 flex items-center gap-4">
                        <span><i class="fa-regular fa-thumbs-up"></i> <?php echo $post['like_count']; ?></span>
                        <span><i class="fa-regular fa-comment"></i> <?php echo $post['comment_count']; ?></span>
                        <span><i class="fa-solid fa-share"></i> <?php echo $post['share_count']; ?></span>
                        <span>Đăng lúc: <?php echo (new DateTime($post['published_at']))->format('d/m/Y H:i'); ?></span>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

    <?php if (!empty($posts) && count($posts) >= 10): ?>
        <div class="mt-6 text-center">
            <a href="/profile?page=2" class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700">Xem thêm</a>
        </div>
    <?php endif; ?>

    <?php if (isset($_SESSION['toast'])): ?>
        <div class="toast <?php echo $_SESSION['toast']['type']; ?> fixed top-4 right-4 p-4 rounded-md shadow-md">
            <?php echo htmlspecialchars($_SESSION['toast']['message']); ?>
        </div>
        <?php unset($_SESSION['toast']); ?>
    <?php endif; ?>

    <!-- Dialog chỉnh sửa bài viết -->
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
                <div class="mb-2">
                    <label class="block text-sm font-medium text-gray-700">Nội dung</label>
                    <textarea name="content" id="editPostContent" class="w-full border border-gray-300 rounded-md p-2" rows="4" required></textarea>
                </div>
                <div class="mb-2">
                    <label class="block text-sm font-medium text-gray-700">Ảnh nổi bật</label>
                    <input type="file" id="editFeaturedImage" accept="image/*" class="w-full">
                    <img id="editPreview" class="mt-2 max-w-xs h-[100px] object-cover rounded-md hidden" alt="Preview">
                    <input type="hidden" name="featured_image" id="editSelectedImage" class="hidden">
                </div>
                <div class="flex justify-end">
                    <button type="button" onclick="closeEditPostDialog()" class="px-4 py-2 mr-2 bg-gray-300 rounded-md">Hủy</button>
                    <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-md">Lưu</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Dialog xóa bài viết (xác nhận) -->
    <div id="deletePostDialog" class="fixed inset-0 z-50 bg-gray-800 bg-opacity-50 hidden flex items-center justify-center" onclick="closeDeletePostDialog()">
        <div class="bg-white p-6 rounded-lg w-[90%] max-w-md" onclick="event.stopPropagation()">
            <h2 class="text-xl font-bold mb-4">Xác nhận xóa</h2>
            <p class="mb-4">Bạn có chắc chắn muốn xóa bài viết này? Hành động này không thể hoàn tác.</p>
            <input type="hidden" id="deletePostId">
            <div class="flex justify-end gap-2">
                <button onclick="closeDeletePostDialog()" class="px-4 py-2 bg-gray-300 rounded-md">Hủy</button>
                <button onclick="confirmDeletePost()" class="px-4 py-2 bg-red-600 text-white rounded-md">Xóa</button>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
    <script>
        // Toggle dropdown menu
        function toggleDropdown(postId) {
            const dropdown = document.getElementById(`dropdownMenu_${postId}`);
            dropdown.classList.toggle('opacity-0');
            dropdown.classList.toggle('invisible');
            dropdown.classList.toggle('translate-y-2');
            dropdown.classList.toggle('translate-y-0');
        }

        // Đóng dropdown khi nhấp ra ngoài
        document.addEventListener('click', function(event) {
            document.querySelectorAll('.dropdown-menu').forEach(dropdown => {
                if (!dropdown.contains(event.target) && !event.target.closest(`#postDropdown_${dropdown.id.split('_')[1]}`)) {
                    dropdown.classList.add('opacity-0', 'invisible', 'translate-y-2');
                    dropdown.classList.remove('translate-y-0');
                }
            });
        });

        // Mở dialog chỉnh sửa bài viết
        function editPost(postId) {
            fetch(`/api/posts/get?post_id=${postId}`)
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        document.getElementById('editPostId').value = data.data.post_id;
                        document.getElementById('editPostTitle').value = data.data.title;
                        document.getElementById('editPostStatus').value = data.data.status;
                        document.getElementById('editPostContent').value = data.data.content;
                        if (data.data.featured_image) {
                            document.getElementById('editPreview').src = data.data.featured_image;
                            document.getElementById('editPreview').classList.remove('hidden');
                            document.getElementById('editSelectedImage').value = data.data.featured_image;
                        }
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
            document.getElementById('editPreview').classList.add('hidden');
            document.getElementById('editSelectedImage').value = '';
        }

        // Xử lý upload ảnh trong dialog chỉnh sửa
        document.getElementById('editFeaturedImage').addEventListener('change', function(event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const base64String = e.target.result;
                    document.getElementById('editPreview').src = base64String;
                    document.getElementById('editPreview').classList.remove('hidden');
                    document.getElementById('editSelectedImage').value = base64String;
                };
                reader.readAsDataURL(file);
            }
        });

        // Xử lý submit form chỉnh sửa bài viết qua AJAX
        document.getElementById('editPostForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const postId = document.getElementById('editPostId').value;
            const title = document.getElementById('editPostTitle').value;
            const status = document.getElementById('editPostStatus').value;
            const content = document.getElementById('editPostContent').value;
            const featured_image = document.getElementById('editSelectedImage').value;

            fetch('/api/posts/update', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-Token': '<?php echo isset($_SESSION['csrf_token']) ? $_SESSION['csrf_token'] : ''; ?>'
                    },
                    body: JSON.stringify({
                        post_id: postId,
                        title: title,
                        status: status,
                        content: content,
                        featured_image: featured_image
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

        // Mở dialog xác nhận xóa bài viết
        function deletePost(postId) {
            document.getElementById('deletePostId').value = postId;
            document.getElementById('deletePostDialog').classList.remove('hidden');
            document.getElementById('deletePostDialog').classList.add('flex');
        }

        // Đóng dialog xác nhận xóa
        function closeDeletePostDialog() {
            document.getElementById('deletePostDialog').classList.add('hidden');
            document.getElementById('deletePostDialog').classList.remove('flex');
        }

        // Xác nhận và xóa bài viết qua AJAX
        function confirmDeletePost() {
            const postId = document.getElementById('deletePostId').value;

            fetch('/api/posts/delete', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-Token': '<?php echo isset($_SESSION['csrf_token']) ? $_SESSION['csrf_token'] : ''; ?>'
                    },
                    body: JSON.stringify({
                        post_id: postId
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
                        closeDeletePostDialog();
                        const postElement = document.querySelector(`#postDropdown_${postId}`).closest('.border');
                        if (postElement) {
                            postElement.remove();
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

        document.addEventListener('DOMContentLoaded', function() {
            const toast = document.querySelector('.toast');
            if (toast) {
                setTimeout(() => toast.style.display = 'none', 3000);
            }
        });
    </script>
</div>