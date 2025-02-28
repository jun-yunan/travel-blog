<div id="friends"
    class="w-[350px] bg-white p-4 shadow-md rounded-lg overflow-hidden relative flex flex-col space-y-4 transition-transform duration-300 translate-x-0 right-0 fixed">

    <!-- Slogan động -->
    <div class="h-[50px] flex items-center justify-center overflow-hidden relative">
        <p id="slogan" class="text-lg font-bold text-gray-800 whitespace-nowrap animate-marquee"></p>
    </div>

    <!-- Menu -->
    <div class="grid grid-cols-3 gap-2 w-full">
        <button data-content="guide"
            class="menu-btn flex flex-col items-center justify-center bg-gray-100 w-20 h-20 rounded-lg text-xs font-semibold shadow-md hover:shadow-lg hover:scale-105 transition-all">
            <i class="bi bi-book text-xl"></i>
            Hướng dẫn
        </button>
        <button data-content="experience"
            class="menu-btn flex flex-col items-center justify-center bg-gray-100 w-20 h-20 rounded-lg text-xs font-semibold shadow-md hover:shadow-lg hover:scale-105 transition-all">
            <i class="bi bi-lightbulb text-xl"></i>
            Kinh nghiệm
        </button>
        <button data-content="contact"
            class="menu-btn flex flex-col items-center justify-center bg-gray-100 w-20 h-20 rounded-lg text-xs font-semibold shadow-md hover:shadow-lg hover:scale-105 transition-all">
            <i class="bi bi-envelope text-xl"></i>
            Liên hệ
        </button>
    </div>

    <div id="dynamic-content" class="hidden bg-white p-4 rounded-lg mt-4 shadow-lg w-full">
        <h2 id="content-title" class="text-lg font-bold"></h2>
        <p id="content-text" class="text-sm text-gray-700"></p>
    </div>

    <!-- Top người đóng góp -->
    <div id="top-contributors">
        <h2 class="text-black text-sm font-semibold">🏆 Top BXH đóng góp tháng này:</h2>
        <ul id="top-users" class="space-y-2"></ul>
        <button id="toggle-top-users"
            class="text-gray text-xs px-2 py-0 mt-1 rounded-md bg-transparent focus:outline-none focus:bg-white-600">
            Xem thêm
        </button>
    </div>

    <!-- Địa điểm nổi bật -->
    <div id="hot-place-banner" class="relative bg-gray-100 p-4 rounded-lg">
        <h2 class="text-black text-sm font-semibold">Địa điểm nổi bật tháng 2:</h2>

        <div class="relative">
            <img id="hot-place-image" src=""
                class="w-full h-48 object-cover rounded-lg transition-opacity duration-500">

            <button id="prev-place"
                class="absolute left-[-40px] top-1/2 transform -translate-y-1/2 bg-transparent border-none text-gray-600 text-2xl hover:text-gray-800 transition focus:outline-none">
                ⬅
            </button>
            <button id="next-place"
                class="absolute right-[-40px] top-1/2 transform -translate-y-1/2 bg-transparent border-none text-gray-600 text-2xl hover:text-gray-800 transition focus:outline-none">
                ➡
            </button>
        </div>

        <h3 id="hot-place-name" class="text-lg font-bold mt-2"></h3>
        <p id="hot-place-views" class="text-sm text-gray-600"></p>

        <div class="flex justify-center mt-2 space-x-1" id="banner-indicators"></div>
    </div>
</div>

