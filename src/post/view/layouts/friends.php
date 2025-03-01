<div id="friends"
    class="w-[350px] bg-white p-4 border border-black border-opacity-10 rounded-lg overflow-hidden relative flex flex-col space-y-4 fixed transition-transform duration-300 translate-x-0 right-0 shadow-md max-h-[2000px] overflow-y-auto scrollbar-custom">
    <!-- Slogan động -->
    <div
        class="h-[50px] flex items-center justify-center border border-black border-opacity-10 rounded-lg mx-0 my-0 overflow-hidden">
        <p id="slogan" class="text-base font-semibold text-gray-800 whitespace-nowrap animate-marquee glow-text"></p>
    </div>

    <!-- Menu -->
    <div class="grid grid-cols-3 gap-2 w-full">
        <button data-content="guide"
            class="menu-btn flex flex-col items-center justify-center bg-gray-50 w-full h-[100px] rounded-lg text-sm font-semibold hover:bg-[#00A136] hover:scale-105 hover:text-white transition-all border border-black border-opacity-10 p-3">
            <i class="bi bi-book text-xl text-blue-500"></i>
            <span class="mt-1 whitespace-nowrap">Hướng dẫn</span>
        </button>
        <button data-content="experience"
            class="menu-btn flex flex-col items-center justify-center bg-gray-50 w-full h-[100px] rounded-lg text-sm font-semibold hover:bg-[#00A136] hover:scale-105 hover:text-white transition-all border border-black border-opacity-10 p-3">
            <i class="bi bi-lightbulb text-xl text-yellow-500"></i>
            <span class="mt-1 whitespace-nowrap">Kinh nghiệm</span>
        </button>
        <button data-content="contact"
            class="menu-btn flex flex-col items-center justify-center bg-gray-50 w-full h-[100px] rounded-lg text-sm font-semibold hover:bg-[#00A136] hover:scale-105 hover:text-white transition-all border border-black border-opacity-10 p-3">
            <i class="bi bi-envelope text-xl text-green-500"></i>
            <span class="mt-1 whitespace-nowrap">Liên hệ</span>
        </button>
    </div>

    <!-- Nội dung động -->
    <div id="dynamic-content" class="hidden bg-white p-4 rounded-lg border border-black border-opacity-10 shadow-md">
        <h2 id="content-title" class="text-base font-semibold text-gray-800 mb-2"></h2>
        <p id="content-text" class="text-sm text-gray-600"></p>
        <button id="hide-content" class="mt-3 text-sm text-gray-500 hover:text-gray-700 underline">Ẩn</button>
    </div>

    <!-- Top người đóng góp -->
    <div id="top-contributors" class="bg-white p-4 rounded-lg border border-black border-opacity-10 shadow-md relative">
        <div class="flex items-center gap-2 mb-3">
            <h2 class="text-base font-bold text-gray-800">🏆 Top BXH đóng góp</h2>
        </div>
        <div id="top-users"
            class="flex flex-col space-y-2 text-sm text-gray-800 max-h-[200px] overflow-y-auto scrollbar-custom"></div>
        <button id="toggle-top-users"
            class="text-gray-500 text-xs px-3 py-1 mt-3 rounded-lg bg-gray-100 hover:bg-gray-200 transition">Xem thêm</button>
        <div id="user-detail-modal"
            class="absolute bg-white p-4 rounded-lg shadow-lg w-[300px] border border-black border-opacity-10 hidden z-10">
            <div class="flex items-center mb-3">
                <img id="user-detail-avatar" src="" class="w-10 h-10 rounded-full mr-3">
                <h2 id="user-detail-name" class="text-base font-semibold text-gray-800"></h2>
            </div>
            <p id="user-detail-gender" class="text-sm text-gray-600 mb-2"></p>
            <p id="user-detail-notes" class="text-sm text-gray-600 mb-3"></p>
            <div id="user-detail-tags" class="flex flex-wrap gap-2"></div>
            <button id="close-modal" class="mt-3 text-sm text-gray-500 hover:text-gray-700 underline">Đóng</button>
        </div>
    </div>

    <!-- Địa điểm nổi bật -->
    <div id="hot-place-banner" class="bg-white p-4 rounded-lg border border-black border-opacity-10">
        <div class="flex items-center gap-2 mb-3">
            <h2 class="text-base font-bold text-gray-800">🌟 Địa điểm nổi bật </h2>
        </div>
        <div class="relative">
            <a id="hot-place-link" href="" target="_blank">
                <img id="hot-place-image" src=""
                    class="w-full h-[180px] object-cover rounded-lg transition-opacity duration-500 border border-black border-opacity-10">
            </a>
            <button id="prev-place"
                class="absolute left-[-36px] top-1/2 transform -translate-y-1/2 bg-white border border-black border-opacity-10 rounded-full p-2 text-gray-600 text-xl hover:text-gray-800 hover:bg-gray-100 transition shadow-md">
                <i class="bi bi-caret-left"></i>
            </button>
            <button id="next-place"
                class="absolute right-[-36px] top-1/2 transform -translate-y-1/2 bg-white border border-black border-opacity-10 rounded-full p-2 text-gray-600 text-xl hover:text-gray-800 hover:bg-gray-100 transition shadow-md">
                <i class="bi bi-caret-right"></i>
            </button>
        </div>
        <div class="text-center mt-3">
            <h3 id="hot-place-name" class="text-base font-semibold text-gray-800"></h3>
            <p id="hot-place-views" class="text-sm text-gray-600 mt-1"></p>
        </div>
        <div class="flex justify-center mt-3 space-x-2" id="banner-indicators"></div>
    </div>

    <!-- Gợi ý lịch trình -->
    <div id="travel-itinerary" class="bg-white p-4 rounded-lg border border-black border-opacity-10 shadow-md">
        <div class="flex items-center gap-2 mb-3">
            <h2 class="text-base font-bold text-gray-800">📅 Gợi ý lịch trình du lịch</h2>
        </div>
        <div id="itinerary-list" class="flex flex-col space-y-3 max-h-[200px] overflow-y-hidden transition-all duration-300">
            <div class="itinerary-item p-3 bg-gradient-to-r from-gray-50 to-gray-100 rounded-lg cursor-pointer hover:shadow-lg transition-all"
                data-itinerary="phu-quoc">
                <h3 class="text-sm font-semibold text-gray-800">3 ngày 2 đêm tại Phú Quốc</h3>
                <p class="text-xs text-gray-600">Khám phá biển xanh, cát trắng và các điểm check-in nổi tiếng.</p>
                <p class="text-xs text-gray-500 mt-1">Chi phí: ~3.000.000 VNĐ</p>
            </div>
            <div class="itinerary-item p-3 bg-gradient-to-r from-gray-50 to-gray-100 rounded-lg cursor-pointer hover:shadow-lg transition-all"
                data-itinerary="ha-giang">
                <h3 class="text-sm font-semibold text-gray-800">5 ngày 4 đêm tại Hà Giang</h3>
                <p class="text-xs text-gray-600">Trải nghiệm cung đường đèo, hoa tam giác mạch và văn hóa địa phương.</p>
                <p class="text-xs text-gray-500 mt-1">Chi phí: ~4.500.000 VNĐ</p>
            </div>
            <div class="itinerary-item p-3 bg-gradient-to-r from-gray-50 to-gray-100 rounded-lg cursor-pointer hover:shadow-lg transition-all"
                data-itinerary="da-lat">
                <h3 class="text-sm font-semibold text-gray-800">3 ngày 2 đêm tại Đà Lạt</h3>
                <p class="text-xs text-gray-600">Tham quan hồ Xuân Hương, thung lũng Tình Yêu và đồi chè.</p>
                <p class="text-xs text-gray-500 mt-1">Chi phí: ~2.500.000 VNĐ</p>
            </div>
            <div class="itinerary-item p-3 bg-gradient-to-r from-gray-50 to-gray-100 rounded-lg cursor-pointer hover:shadow-lg transition-all"
                data-itinerary="phan-thiet">
                <h3 class="text-sm font-semibold text-gray-800">2 ngày 1 đêm tại Phan Thiết</h3>
                <p class="text-xs text-gray-600">Khám phá đồi cát bay, biển Mũi Né và tháp Chăm Poshanu.</p>
                <p class="text-xs text-gray-500 mt-1">Chi phí: ~1.800.000 VNĐ</p>
            </div>
            <div class="itinerary-item p-3 bg-gradient-to-r from-gray-50 to-gray-100 rounded-lg cursor-pointer hover:shadow-lg transition-all"
                data-itinerary="ha-noi">
                <h3 class="text-sm font-semibold text-gray-800">2 ngày 1 đêm tại Hà Nội</h3>
                <p class="text-xs text-gray-600">Tham quan Hồ Gươm, Văn Miếu và thưởng thức ẩm thực đường phố.</p>
                <p class="text-xs text-gray-500 mt-1">Chi phí: ~1.500.000 VNĐ</p>
            </div>
        </div>
        <button id="toggle-itinerary" class="mt-3 text-sm text-gray-500 hover:text-gray-700 underline">Xem thêm</button>
    </div>

    <!-- Modal chi tiết lịch trình -->
    <div id="itinerary-modal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
        <div class="bg-white p-6 rounded-lg border border-black border-opacity-10 shadow-lg w-[400px] max-w-[90%]">
            <h3 id="modal-title" class="text-lg font-semibold text-gray-800 mb-4"></h3>
            <div class="space-y-4">
                <div>
                    <label class="text-sm font-medium text-gray-700">Địa điểm:</label>
                    <p id="modal-location" class="text-sm text-gray-600"></p>
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-700">Ăn uống:</label>
                    <p id="modal-food" class="text-sm text-gray-600"></p>
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-700">Hoạt động:</label>
                    <p id="modal-activity" class="text-sm text-gray-600"></p>
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-700">Chi phí ước tính:</label>
                    <p id="modal-cost" class="text-sm text-gray-600"></p>
                </div>
            </div>
            <button id="close-modal-itinerary"
                class="mt-4 text-sm text-gray-500 hover:text-gray-700 underline">Đóng</button>
        </div>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        // Slogan động
        const slogans = [
            "🎒 Xách balo lên và đi 🚀",
            "🌈 Khám phá thế giới qua từng chuyến đi 🌍",
            "🛣️ Cùng nhau khám phá thế giới ✨",
            "📖 Mỗi chuyến đi là một câu chuyện mới 🌍",
            "🌟 Hãy đi khi còn trẻ! ✈️",
            "🔥 Tuổi trẻ là những chuyến đi đầy đam mê ❤️",
            "🌏 Khám phá những miền đất mới 🚀"
        ];

        const sloganEl = document.getElementById('slogan');
        function updateSlogan() {
            if (sloganEl) {
                sloganEl.innerText = slogans[Math.floor(Math.random() * slogans.length)];
            }
        }
        updateSlogan();
        setInterval(updateSlogan, 10000);

        // Nội dung động
        const contentData = {
            guide: { title: "Hướng dẫn", text: "Đây là nội dung hướng dẫn chi tiết dành cho bạn." },
            experience: { title: "Kinh nghiệm", text: "Những kinh nghiệm hữu ích khi đi du lịch bạn nên biết!" },
            contact: { title: "Liên hệ", text: "Bạn có thể liên hệ với chúng tôi qua email hoặc số điện thoại." }
        };

        document.querySelectorAll('.menu-btn').forEach(btn => {
            btn.addEventListener('click', () => {
                const contentType = btn.getAttribute('data-content');
                const contentBox = document.getElementById('dynamic-content');
                if (contentBox) {
                    document.getElementById('content-title').innerText = contentData[contentType].title;
                    document.getElementById('content-text').innerText = contentData[contentType].text;
                    contentBox.classList.remove('hidden');
                }
            });
        });

        document.getElementById('hide-content')?.addEventListener('click', () => {
            document.getElementById('dynamic-content')?.classList.add('hidden');
        });

        // Top người đóng góp
        const topUsers = [
            { name: "Nguyễn Nhựt Trường", posts: 120, avatar: "https://i.pravatar.cc/40?img=1", medal: "👑", gender: "Nam", notes: "Yêu thích du lịch núi rừng", tags: ["Du lịch", "Nhiếp ảnh", "Hà Giang"] },
            { name: "Nguyễn Anh Kiệt", posts: 98, avatar: "https://i.pravatar.cc/40?img=2", medal: "🥈", gender: "Nam", notes: "Thích khám phá biển đảo", tags: ["Biển", "Phú Quốc", "Lặn biển"] },
            { name: "Lâm Phan Kiều Trinh", posts: 85, avatar: "https://i.pravatar.cc/40?img=3", medal: "🥉", gender: "Nữ", notes: "Đam mê văn hóa cổ", tags: ["Hội An", "Lịch sử", "Ẩm thực"] },
            { name: "Tăng Thị Thúy Huỳnh", posts: 72, avatar: "https://i.pravatar.cc/40?img=4", medal: "", gender: "Nữ", notes: "Thích phiêu lưu mạo hiểm", tags: ["Leo núi", "Hà Giang", "Camping"] },
            { name: "Lê Thị Kim Xuyến", posts: 65, avatar: "https://i.pravatar.cc/40?img=5", medal: "", gender: "Nữ", notes: "Yêu thích thiên nhiên", tags: ["Rừng", "Đà Lạt", "Check-in"] },
            { name: "Trần Tiến Phát", posts: 40, avatar: "https://i.pravatar.cc/40?img=6", medal: "", gender: "Nam", notes: "Yêu thích thiên nhiên", tags: ["Rừng", "Đà Lạt", "Check-in"] },
            { name: "Thái Trung Nhẫn", posts: 65, avatar: "https://i.pravatar.cc/40?img=7", medal: "", gender: "Nam", notes: "Yêu thích thiên nhiên", tags: ["Rừng", "Đà Lạt", "Check-in"] }
        ];

        let topUsersVisible = 3;

        function renderTopUsers() {
            const topUsersList = document.getElementById('top-users');
            if (topUsersList) {
                topUsersList.innerHTML = topUsers.slice(0, topUsersVisible).map((user, index) =>
                    `<div class="flex items-center justify-between space-y-0 ${index === 0 ? 'mt-0' : 'mt-2'}" data-user="${user.name}">
                        <div class="flex items-center space-x-3">
                            <img src="${user.avatar}" class="w-10 h-10 rounded-full object-cover">
                            <div>
                                <span class="text-gray-800 text-sm font-semibold">${user.name}</span>
                                <p class="text-sm text-gray-600">${user.posts} bài viết</p>
                            </div>
                        </div>
                        <span class="text-gray-800 text-sm font-semibold">${user.medal}</span>
                    </div>`
                ).join('');

                document.querySelectorAll('#top-users div').forEach(item => {
                    item.addEventListener('click', () => {
                        const userName = item.getAttribute('data-user');
                        const user = topUsers.find(u => u.name === userName);
                        if (user) showUserDetails(user, item);
                    });
                });
            }
        }

        function showUserDetails(user, targetElement) {
            const modal = document.getElementById('user-detail-modal');
            if (!modal) return;
            document.getElementById('user-detail-name').innerText = user.name;
            document.getElementById('user-detail-gender').innerText = `Giới tính: ${user.gender}`;
            document.getElementById('user-detail-notes').innerText = `Ghi chú: ${user.notes}`;
            document.getElementById('user-detail-avatar').src = user.avatar;
            document.getElementById('user-detail-tags').innerHTML = user.tags.map(tag =>
                `<span class="bg-blue-100 text-blue-800 text-xs font-semibold px-2 py-1 rounded-full">${tag}</span>`
            ).join('');

            const rect = targetElement.getBoundingClientRect();
            const parentRect = document.getElementById('top-contributors').getBoundingClientRect();
            modal.style.top = `${rect.bottom - parentRect.top}px`;
            modal.style.left = `${rect.left - parentRect.left}px`;
            modal.classList.remove('hidden');
        }

        document.getElementById('close-modal')?.addEventListener('click', () => {
            document.getElementById('user-detail-modal')?.classList.add('hidden');
        });

        document.getElementById('toggle-top-users')?.addEventListener('click', () => {
            topUsersVisible = topUsersVisible === 3 ? topUsers.length : 3;
            document.getElementById('toggle-top-users').innerText = topUsersVisible === 3 ? 'Xem thêm' : 'Thu gọn';
            renderTopUsers();
        });

        renderTopUsers();

        // Địa điểm nổi bật
        const hotPlacesByMonth = {
            2: [
                { name: "Đà Lạt", views: 30000, image: "https://idalat.vn/blog/wp-content/uploads/sites/2/2020/09/vi-sao-cu-dan-mang-cu-hay-goi-da-lat-la-dallas-1-600x687.jpg", url: "https://dalat.vn" },
                { name: "Phan Thiết", views: 25200, image: "https://dulichyenviet.com/wp-content/uploads/2023/11/maxresdefault-4.jpg", url: "https://phnthiet.vn" },
                { name: "Hà Nội", views: 7200, image: "https://i.pinimg.com/550x/c7/cc/7b/c7cc7bcf0a40c73bb658690e45aef7ef.jpg", url: "https://hanoi.vn" },
                { name: "Hà Giang", views: 5200, image: "https://dulichchat.com/wp-content/uploads/2019/12/kinh-nghiem-du-lich-ha-giang-tu-tuc-dulichchat-780x405.jpg", url: "https://hagiang.vn" },
                { name: "Phú Quốc", views: 15000, image: "https://file.hstatic.net/200000504041/file/phu-quoc-joys-holiday-hon-mong-tay_7aa61984a61649338e6c7bb83459bd3f_grande.jpeg", url: "https://phuquoc.vn" },
                { name: "Hội An", views: 8700, image: "https://bizweb.dktcdn.net/100/349/716/files/pho-co-hoi-an-1-1.png?v=1727928153980", url: "https://hoian.vn" }
            ]
        };

        const currentMonth = 2;
        const hotPlaces = hotPlacesByMonth[currentMonth] || [];
        let currentIndex = 0;

        function updateHotPlaceBanner(index) {
            if (!hotPlaces.length) return;
            const place = hotPlaces[index];
            const bannerImage = document.getElementById('hot-place-image');
            const bannerName = document.getElementById('hot-place-name');
            const bannerViews = document.getElementById('hot-place-views');
            const bannerLink = document.getElementById('hot-place-link');
            if (bannerImage && bannerName && bannerViews && bannerLink) {
                bannerImage.classList.add('opacity-0');
                setTimeout(() => {
                    bannerImage.src = place.image;
                    bannerName.innerText = place.name;
                    bannerViews.innerText = `${place.views.toLocaleString()} lượt khách ghé thăm`;
                    bannerLink.href = place.url;
                    bannerImage.classList.remove('opacity-0');
                }, 500);
            }
            const indicators = document.getElementById('banner-indicators');
            if (indicators) {
                indicators.innerHTML = hotPlaces.map((_, i) =>
                    `<span class="h-2 w-2 rounded-full transition ${i === index ? 'bg-blue-500' : 'bg-gray-300'}"></span>`
                ).join('');
            }
        }

        document.getElementById('next-place')?.addEventListener('click', () => {
            currentIndex = (currentIndex + 1) % hotPlaces.length;
            updateHotPlaceBanner(currentIndex);
        });

        document.getElementById('prev-place')?.addEventListener('click', () => {
            currentIndex = (currentIndex - 1 + hotPlaces.length) % hotPlaces.length;
            updateHotPlaceBanner(currentIndex);
        });

        setInterval(() => {
            currentIndex = (currentIndex + 1) % hotPlaces.length;
            updateHotPlaceBanner(currentIndex);
        }, 10000);

        updateHotPlaceBanner(currentIndex);

        // Gợi ý lịch trình
        const itineraryList = document.getElementById('itinerary-list');
        const toggleItineraryBtn = document.getElementById('toggle-itinerary');
        let isExpanded = false;

        toggleItineraryBtn?.addEventListener('click', () => {
            if (!isExpanded) {
                itineraryList.classList.remove('overflow-y-hidden');
                itineraryList.classList.add('overflow-y-auto', 'scrollbar-custom');
                toggleItineraryBtn.innerText = 'Thu gọn';
            } else {
                itineraryList.classList.add('overflow-y-hidden');
                itineraryList.classList.remove('overflow-y-auto', 'scrollbar-custom');
                toggleItineraryBtn.innerText = 'Xem thêm';
            }
            isExpanded = !isExpanded;
        });

        // Dữ liệu lịch trình chi tiết
        const itineraryDetails = {
            "phu-quoc": {
                title: "3 ngày 2 đêm tại Phú Quốc",
                location: "Phú Quốc, Kiên Giang",
                food: "Ngày 1: Bún quậy (50k), hải sản tươi sống (300k/người). Ngày 2: Gỏi cá trích (150k), cơm chiên ghẹ (100k). Ngày 3: Bánh canh chả cá (40k). Tổng: ~640k/người.",
                activity: "Ngày 1: Tham quan làng chài Rạch Vẹm (miễn phí), tắm biển Bãi Sao (miễn phí). Ngày 2: Lặn ngắm san hô (250k), check-in Sunset Sanato (100k). Ngày 3: Tham quan nhà tù Phú Quốc (miễn phí). Tổng: ~350k/người.",
                cost: "Vé máy bay khứ hồi: 1.200k, khách sạn 2 sao (2 đêm): 600k, ăn uống: 640k, hoạt động: 350k. Tổng: ~2.790k (~3.000.000 VNĐ)."
            },
            "ha-giang": {
                title: "5 ngày 4 đêm tại Hà Giang",
                location: "Hà Giang, vùng Đông Bắc",
                food: "Ngày 1: Bánh cuốn trứng (30k), thắng cố (50k). Ngày 2: Cháo ấu tẩu (40k), lẩu gà đen (200k). Ngày 3: Cơm lam (50k), thịt trâu gác bếp (150k). Ngày 4: Phở bò (40k), xôi ngũ sắc (30k). Ngày 5: Bánh cuốn (30k). Tổng: ~620k/người.",
                activity: "Ngày 1: Check-in cổng trời Quản Bạ (miễn phí). Ngày 2: Chinh phục đèo Mã Pí Lèng (miễn phí), ngắm hoa tam giác mạch (50k). Ngày 3: Thăm cột cờ Lũng Cú (25k). Ngày 4: Tham quan làng văn hóa Lũng Cẩm (50k). Ngày 5: Thư giãn ở Đồng Văn (miễn phí). Thuê xe máy (200k/ngày x 4 ngày = 800k). Tổng: ~925k/người.",
                cost: "Xe khách khứ hồi HN-HG: 600k, khách sạn/homestay (4 đêm): 1.200k, ăn uống: 620k, hoạt động + xe máy: 925k. Tổng: ~3.345k (~4.500.000 VNĐ với thêm chi phí phát sinh)."
            },
            "da-lat": {
                title: "3 ngày 2 đêm tại Đà Lạt",
                location: "Đà Lạt, Lâm Đồng",
                food: "Ngày 1: Bánh tráng nướng (30k), lẩu gà lá é (200k/người). Ngày 2: Bánh mì xíu mại (25k), kem bơ (30k). Ngày 3: Cơm niêu (60k). Tổng: ~345k/người.",
                activity: "Ngày 1: Tham quan hồ Xuân Hương (miễn phí), thung lũng Tình Yêu (100k). Ngày 2: Check-in đồi chè Cầu Đất (miễn phí), cây thông cô đơn (miễn phí). Ngày 3: Thác Datanla (50k). Tổng: ~150k/người.",
                cost: "Xe khách khứ hồi SG-ĐL: 500k, khách sạn 2 sao (2 đêm): 600k, ăn uống: 345k, hoạt động: 150k. Tổng: ~1.595k (~2.500.000 VNĐ với thêm chi phí phát sinh)."
            },
            "phan-thiet": {
                title: "2 ngày 1 đêm tại Phan Thiết",
                location: "Phan Thiết, Bình Thuận",
                food: "Ngày 1: Bánh canh chả cá (35k), hải sản (200k/người). Ngày 2: Bánh xèo (40k). Tổng: ~275k/người.",
                activity: "Ngày 1: Tham quan đồi cát bay (miễn phí), tắm biển Mũi Né (miễn phí). Ngày 2: Tháp Chăm Poshanu (15k), suối Tiên (15k). Tổng: ~30k/người.",
                cost: "Xe khách khứ hồi SG-PT: 300k, khách sạn 2 sao (1 đêm): 300k, ăn uống: 275k, hoạt động: 30k. Tổng: ~905k (~1.800.000 VNĐ với thêm chi phí phát sinh)."
            },
            "ha-noi": {
                title: "2 ngày 1 đêm tại Hà Nội",
                location: "Hà Nội, miền Bắc",
                food: "Ngày 1: Phở bò (50k), bún chả (60k). Ngày 2: Bánh cuốn (30k), chả cá Lã Vọng (150k). Tổng: ~290k/người.",
                activity: "Ngày 1: Tham quan Hồ Gươm (miễn phí), Văn Miếu (30k). Ngày 2: Phố cổ Hà Nội (miễn phí), cầu Long Biên (miễn phí). Tổng: ~30k/người.",
                cost: "Xe khách khứ hồi (nếu từ gần): 400k, khách sạn 2 sao (1 đêm): 400k, ăn uống: 290k, hoạt động: 30k. Tổng: ~1.120k (~1.500.000 VNĐ với thêm chi phí phát sinh)."
            }
        };

        // Xử lý sự kiện khi bấm vào lịch trình để hiển thị modal
        document.querySelectorAll('.itinerary-item').forEach(item => {
            item.addEventListener('click', () => {
                const itineraryId = item.getAttribute('data-itinerary');
                const details = itineraryDetails[itineraryId];
                const modal = document.getElementById('itinerary-modal');

                if (details && modal) {
                    document.getElementById('modal-title').innerText = details.title;
                    document.getElementById('modal-location').innerText = details.location;
                    document.getElementById('modal-food').innerText = details.food;
                    document.getElementById('modal-activity').innerText = details.activity;
                    document.getElementById('modal-cost').innerText = details.cost;
                    modal.classList.remove('hidden');
                }
            });
        });

        // Đóng modal
        document.getElementById('close-modal-itinerary')?.addEventListener('click', () => {
            document.getElementById('itinerary-modal')?.classList.add('hidden');
        });

        // Đóng modal khi bấm ra ngoài nội dung modal
        document.getElementById('itinerary-modal')?.addEventListener('click', (e) => {
            if (e.target === document.getElementById('itinerary-modal')) {
                document.getElementById('itinerary-modal').classList.add('hidden');
            }
        });
    });
