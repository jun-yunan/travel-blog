<div class="w-[350px] h-[1500px] border border-black border-opacity-10 rounded-[8px] relative overflow-hidden flex flex-col items-center">
    <div class="h-[300px] p-[2rem] mt-[0.5rem]">
        <!-- Slider Container -->
    <div class="flex items-center space-x-2 mb-2 mt-[-0.75rem]">
    <img src="/assets/images/icon.jpg" alt="Icon" class="w-6 h-6">
    <h2 class="text-ls font-bold text-gray-900">Sự kiện nổi bật sắp diễn ra</h2>
    <img src="/assets/images/icon.jpg" alt="Icon" class="w-6 h-6 rotate-90">
    </div>
    <div class="relative w-[275px] h-[171px]">
        <img id="sliderImage" src="/assets/images/lehoiamnhac.jpg" alt="Slide 1" 
            class="w-full h-full object-cover transition-all duration-500 rounded-md">
    </div>

    <!-- Caption & Buttons -->
    <div class="flex items-center mt-[0.5rem] w-full justify-between px-[-5rem]">
        <!-- Nút Bấm Trái -->
        <img id="prevBtn" src="/assets/images/prev.png" alt="Previous"
            class="w-10 h-10 cursor-pointer hover:opacity-80 transition">

        <!-- Tiêu Đề + Ngày Tháng -->
    <div class="flex-1 flex flex-col items-center">
        <h1 id="sliderText" class="text-base font-semibold text-gray-800">Lễ hội âm nhạc</h1>
        <p id="sliderDate" class="text-xs text-gray-600 mt-1">27-02-2025 đến 03-03-2025</p>
    </div>
        <!-- Nút Bấm Phải -->
        <img id="nextBtn" src="/assets/images/next.png" alt="Next"
            class="w-10 h-10 cursor-pointer hover:opacity-80 transition">
    </div>
    </div>
    <!-- Ô tạo lịch trình -->
    <div class="w-[300px] h-[50px] flex items-center justify-center border border-black border-opacity-10 rounded-lg mt-1 cursor-pointer hover:bg-[#00A136] transition">
        <img src="/assets/images/calendar-icon2.png" alt="Calendar Icon" class="w-6 h-6 mr-2">
        <span class="text-gray-800 font-medium hover:text-white">Tạo lịch trình riêng của bạn</span>
    </div>
    <!-- Danh sách bạn bè -->
<div class="w-[300px] border border-black border-opacity-10 rounded-lg mt-4 p-4">
    <!-- Tiêu đề -->
    <div class="flex items-center space-x-2 mb-3">
        <img src="/assets/images/friends-icon.png" alt="Friends Icon" class="w-6 h-6">
        <h2 class="text-lg font-bold text-gray-900">Danh sách bạn bè</h2>
    </div>

    <!-- Danh sách có thể cuộn -->
    <div class="h-[200px] overflow-y-auto space-y-3 scrollbar-custom">
        <!-- Mỗi người bạn -->
        <div class="flex items-center space-x-3">
            <img src="/assets/images/anhkiet.png" alt="Avatar" class="w-10 h-10 rounded-full object-cover">
            <div>
                <p class="text-gray-900 font-semibold">Nguyễn Anh Kiệt</p>
                <p class="text-sm text-gray-600">+ 20 bài viết mới</p>
            </div>
        </div>

        <div class="flex items-center space-x-3">
            <img src="/assets/images/kieutrinh.png" alt="Avatar" class="w-10 h-10 rounded-full object-cover">
            <div>
                <p class="text-gray-900 font-semibold">Lâm Phan Kiều Trinh</p>
                <p class="text-sm text-gray-600">+ 5 bài viết mới</p>
            </div>
        </div>

        <div class="flex items-center space-x-3">
            <img src="/assets/images/trungnhan.png" alt="Avatar" class="w-10 h-10 rounded-full object-cover">
            <div>
                <p class="text-gray-900 font-semibold">Thái Trung Nhẫn</p>
                <p class="text-sm text-gray-600">+ 5 bài viết mới</p>
            </div>
        </div>

        <!-- Thêm nhiều bạn bè để test cuộn -->
        <div class="flex items-center space-x-3">
            <img src="/assets/images/thuyhuynh.png" alt="Avatar" class="w-10 h-10 rounded-full object-cover">
            <div>
                <p class="text-gray-900 font-semibold">Tăng Thị Thúy Huỳnh</p>
                <p class="text-sm text-gray-600">+ 3 bài viết mới</p>
            </div>
        </div>

        <div class="flex items-center space-x-3">
            <img src="/assets/images/tienphat.png" alt="Avatar" class="w-10 h-10 rounded-full object-cover">
            <div>
                <p class="text-gray-900 font-semibold">Trần Tiến Phát</p>
                <p class="text-sm text-gray-600">+ 4 bài viết mới</p>
            </div>
        </div>
        <div class="flex items-center space-x-3">
            <img src="/assets/images/kimxuyen.png" alt="Avatar" class="w-10 h-10 rounded-full object-cover">
            <div>
                <p class="text-gray-900 font-semibold">Lê Thị Kim Xuyến</p>
                <p class="text-sm text-gray-600">+ 1 bài viết mới</p>
            </div>
        </div>
    </div>
