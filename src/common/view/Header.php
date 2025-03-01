<!DOCTYPE html>
<html>

<head>

    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta author="David Baqueiro">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>TRAVEL BLOG</title>

    <!-- <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet"> -->
    <!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css"> -->
    <!-- <link href="/www/dist/src.css?v=<?= $this->cache_version; ?>" rel="stylesheet"> -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <!-- <script src="https://unpkg.com/@tailwindcss/browser@4"></script> -->
    <!-- <script src="https://cdn.tailwindcss.com"></script> -->
    <script src="https://cdn.tailwindcss.com?plugins=forms,typography,aspect-ratio,line-clamp,container-queries"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
    <script src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
</head>

<body class="">
    <?php
    if (session_status() == PHP_SESSION_NONE) {
        session_start();

        // Đặt thời gian sống của session và cookie là 24 giờ
        ini_set('session.gc_maxlifetime', 86400); // 24 giờ (giây)
        ini_set('session.cookie_lifetime', 86400); // Cookie sống 24 giờ
    }
    if (isset($_SESSION['toast'])) {
        $toast = $_SESSION['toast'];
        unset($_SESSION['toast']); // Xóa sau khi hiển thị
    ?>
        <script>
            Toastify({
                text: "<?= $toast['message'] ?>",
                duration: 3000,
                gravity: "bottom",
                position: "right",
                backgroundColor: "<?= $toast['type'] === 'success' ? '#22c55e' : '#ef4444' ?>",
                stopOnFocus: true
            }).showToast();
        </script>
    <?php } ?>

    <?php if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) {
        $user = $_SESSION['user'];
    } ?>

    <?php
    // Custom CSS/JS
    // foreach ($this->output_styles as $style_file) {
    //     echo $style_file;
    // }
    // foreach ($this->output_scripts as $script_file) {
    //     echo $script_file;
    // }
    // 
    ?>
    <div class="overflow-x-hidden">
        <header class="fixed z-50 top-0 left-0 right-0 h-[78px] bg-white mb-[78px] shadow-md flex items-center w-full justify-center">
            <div class="w-full flex items-center mx-[150px]">

                <a href="/" class="text-3xl no-underline font-semibold flex">
                    <p class="text-green-500">T</p>
                    <p class="text-gray-800">RAVELBLOGS</p>
                </a>

                <div class="flex-1 flex items-center justify-center">

                    <div class="relative" onclick="openSearchDialog(event)">
                        <button type="submit" class="absolute top-0 right-0 bottom-0 px-4 py-2 bg-green-500 text-white rounded-full">
                            <i class="fa-solid fa-search"></i>
                        </button>
                        <input type="text" name="q" class="w-[400px] h-[40px] px-4 py-2 border border-gray-300 rounded-full focus:outline-none focus-within:outline-green-500 focus-visible:outline-green-500 focus-within:ring-0" placeholder="Tìm kiếm..." readonly>
                    </div>

                </div>

                <!-- Dialog tìm kiếm -->
                <div id="searchDialog" class="fixed inset-0 bg-gray-800 bg-opacity-50 hidden flex items-center justify-center" onclick="closeSearchDialog(event)">
                    <div class="bg-white p-6 rounded-lg w-[90%] max-w-md" onclick="event.stopPropagation()">
                        <h2 class="text-xl font-bold mb-4">Tìm kiếm bài viết</h2>
                        <div class="relative">
                            <input type="text" id="searchInput" class="w-full px-4 py-2 border border-gray-300 rounded-full focus:outline-none" placeholder="Nhập từ khóa...">
                            <button class="absolute top-0 right-0 bottom-0 px-4 py-2 bg-green-500 text-white rounded-full">
                                <i class="fa-solid fa-search"></i>
                            </button>
                        </div>
                        <!-- Kết quả tìm kiếm -->
                        <div id="searchResults" class="mt-4 max-h-40 overflow-y-auto"></div>
                        <!-- <button onclick="closeSearchDialog()" class="mt-4 px-4 py-2 bg-gray-300 rounded-md">Đóng</button> -->
                    </div>
                </div>

                <?php if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true): ?>


                    <div id="userDropdown" class="flex items-center gap-x-2 cursor-pointer relative group">
                        <div class="flex items-end flex-col">
                            <p id="full_name_ref" class="text-gray-800 text-base font-semibold"><?php echo htmlspecialchars($_SESSION['user']['full_name']) ?></p>
                            <p id="bio_ref" class="text-gray-500 text-sm font-light">Phiêu lưu mạo hiểm</p>
                        </div>

                        <img id="profile_picture_ref" class="w-[56px] h-[56px] border shadow rounded-full hover:opacity-80 cursor-pointer transition-all duration-500" src="/assets/images/placeholder.jpg" alt="user" />

                        <!-- Dropdown menu -->
                        <div class="absolute right-0 mt-2 top-full w-48 bg-white rounded-md shadow-lg opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-300 z-10">
                            <ul class="py-1">
                                <?php if (isset($_SESSION['user']) && $_SESSION['user']['role'] == 'admin'): ?>
                                    <li>
                                        <a href="/profile" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900"><i class="fa-solid fa-shield mr-2"></i> Admin</a>
                                    </li>
                                <?php endif; ?>
                                <li>
                                    <a href="/profile" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900"><i class="fa-solid fa-user mr-2"></i> Hồ sơ</a>
                                </li>
                                <li>
                                    <a href="/settings" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900"><i class="fa-solid fa-gear mr-2"></i> Cài đặt</a>
                                </li>
                                <li>
                                    <a href="/logout" class="flex items-center gap-x-3 px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900">
                                        <i class="fa-solid text-base fa-right-from-bracket text-rose-600"></i>
                                        <p class="text-rose-600 font-semibold text-base">Đăng xuất</p>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>

                <?php else: ?>
                    <a href="/login">
                        <button type="button" class="px-6 py-2 text-sm font-semibold border-[2px] hover:bg-green-500 hover:text-white transition-all hover:scale-105 duration-500 rounded-md border-green-500 text-green-600">Đăng Nhập</button>
                    </a>
                <?php endif; ?>
            </div>
            <script>
                const full_name_ref = document.getElementById('full_name_ref');
                const bio_ref = document.getElementById('bio_ref');
                const profile_picture_ref = document.getElementById('profile_picture_ref')

                document.addEventListener('DOMContentLoaded', function() {
                    const dropdown = document.getElementById('userDropdown');
                    const dropdownMenu = dropdown.querySelector('.absolute');

                    dropdown.addEventListener('mouseenter', function() {
                        dropdownMenu.classList.remove('opacity-0', 'invisible');
                        dropdownMenu.classList.add('opacity-100', 'visible');
                    });

                    dropdown.addEventListener('mouseleave', function() {
                        dropdownMenu.classList.remove('opacity-100', 'visible');
                        dropdownMenu.classList.add('opacity-0', 'invisible');
                    });
                });

                function openSearchDialog(event) {
                    event.preventDefault();
                    document.getElementById('searchDialog').classList.remove('hidden');
                    document.getElementById('searchInput').focus();
                }

                function closeSearchDialog(event) {
                    if (event) event.preventDefault();
                    document.getElementById('searchDialog').classList.add('hidden');
                    document.getElementById('searchResults').innerHTML = ''; // Xóa kết quả khi đóng
                }

                // Debounce để hạn chế gửi request liên tục
                function debounce(func, wait) {
                    let timeout;
                    return function(...args) {
                        clearTimeout(timeout);
                        timeout = setTimeout(() => func.apply(this, args), wait);
                    };
                }

                // Hàm tìm kiếm
                function search(query) {
                    if (!query) {
                        document.getElementById('searchResults').innerHTML = '';
                        return;
                    }

                    fetch(`/posts/search?q=${encodeURIComponent(query)}`)
                        .then(response => response.json())
                        .then(results => {
                            const resultDiv = document.getElementById('searchResults');
                            if (!Array.isArray(results) || results.length === 0) {
                                resultDiv.innerHTML = '<p class="text-gray-500">Không tìm thấy kết quả.</p>';
                            } else {
                                resultDiv.innerHTML = results.map(post => `
                    <a href="/posts/${post.slug}" class="block p-2 hover:bg-gray-100">
                        <strong>${post.title}</strong>
                        <span class="text-xs text-gray-500"> (${post.published_at})</span>
                    </a>
                `).join('');
                            }
                        })
                        .catch(error => {
                            console.error('Lỗi:', error);
                            document.getElementById('searchResults').innerHTML = '<p class="text-red-500">Có lỗi xảy ra.</p>';
                        });
                }

                // Lắng nghe sự kiện input với debounce
                document.getElementById('searchInput').addEventListener('input', debounce(function(event) {
                    const query = event.target.value.trim();
                    search(query);
                }, 300)); // Chờ 300ms trước khi gửi request


                async function getMe() {
                    try {
                        const response = await fetch('/api/users/me');

                        if (!response.ok) {
                            throw new Error('Không thể lấy thông tin người dùng.');
                        }

                        const data = await response.json();
                        const user = data.user;
                        console.log(user);


                        full_name_ref.textContent = user.full_name;
                        bio_ref.textContent = user.bio;

                        if (user.profile_picture) {
                            profile_picture_ref.src = user.profile_picture;
                        }

                    } catch (error) {
                        console.error('Lỗi:', error);
                    }
                }


                document.addEventListener('DOMContentLoaded', async function() {
                    await getMe();
                });
            </script>
        </header>

        <div class="mt-[100px] w-full">
            <!-- <div id="main" class="main"> -->