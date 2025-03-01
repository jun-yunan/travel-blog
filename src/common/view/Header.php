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
                    <div class="dropdown">
                        <div class="flex items-center gap-x-2 cursor-pointer" data-bs-toggle="dropdown" aria-expanded="false">
                            <div class="flex items-end flex-col">
                                <p class=" text-gray-800 text-base font-semibold"><?php echo htmlspecialchars($_SESSION['user']['full_name']) ?></p>
                                <p class="text-gray-500 text-sm font-light">Phiêu lưu mạo hiểm</p>
                            </div>

                            <img class="w-[56px] h-[56px] border shadow rounded-full hover:opacity-80 cursor-pointer transition-all duration-500" src="/assets/images/placeholder.jpg" alt="user" />

                        </div>

                        <ul class="dropdown-menu">
                            <?php if (isset($_SESSION['user']) && $_SESSION['user']['role'] == 'admin'): ?>
                                <li><a class="dropdown-item" href="/user/admin">Admin</a></li>
                            <?php endif; ?>

                            <li><a class="dropdown-item" href="index?route=user/user/profile">Hồ sơ</a></li>
                            <li><a class="dropdown-item" href="index?route=user/user/settings">Cài đặt</a></li>
                            <li><a class="dropdown-item" href="#">Dark mode</a></li>
                            <div class="dropdown-divider"></div>
                            <li>
                                <a class="dropdown-item" href="/logout">
                                    <div class="flex items-center gap-x-2">
                                        <i class="fa-solid fa-right-from-bracket text-rose-600"></i>
                                        <p class="text-rose-600 font-semibold text-base">Đăng xuất</p>
                                    </div>
                                </a>
                            </li>
                        </ul>
                    </div>
                <?php else: ?>
                    <a href="/login">
                        <button type="button" class="px-6 py-2 text-sm font-semibold border-[2px] hover:bg-green-500 hover:text-white transition-all hover:scale-105 duration-500 rounded-md border-green-500 text-green-600">Đăng Nhập</button>
                    </a>
                <?php endif; ?>
            </div>
            <script>
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
            </script>
        </header>

        <div class="mt-[100px] w-full">
            <!-- <div id="main" class="main"> -->