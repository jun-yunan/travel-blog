<div class="w-[350px] h-[1500px] bg-white p-4 border border-black border-opacity-10 rounded-lg overflow-hidden relative flex flex-col space-y-4 shadow-md max-h-[2000px] overflow-y-auto scrollbar-custom">
    <!-- Phần slider sự kiện -->
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
        <div class="flex items-center mt-[0.5rem] w-full justify-between px-[-5rem]">
            <img id="prevBtn" src="/assets/images/prev.png" alt="Previous"
                class="w-10 h-10 cursor-pointer hover:opacity-80 transition">
            <div class="flex-1 flex flex-col items-center">
                <h1 id="sliderText" class="text-base font-semibold text-gray-800">Lễ hội âm nhạc</h1>
                <p id="sliderDate" class="text-xs text-gray-600 mt-1">27-02-2025 đến 03-03-2025</p>
            </div>
            <img id="nextBtn" src="/assets/images/next.png" alt="Next"
                class="w-10 h-10 cursor-pointer hover:opacity-80 transition">
        </div>
    </div>

    <!-- Ô tạo lịch trình -->
    <div class="w-[300px] h-[50px] flex items-center justify-center border border-black border-opacity-10 rounded-lg cursor-pointer hover:bg-[#00A136] transition">
        <img src="/assets/images/calendar-icon2.png" alt="Calendar Icon" class="w-6 h-6 mr-2">
        <span class="text-gray-800 font-medium hover:text-white">Tạo lịch trình riêng của bạn</span>
    </div>

    <!-- Danh sách bạn bè -->
    <div class="w-[300px] h-[300px] border border-black border-opacity-10 rounded-lg p-4">
        <div class="flex items-center space-x-2 mb-3">
            <img src="/assets/images/friends-icon.png" alt="Friends Icon" class="w-6 h-6">
            <h2 class="text-lg font-bold text-gray-900">Danh sách bạn bè</h2>
        </div>
        <div class="h-[230px] overflow-y-auto space-y-3 scrollbar-custom">
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
    <div class="w-[300px] h-[300px] border border-black border-opacity-10 rounded-lg p-4">
        <div class="flex items-center gap-2 mb-3">
            <img src="assets/images/group.png" class="w-6 h-6">
            <p class="text-lg font-semibold">Nhóm của bạn</p>
        </div>
        <div class="h-[230px] overflow-y-auto scrollbar-custom">
            <div class="flex items-center gap-3 p-3 rounded-lg">
                <img src="assets/images/anhnhom2.jpg" class="w-10 h-10 rounded-full object-cover">
                <div>
                    <p class="font-semibold text-black">Anh em miền Bắc</p>
                    <p class="text-gray-400 text-sm">+ 200 thông báo mới</p>
                </div>
            </div>
            <div class="flex items-center gap-3 p-3 rounded-lg">
                <img src="assets/images/anhnhom2.jpg" class="w-10 h-10 rounded-full object-cover">
                <div>
                    <p class="font-semibold text-black">Phượt 4B</p>
                    <p class="text-gray-400 text-sm">+ 38 thông báo mới</p>
                </div>
            </div>
            <div class="flex items-center gap-3 p-3 rounded-lg">
                <img src="assets/images/anhnhom2.jpg" class="w-10 h-10 rounded-full object-cover">
                <div>
                    <p class="font-semibold text-black">Đồng đội thân thiết</p>
                    <p class="text-gray-400 text-sm">+ 15 thông báo mới</p>
                </div>
            </div>
            <div class="flex items-center gap-3 p-3 rounded-lg">
                <img src="assets/images/anhnhom2.jpg" class="w-10 h-10 rounded-full object-cover">
                <div>
                    <p class="font-semibold text-black">Hội du lịch bụi</p>
                    <p class="text-gray-400 text-sm">+ 89 thông báo mới</p>
                </div>
            </div>
            <div class="flex items-center gap-3 p-3 rounded-lg">
                <img src="assets/images/anhnhom2.jpg" class="w-10 h-10 rounded-full object-cover">
                <div>
                    <p class="font-semibold text-black">Lữ hành khám phá</p>
                    <p class="text-gray-400 text-sm">+ 23 thông báo mới</p>
                </div>
            </div>
            <div class="flex items-center gap-3 p-3 rounded-lg">
                <img src="assets/images/anhnhom2.jpg" class="w-10 h-10 rounded-full object-cover">
                <div>
                    <p class="font-semibold text-black">Team trekking</p>
                    <p class="text-gray-400 text-sm">+ 12 thông báo mới</p>
                </div>
            </div>
            <div class="flex items-center gap-3 p-3 rounded-lg">
                <img src="assets/images/anhnhom2.jpg" class="w-10 h-10 rounded-full object-cover">
                <div>
                    <p class="font-semibold text-black">Đội săn ảnh</p>
                    <p class="text-gray-400 text-sm">+ 47 thông báo mới</p>
                </div>
            </div>
            <div class="flex items-center gap-3 p-3 rounded-lg">
                <img src="assets/images/anhnhom2.jpg" class="w-10 h-10 rounded-full object-cover">
                <div>
                    <p class="font-semibold text-black">Hội bạn đại học</p>
                    <p class="text-gray-400 text-sm">+ 9 thông báo mới</p>
                </div>
            </div>
            <div class="flex items-center gap-3 p-3 rounded-lg">
                <img src="assets/images/anhnhom2.jpg" class="w-10 h-10 rounded-full object-cover">
                <div>
                    <p class="font-semibold text-black">Nhóm leo núi</p>
                    <p class="text-gray-400 text-sm">+ 33 thông báo mới</p>
                </div>
            </div>
            <div class="flex items-center gap-3 p-3 rounded-lg">
                <img src="assets/images/anhnhom2.jpg" class="w-10 h-10 rounded-full object-cover">
                <div>
                    <p class="font-semibold text-black">Team phượt xe máy</p>
                    <p class="text-gray-400 text-sm">+ 78 thông báo mới</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Câu chuyện du lịch -->
    <div class="w-[300px] h-[300px] border border-black border-opacity-10 rounded-lg p-4">
        <div class="flex items-center gap-2 mb-3">
            <p class="text-lg font-semibold">Câu chuyện du lịch</p>
        </div>
        <div class="h-[230px] overflow-y-auto scrollbar-custom space-y-4">
            <div class="flex items-start gap-3">
                <img src="assets/images/travel1.jpg" class="w-12 h-12 rounded-md object-cover">
                <div>
                    <p class="font-semibold text-black">Hành trình lên Fansipan</p>
                    <p class="text-gray-600 text-sm line-clamp-2">Chinh phục nóc nhà Đông Dương với sương mù và cảnh đẹp tuyệt vời trong 3 ngày 2 đêm.</p>
                    <p class="text-gray-400 text-xs mt-1">bởi Nguyễn Anh Kiệt - 28/02/2025</p>
                </div>
            </div>
            <div class="flex items-start gap-3">
                <img src="assets/images/travel2.jpg" class="w-12 h-12 rounded-md object-cover">
                <div>
                    <p class="font-semibold text-black">Phượt Đà Lạt bằng xe máy</p>
                    <p class="text-gray-600 text-sm line-clamp-2">Khám phá thành phố ngàn hoa qua những cung đường uốn lượn và khí hậu mát mẻ.</p>
                    <p class="text-gray-400 text-xs mt-1">bởi Lâm Phan Kiều Trinh - 25/02/2025</p>
                </div>
            </div>
            <div class="flex items-start gap-3">
                <img src="assets/images/travel3.jpg" class="w-12 h-12 rounded-md object-cover">
                <div>
                    <p class="font-semibold text-black">Cắm trại bên hồ Tà Đùng</p>
                    <p class="text-gray-600 text-sm line-clamp-2">Trải nghiệm đêm ngủ giữa thiên nhiên với view hồ nước lung linh như vịnh Hạ Long.</p>
                    <p class="text-gray-400 text-xs mt-1">bởi Thái Trung Nhẫn - 20/02/2025</p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    const slides = [
        { img: "/assets/images/lehoiamnhac.jpg", text: "Lễ hội âm nhạc", date: "27-02-2025 đến 03-03-2025" },
        { img: "/assets/images/tuanlethoitrang.webp", text: "Tuần lễ thời trang", date: "10-04-2025 đến 15-04-2025" },
        { img: "/assets/images/banhdangiang.jpg", text: "Bánh dân giang nam bộ", date: "01-06-2025 đến 05-06-2025" }
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
        width: 6px;
    }

    .scrollbar-custom::-webkit-scrollbar-thumb {
        background-color: rgba(0, 0, 0, 0.2);
        border-radius: 10px;
    }

    .scrollbar-custom::-webkit-scrollbar-thumb:hover {
        background-color: rgba(0, 0, 0, 0.4);
    }

    .scrollbar-custom::-webkit-scrollbar-track {
        background: transparent;
    }
</style>