</div>
    <!-- Danh sách nhóm -->
    <div class="w-[300px] border border-black border-opacity-10 rounded-lg mt-4 p-4">
    <!-- Tiêu đề -->
    <div class="flex items-center gap-2 mb-3">
        <img src="assets/images/group.png" class="w-6 h-6">
        <p class="text-lg font-semibold">Nhóm của bạn</p>
    </div>

    <!-- Danh sách nhóm (có thể cuộn) -->
    <div class="max-h-[300px] overflow-y-auto scrollbar-thin scrollbar-custom">
        <!-- Nhóm 1 -->
        <div class="flex items-center gap-3 p-3 rounded-lg">
            <img src="assets/images/anhnhom2.jpg" class="w-10 h-10 rounded-full object-cover">
            <div>
                <p class="font-semibold text-black">Anh em miền Bắc</p>
                <p class="text-gray-400 text-sm">+ 200 thông báo mới</p>
            </div>
        </div>

        <!-- Nhóm 2 -->
        <div class="flex items-center gap-3 p-3  rounded-lg">
            <img src="assets/images/anhnhom2.jpg" class="w-10 h-10 rounded-full object-cover">
            <div>
                <p class="font-semibold text-black">Phượt 4B</p>
                <p class="text-gray-400 text-sm">+ 38 thông báo mới</p>
            </div>
        </div>

        <!-- Nhóm 3 -->
        <div class="flex items-center gap-3 p-3  rounded-lg">
            <img src="assets/images/anhnhom2.jpg" class="w-10 h-10 rounded-full object-cover">
            <div>
                <p class="font-semibold text-black">Đồng đội thân thiết</p>
                <p class="text-gray-400 text-sm">+ 15 thông báo mới</p>
            </div>
        </div>

        <!-- Nhóm 4 -->
        <div class="flex items-center gap-3 p-3  rounded-lg">
            <img src="assets/images/anhnhom2.jpg" class="w-10 h-10 rounded-full object-cover">
            <div>
                <p class="font-semibold text-black">Hội du lịch bụi</p>
                <p class="text-gray-400 text-sm">+ 89 thông báo mới</p>
            </div>
        </div>

        <!-- Nhóm 5 -->
        <div class="flex items-center gap-3 p-3  rounded-lg">
            <img src="assets/images/anhnhom2.jpg" class="w-10 h-10 rounded-full object-cover">
            <div>
                <p class="font-semibold text-black">Lữ hành khám phá</p>
                <p class="text-gray-400 text-sm">+ 23 thông báo mới</p>
            </div>
        </div>

        <!-- Nhóm 6 -->
        <div class="flex items-center gap-3 p-3  rounded-lg">
            <img src="assets/images/anhnhom2.jpg" class="w-10 h-10 rounded-full object-cover">
            <div>
                <p class="font-semibold text-black">Team trekking</p>
                <p class="text-gray-400 text-sm">+ 12 thông báo mới</p>
            </div>
        </div>

        <!-- Nhóm 7 -->
        <div class="flex items-center gap-3 p-3  rounded-lg">
            <img src="assets/images/anhnhom2.jpg" class="w-10 h-10 rounded-full object-cover">
            <div>
                <p class="font-semibold text-black">Đội săn ảnh</p>
                <p class="text-gray-400 text-sm">+ 47 thông báo mới</p>
            </div>
        </div>

        <!-- Nhóm 8 -->
        <div class="flex items-center gap-3 p-3  rounded-lg">
            <img src="assets/images/anhnhom2.jpg" class="w-10 h-10 rounded-full object-cover">
            <div>
                <p class="font-semibold text-black">Hội bạn đại học</p>
                <p class="text-gray-400 text-sm">+ 9 thông báo mới</p>
            </div>
        </div>

        <!-- Nhóm 9 -->
        <div class="flex items-center gap-3 p-3 rounded-lg">
            <img src="assets/images/anhnhom2.jpg" class="w-10 h-10 rounded-full object-cover">
            <div>
                <p class="font-semibold text-black">Nhóm leo núi</p>
                <p class="text-gray-400 text-sm">+ 33 thông báo mới</p>
            </div>
        </div>

        <!-- Nhóm 10 -->
        <div class="flex items-center gap-3 p-3 rounded-lg">
            <img src="assets/images/anhnhom2.jpg" class="w-10 h-10 rounded-full object-cover">
            <div>
                <p class="font-semibold text-black">Team phượt xe máy</p>
                <p class="text-gray-400 text-sm">+ 78 thông báo mới</p>
            </div>
        </div>
    </div>