</script>

<style>
    @keyframes marquee {
        0% {
            transform: translateX(100%);
        }

        100% {
            transform: translateX(-100%);
        }
    }

    #slogan {
        animation: marquee 10s linear infinite;
        white-space: nowrap;
        max-width: 100%;
        text-shadow: 0 0 5px rgba(0, 161, 54, 0.7), 0 0 10px rgba(0, 161, 54, 0.5);
    }

    .glow-text {
        animation: glow 1.5s ease-in-out infinite alternate;
    }

    @keyframes glow {
        from {
            text-shadow: 0 0 5px rgba(0, 161, 54, 0.7), 0 0 10px rgba(0, 161, 54, 0.5);
        }

        to {
            text-shadow: 0 0 10px rgba(0, 161, 54, 0.9), 0 0 15px rgba(0, 161, 54, 0.7);
        }
    }

    .menu-btn {
        transition: all 0.3s ease;
    }

    .menu-btn:hover {
        transform: scale(1.05);
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
    }

    #top-users div {
        transition: all 0.3s ease;
        padding-left: 0;
    }

    #top-users div:hover {
        padding-left: 5px;
        background-color: #f3f3f3;
    }

    #hot-place-banner button {
        transition: all 0.3s ease;
    }

    #hot-place-banner button:hover {
        background-color: #f3f4f6;
        color: #1f2937;
    }

    #user-detail-modal {
        transition: opacity 0.3s ease;
    }

    #user-detail-modal.hidden {
        opacity: 0;
        pointer-events: none;
    }

    #user-detail-tags span {
        transition: all 0.3s ease;
    }

    #user-detail-tags span:hover {
        transform: scale(1.05);
        background-color: #dbeafe;
    }

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

    #itinerary-modal {
        transition: opacity 0.3s ease-in-out;
    }

    #itinerary-modal.hidden {
        opacity: 0;
        pointer-events: none;
    }

    #itinerary-modal:not(.hidden) {
        opacity: 1;
    }

    #itinerary-modal > div {
        animation: slideIn 0.3s ease-in-out;
        background: linear-gradient(135deg, #ffffff, #f9fafb);
    }

    @keyframes slideIn {
        from {
            opacity: 0;
            transform: translateY(-20px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
</style>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css">