<script>
    // Tạo slogan 
    const slogans = [
        "🎒 Xách balo lên và đi 🚀",
        "🌈 Khám phá thế giới qua từng chuyến đi 🌍",
        "🛣️ Cùng nhau khám phá thế giới ✨",
        "📖 Mỗi chuyến đi là một câu chuyện mới 🌍",
        "🌟 Hãy đi khi còn trẻ! ✈️",
        "🔥 Tuổi trẻ là những chuyến đi đầy đam mê ❤️",
        "🌏 Khám phá những miền đất mới 🚀"
    ];

    function updateSlogan() {
        const sloganEl = document.getElementById('slogan');
        if (sloganEl) {
            sloganEl.innerText = slogans[Math.floor(Math.random() * slogans.length)];
        }
    }
    updateSlogan();
    setInterval(updateSlogan, 10000);

    // Hiển thị nội dung menu động
    const contentData = {
        guide: { title: "Hướng dẫn", text: "Đây là nội dung hướng dẫn chi tiết dành cho bạn." },
        experience: { title: "Kinh nghiệm", text: "Những kinh nghiệm hữu ích khi đi du lịch bạn nên biết!" },
        contact: { title: "Liên hệ", text: "Bạn có thể liên hệ với chúng tôi qua email hoặc số điện thoại." }
    };

    document.addEventListener("click", function (event) {
        if (event.target.classList.contains("menu-btn")) {
            const contentType = event.target.getAttribute("data-content");
            const contentBox = document.getElementById("dynamic-content");
            if (contentBox) {
                document.getElementById("content-title").innerText = contentData[contentType].title;
                document.getElementById("content-text").innerText = contentData[contentType].text;
                contentBox.classList.remove("hidden");
            }
        }
    });

    // Hiển thị danh sách top người đóng góp
    const topUsers = [
        { name: "Nguyễn Nhựt Trường", posts: 120, avatar: "https://i.pravatar.cc/40?img=1", medal: "👑" },
        { name: "Nguyễn Anh Kiệt", posts: 98, avatar: "https://i.pravatar.cc/40?img=2", medal: "🥈" },
        { name: "Lâm Phan Kiều Trinh", posts: 85, avatar: "https://i.pravatar.cc/40?img=3", medal: "🥉" },
        { name: "Tăng Thị Thúy Huỳnh", posts: 72, avatar: "https://i.pravatar.cc/40?img=4", medal: "" },
        { name: "Lê Thị Kim Xuyến", posts: 65, avatar: "https://i.pravatar.cc/40?img=5", medal: "" }
    ];

    let topUsersVisible = 3;

    function renderTopUsers() {
        const topUsersList = document.getElementById("top-users");
        if (topUsersList) {
            topUsersList.innerHTML = topUsers.slice(0, topUsersVisible).map(user =>
                `<li class="flex items-center bg-white p-2 rounded-lg shadow-sm hover:bg-gray-100 transition">
                <img src="${user.avatar}" class="w-8 h-8 rounded-full mr-2">
                <span class="text-gray-800 text-sm font-semibold">${user.name} ${user.medal}</span>
                <span class="ml-auto text-gray-500 text-sm">${user.posts} bài</span>
            </li>`).join('');
        }
    }

    document.getElementById("toggle-top-users")?.addEventListener("click", () => {
        topUsersVisible = topUsersVisible === 3 ? topUsers.length : 3;
        document.getElementById("toggle-top-users").innerText = topUsersVisible === 3 ? "Xem thêm" : "Thu gọn";
        renderTopUsers();
    });

    renderTopUsers();

    // Hiển thị địa điểm nổi bật mỗi tháng
    const hotPlacesByMonth = {
        2: [
            { name: "Hà Giang", views: 5200, image: "https://www.vietnambooking.com/wp-content/uploads/2019/09/dia-diem-du-lich-ha-giang-1.jpg" },
            { name: "Phú Quốc", views: 15000, image: "https://tuyengiao.hagiang.gov.vn/upload/64711/20240401/grab76937cac_dao_o_phu_quoc_1_1632974771.jpg" },
            { name: "Hội An", views: 800, image: "https://image.nhandan.vn/w800/Uploaded/2025/igpcvcvjntc8510/2023_01_19/hoian-463.jpg.webp" }
        ]
    };

    const currentMonth = new Date().getMonth() + 1;
    let hotPlaces = hotPlacesByMonth[currentMonth] || [];
    let currentIndex = 0;

    function updateHotPlaceBanner(index) {
        if (!hotPlaces.length) return;

        const place = hotPlaces[index];
        const bannerImage = document.getElementById("hot-place-image");
        const bannerName = document.getElementById("hot-place-name");
        const bannerViews = document.getElementById("hot-place-views");

        if (bannerImage && bannerName && bannerViews) {
            bannerImage.classList.add("opacity-0");
            setTimeout(() => {
                bannerImage.src = place.image;
                bannerName.innerText = place.name;
                bannerViews.innerText = `Lượt khách ghé thăm: ${place.views.toLocaleString()} lượt`;
                bannerImage.classList.remove("opacity-0");
            }, 500);
        }

        const indicators = document.getElementById("banner-indicators");
        if (indicators) {
            indicators.innerHTML = hotPlaces.map((_, i) =>
                `<span class="h-2 w-2 rounded-full transition ${i === index ? "bg-gray-800 scale-110" : "bg-gray-400"
                }"></span>`
            ).join('');
        }
    }

    // Xử lý sự kiện khi bấm mũi tên
    document.getElementById("next-place")?.addEventListener("click", () => {
        currentIndex = (currentIndex + 1) % hotPlaces.length;
        updateHotPlaceBanner(currentIndex);
    });

    document.getElementById("prev-place")?.addEventListener("click", () => {
        currentIndex = (currentIndex - 1 + hotPlaces.length) % hotPlaces.length;
        updateHotPlaceBanner(currentIndex);
    });

    // Tự động chuyển banner
    setInterval(() => {
        currentIndex = (currentIndex + 1) % hotPlaces.length;
        updateHotPlaceBanner(currentIndex);
    }, 10000);

    updateHotPlaceBanner(currentIndex);
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
        color: gray;
        text-shadow: 0 0 10px #fff, 0 0 20px #0ff, 0 0 30px #0ff;
    }
</style>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css">