</div>



</div>

<script>
    const slides = [
        { img: "/assets/images/lehoiamnhac.jpg", text: "Lễ hội âm nhạc", date: "27-02-2025 đến 03-03-2025" },
        { img: "/assets/images/tuanlethoitrang.webp", text: "Tuần lễ thời trang", date: "10-04-2025 đến 15-04-2025" },
        { img: "/assets/images/banhdangiang.jpg", text: "Bánh dân giang nam bộ", date: "01-06-2025 đến 05-06-2025"  }
    ];

    let currentIndex = 0;
    const imageElement = document.getElementById("sliderImage");
    const textElement = document.getElementById("sliderText");
    const prevButton = document.getElementById("prevBtn");
    const nextButton = document.getElementById("nextBtn");

    function updateSlider(index) {
    imageElement.src = slides[index].img;
    textElement.textContent = slides[index].text;
    document.getElementById("sliderDate").textContent = slides[index].date;
}

    prevButton.addEventListener("click", () => {
        currentIndex = (currentIndex - 1 + slides.length) % slides.length;
        updateSlider(currentIndex);
    });

    nextButton.addEventListener("click", () => {
        currentIndex = (currentIndex + 1) % slides.length;
        updateSlider(currentIndex);
    });

    setInterval(() => {
        currentIndex = (currentIndex + 1) % slides.length;
        updateSlider(currentIndex);
    }, 3000);
</script>

<!-- CSS bổ sung cho thanh cuộn -->
<style>
    /* Tùy chỉnh thanh cuộn */
.scrollbar-custom::-webkit-scrollbar {
    width: 6px; /* Độ rộng thanh cuộn */
}

.scrollbar-custom::-webkit-scrollbar-thumb {
    background-color: rgba(0, 0, 0, 0.2); /* Màu thanh cuộn mờ */
    border-radius: 10px; /* Bo góc */
}

.scrollbar-custom::-webkit-scrollbar-thumb:hover {
    background-color: rgba(0, 0, 0, 0.4); /* Hover sáng hơn một chút */
}

.scrollbar-custom::-webkit-scrollbar-track {
    background: transparent; /* Ẩn phần nền track */
}

</style>
