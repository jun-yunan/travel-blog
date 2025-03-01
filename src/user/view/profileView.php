<div class="w-full max-w-4xl mx-auto p-6 bg-white rounded-lg shadow-md mt-8">
    <h1 class="text-3xl font-bold text-green-500 mb-6">Trang cá nhân</h1>

    <?php
    $user = $data['user'];
    $posts = $data['posts'];
    ?>

    <!-- Cover Image -->
    <div class="relative h-48 mb-8 rounded-lg overflow-hidden shadow-md">
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
            class="w-24 h-24 rounded-full object-cover border-2 border-gray-200 shadow-md z-10">
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
                <div class="border border-gray-200 p-4 rounded-lg shadow-sm">
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

    <!-- Phân trang (nếu cần) -->
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

    <!-- Dialog chỉnh sửa hồ sơ -->
    <div id="editProfileDialog" class="fixed inset-0 z-50 bg-gray-800 bg-opacity-50 hidden flex items-center justify-center" onclick="closeEditProfileDialog()">
        <div class="bg-white p-6 rounded-lg w-[90%] max-w-md" onclick="event.stopPropagation()">
            <h2 class="text-xl font-bold mb-4">Chỉnh sửa hồ sơ</h2>
            <form id="editProfileForm" class="space-y-4">
                <input type="hidden" name="user_id" value="<?php echo htmlspecialchars($user['user_id']); ?>">
                <div class="mb-2">
                    <label class="block text-sm font-medium text-gray-700">Họ và tên</label>
                    <input type="text" name="full_name" value="<?php echo htmlspecialchars($user['full_name']); ?>" class="w-full border border-gray-300 rounded-md p-2" required>
                </div>
                <div class="mb-2">
                    <label class="block text-sm font-medium text-gray-700">Username</label>
                    <input type="text" name="username" value="<?php echo htmlspecialchars($user['username']); ?>" class="w-full border border-gray-300 rounded-md p-2" required>
                </div>
                <div class="mb-2">
                    <label class="block text-sm font-medium text-gray-700">Email</label>
                    <input type="email" name="email" value="<?php echo htmlspecialchars($user['email'] ?? ''); ?>" class="w-full border border-gray-300 rounded-md p-2" required>
                </div>
                <div class="mb-2">
                    <label class="block text-sm font-medium text-gray-700">Ảnh đại diện</label>
                    <input type="file" id="profilePicture" accept="image/*" class="w-full">
                    <img id="profilePreview" src="<?php echo htmlspecialchars($user['profile_picture'] ?? '/assets/images/placeholder.jpg'); ?>" class="mt-2 max-w-xs h-[100px] object-cover rounded-md" alt="Preview">
                    <input type="hidden" name="profile_picture" id="selectedProfilePicture" value="<?php echo htmlspecialchars($user['profile_picture'] ?? ''); ?>">
                </div>
                <div class="flex justify-end">
                    <button type="button" onclick="closeEditProfileDialog()" class="px-4 py-2 mr-2 bg-gray-300 rounded-md">Hủy</button>
                    <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-md">Lưu</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
<script>
    // Mở dialog chỉnh sửa hồ sơ
    function openEditProfileDialog() {
        document.getElementById('editProfileDialog').classList.remove('hidden');
        document.getElementById('editProfileDialog').classList.add('flex');
    }

    // Đóng dialog chỉnh sửa hồ sơ
    function closeEditProfileDialog() {
        document.getElementById('editProfileDialog').classList.add('hidden');
        document.getElementById('editProfileDialog').classList.remove('flex');
    }

    // Xử lý upload ảnh profile
    document.getElementById('profilePicture').addEventListener('change', async function(event) {
        const file = event.target.files[0];
        if (file) {
            // const reader = new FileReader();

            // reader.onload = function(e) {
            //     const base64String = e.target.result;
            //     document.getElementById('profilePreview').src = base64String;
            //     document.getElementById('selectedProfilePicture').value = base64String;
            // };

            // reader.readAsDataURL(file);

            try {
                const base64String = await new Promise((resolve, reject) => {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        resolve(e.target.result);
                    };
                    reader.onerror = function(e) {
                        reject(new Error('Lỗi khi đọc file ảnh: ' + e.target.error));
                    };
                    reader.readAsDataURL(file);
                });

                console.log('Base64:', base64String);


                document.getElementById('profilePreview').src = base64String;
                document.getElementById('selectedProfilePicture').value = base64String;
            } catch (error) {
                console.log('Lỗi:', error);
                Toastify({
                    text: "Có lỗi xảy ra khi đọc file ảnh.",
                    duration: 3000,
                    gravity: "top",
                    position: "right",
                    backgroundColor: '#ef4444',
                    stopOnFocus: true
                }).showToast();

            }
        }
    });

    // Gửi form qua AJAX
    document.getElementById('editProfileForm').addEventListener('submit', async function(e) {
        e.preventDefault();
        const formData = new FormData();

        formData.append('user_id', document.querySelector('input[name="user_id"]').value);
        formData.append('full_name', document.querySelector('input[name="full_name"]').value);
        formData.append('username', document.querySelector('input[name="username"]').value);
        formData.append('email', document.querySelector('input[name="email"]').value);



        formData.append('profile_picture', document.getElementById('selectedProfilePicture').value);

        console.log('Form data:', formData);




        // formData.append('profile_picture', document.getElementById('selectedProfilePicture').value);

        await fetch('/api/profile/update', {
                method: 'POST',
                body: formData
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
                    closeEditProfileDialog();
                    // Cập nhật giao diện nếu cần (ví dụ: reload trang hoặc cập nhật avatar)
                    window.location.reload(); // Hoặc gọi API để lấy lại dữ liệu mới
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
                    text: "Có lỗi xảy ra khi cập nhật hồ sơ.",
                    duration: 3000,
                    gravity: "top",
                    position: "right",
                    backgroundColor: '#ef4444',
                    stopOnFocus: true
                }).showToast();
            });
    });

    // Xử lý ẩn toast sau vài giây
    document.addEventListener('DOMContentLoaded', function() {
        const toast = document.querySelector('.toast');
        if (toast) {
            setTimeout(() => toast.style.display = 'none', 3000);
        }
    });
</script>
</div>