<div class="w-full max-w-4xl min-h-[1000px] mx-auto p-6 bg-white rounded-lg shadow-md mt-8">
    <h1 class="text-3xl font-bold text-green-500 mb-6">Lịch trình của tôi</h1>

    <?php
    $schedules = $data['schedules'];
    ?>

    <!-- Nút tạo lịch trình mới -->
    <button
        class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 mb-4"
        onclick="openScheduleDialog()">
        Tạo lịch trình mới
    </button>

    <!-- Danh sách lịch trình -->
    <div class="space-y-6">
        <?php if (empty($schedules)): ?>
            <p class="text-gray-500">Bạn chưa có lịch trình nào.</p>
        <?php else: ?>
            <?php foreach ($schedules as $schedule): ?>
                <div id="schedule_<?php echo $schedule['schedule_id']; ?>" class="border border-gray-200 p-4 rounded-lg shadow-sm">
                    <h3 class="text-xl font-semibold text-gray-800">
                        <?php echo htmlspecialchars($schedule['title']); ?>
                    </h3>
                    <p class="text-gray-600 mt-2"><?php echo htmlspecialchars($schedule['description'] ?? 'Không có mô tả'); ?></p>
                    <div class="mt-2 text-gray-500">
                        <p>Ngày bắt đầu: <?php echo (new DateTime($schedule['start_date']))->format('d/m/Y H:i'); ?></p>
                        <p>Ngày kết thúc: <?php echo (new DateTime($schedule['end_date']))->format('d/m/Y H:i'); ?></p>
                        <p>Địa điểm: <?php echo htmlspecialchars($schedule['location_name'] ?? 'Chưa xác định'); ?></p>
                        <p>Trạng thái: <?php echo ucfirst($schedule['status']); ?></p>
                    </div>
                    <div class="mt-4 flex gap-2">
                        <button
                            class="px-3 py-1 bg-blue-500 text-white rounded-md hover:bg-blue-600 text-sm"
                            onclick="openEditScheduleDialog(<?php echo $schedule['schedule_id']; ?>)">
                            Sửa
                        </button>
                        <button
                            class="px-3 py-1 bg-red-500 text-white rounded-md hover:bg-red-600 text-sm"
                            onclick="deleteSchedule(<?php echo $schedule['schedule_id']; ?>)">
                            Xóa
                        </button>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

    <!-- Phân trang (nếu cần) -->
    <?php if (!empty($schedules) && count($schedules) >= 10): ?>
        <div class="mt-6 text-center">
            <a href="/schedule?page=2" class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700">Xem thêm</a>
        </div>
    <?php endif; ?>

    <!-- Dialog tạo/sửa lịch trình -->
    <div id="scheduleDialog" class="fixed inset-0 z-50 bg-gray-800 bg-opacity-50 hidden flex items-center justify-center" onclick="closeScheduleDialog()">
        <div class="bg-white p-6 rounded-lg w-[90%] max-w-md" onclick="event.stopPropagation()">
            <h2 id="dialogTitle" class="text-xl font-bold mb-4">Tạo lịch trình mới</h2>
            <form id="scheduleForm" class="space-y-4">
                <input type="hidden" name="user_id" value="<?php echo htmlspecialchars($_SESSION['user']['user_id']); ?>">
                <input type="hidden" name="schedule_id" id="scheduleId" value="">
                <div class="mb-2">
                    <label class="block text-sm font-medium text-gray-700">Tiêu đề</label>
                    <input type="text" name="title" id="scheduleTitle" class="w-full border border-gray-300 rounded-md p-2" required>
                </div>
                <div class="mb-2">
                    <label class="block text-sm font-medium text-gray-700">Ngày bắt đầu</label>
                    <input type="datetime-local" name="start_date" id="startDate" class="w-full border border-gray-300 rounded-md p-2" required>
                </div>
                <div class="mb-2">
                    <label class="block text-sm font-medium text-gray-700">Ngày kết thúc</label>
                    <input type="datetime-local" name="end_date" id="endDate" class="w-full border border-gray-300 rounded-md p-2" required>
                </div>
                <div class="mb-2">
                    <label class="block text-sm font-medium text-gray-700">Địa điểm</label>
                    <input type="number" name="location_id" id="locationId" class="w-full border border-gray-300 rounded-md p-2">
                </div>
                <div class="mb-2">
                    <label class="block text-sm font-medium text-gray-700">Mô tả</label>
                    <textarea name="description" id="description" class="w-full border border-gray-300 rounded-md p-2" rows="3"></textarea>
                </div>
                <div class="mb-2">
                    <label class="block text-sm font-medium text-gray-700">Trạng thái</label>
                    <select name="status" id="status" class="w-full border border-gray-300 rounded-md p-2">
                        <option value="pending">Chờ xử lý</option>
                        <option value="ongoing">Đang thực hiện</option>
                        <option value="completed">Hoàn thành</option>
                        <option value="cancelled">Đã hủy</option>
                    </select>
                </div>
                <div class="flex justify-end">
                    <button type="button" onclick="closeScheduleDialog()" class="px-4 py-2 mr-2 bg-gray-300 rounded-md">Hủy</button>
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
        // Mở dialog tạo/sửa lịch trình
        function openScheduleDialog(scheduleId = null) {
            document.getElementById('scheduleDialog').classList.remove('hidden');
            document.getElementById('scheduleDialog').classList.add('flex');

            const form = document.getElementById('scheduleForm');
            const title = document.getElementById('dialogTitle');

            if (scheduleId) {
                title.textContent = 'Chỉnh sửa lịch trình';
                document.getElementById('scheduleId').value = scheduleId;

                // Gọi API để lấy thông tin lịch trình
                fetch(`/api/schedules/${scheduleId}`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.status === 'success') {
                            document.getElementById('scheduleTitle').value = data.data.title;
                            document.getElementById('startDate').value = data.data.start_date.split('.')[0].replace(' ', 'T'); // Định dạng datetime-local
                            document.getElementById('endDate').value = data.data.end_date.split('.')[0].replace(' ', 'T');
                            document.getElementById('locationId').value = data.data.location_id || '';
                            document.getElementById('description').value = data.data.description || '';
                            document.getElementById('status').value = data.data.status;
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
                            text: "Không thể tải thông tin lịch trình.",
                            duration: 3000,
                            gravity: "top",
                            position: "right",
                            backgroundColor: '#ef4444',
                            stopOnFocus: true
                        }).showToast();
                    });
            } else {
                title.textContent = 'Tạo lịch trình mới';
                form.reset();
                document.getElementById('scheduleId').value = '';
            }
        }

        // Đóng dialog tạo/sửa lịch trình
        function closeScheduleDialog() {
            document.getElementById('scheduleDialog').classList.add('hidden');
            document.getElementById('scheduleDialog').classList.remove('flex');
        }

        // Xử lý submit form lịch trình qua AJAX
        document.getElementById('scheduleForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const scheduleId = document.getElementById('scheduleId').value;
            const userId = document.getElementById('user_id').value;
            const title = document.getElementById('scheduleTitle').value;
            const startDate = document.getElementById('startDate').value;
            const endDate = document.getElementById('endDate').value;
            const locationId = document.getElementById('locationId').value;
            const description = document.getElementById('description').value;
            const status = document.getElementById('status').value;

            const url = scheduleId ? `/api/schedules/update` : `/api/schedules/create`;
            const method = scheduleId ? 'PUT' : 'POST';

            fetch(url, {
                    method: method,
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-Token': '<?php echo isset($_SESSION['csrf_token']) ? $_SESSION['csrf_token'] : ''; ?>'
                    },
                    body: JSON.stringify({
                        schedule_id: scheduleId || null,
                        user_id: userId,
                        title: title,
                        start_date: startDate,
                        end_date: endDate,
                        location_id: locationId ? parseInt(locationId) : null,
                        description: description,
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
                        closeScheduleDialog();
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
                        text: "Có lỗi xảy ra khi xử lý lịch trình.",
                        duration: 3000,
                        gravity: "top",
                        position: "right",
                        backgroundColor: '#ef4444',
                        stopOnFocus: true
                    }).showToast();
                });
        });

        // Xóa lịch trình qua AJAX
        function deleteSchedule(scheduleId) {
            const userId = '<?php echo htmlspecialchars($_SESSION['user']['user_id']); ?>';

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

            if (!confirm('Bạn có chắc chắn muốn xóa lịch trình này?')) {
                return;
            }

            fetch('/api/schedules/delete', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-Token': '<?php echo isset($_SESSION['csrf_token']) ? $_SESSION['csrf_token'] : ''; ?>'
                    },
                    body: JSON.stringify({
                        schedule_id: scheduleId,
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
                        // Xóa lịch trình khỏi giao diện
                        const scheduleElement = document.querySelector(`#schedule_${scheduleId}`).closest('.border');
                        if (scheduleElement) {
                            scheduleElement.remove();
                        }
                    } else {
                        Toastify({
                            text: data.message || "Lỗi khi xóa lịch trình.",
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
                        text: "Có lỗi xảy ra khi xóa lịch trình.",
                        duration: 3000,
                        gravity: "top",
                        position: "right",
                        backgroundColor: '#ef4444',
                        stopOnFocus: true
                    }).showToast();
                });
        }

        // Mở dialog chỉnh sửa lịch trình
        function openEditScheduleDialog(scheduleId) {
            openScheduleDialog(scheduleId);
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