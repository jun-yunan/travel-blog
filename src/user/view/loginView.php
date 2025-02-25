<!DOCTYPE html>
<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta author="David Baqueiro">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Blog Travel | Sign In</title>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com?plugins=forms,typography,aspect-ratio,line-clamp,container-queries"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link href="/www/dist/src.css?v=<?= $this->cache_version; ?>" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.snow.css" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper/swiper-bundle.min.css" />
    <script src="https://cdn.jsdelivr.net/npm/swiper/swiper-bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
    <script src="https://cdn.jsdelivr.net/npm/toastify-js"></script>

    <link href="/global.css" rel="stylesheet">
    <script src="/app.js"></script>
</head>

<body>
    <?php
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }
    if (isset($_SESSION['toast'])) {
        $toast = $_SESSION['toast'];
        unset($_SESSION['toast']); // Xóa sau khi hiển thị
    ?>
        <script>
            Toastify({
                text: "<?= $toast['message'] ?>",
                duration: 3000,
                gravity: "top",
                position: "right",
                backgroundColor: "<?= $toast['type'] === 'success' ? '#22c55e' : '#ef4444' ?>",
                stopOnFocus: true
            }).showToast();
        </script>
    <?php } ?>

    <?php if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) {
        $user = $_SESSION['user'];
    } ?>
    <div class="w-full h-[100vh] flex items-center justify-center">
        <div class="w-[80%] h-[80%] relative flex items-center border border-gray-500 rounded-lg shadow-md overflow-hidden">
            <div class="w-[50%] h-full relative">
                <a href="/" class="absolute z-10 top-3 left-3 rounded-full cursor-pointer gap-x-1 bg-gray-500 hover:bg-gray-300 hover:text-gray-500 transition duration-500 text-gray-200 flex items-center justify-center px-4 py-1 text-sm font-medium">
                    <ion-icon name="arrow-back-outline"></ion-icon>
                    <p>Trở về</p>
                </a>
                <div class="swiper w-full h-full">
                    <div class="swiper-wrapper w-full h-full">
                        <div class="swiper-slide flex items-center justify-center">
                            <img class="m-auto w-full h-full object-cover" src="/assets/images/ha_long_1.jpg" alt="ha long">
                        </div>
                        <div class="swiper-slide flex items-center justify-center">
                            <img class="m-auto w-full h-full object-cover" src="/assets/images/ha_long_2.jpg" alt="ha long">
                        </div>
                        <div class="swiper-slide flex items-center justify-center">
                            <img class="m-auto w-full h-full object-cover" src="/assets/images/ha_long_3.jpg" alt="ha long">
                        </div>
                        <div class="swiper-slide flex items-center justify-center">
                            <img class="m-auto w-full h-full object-cover" src="/assets/images/ha_long_4.jpg" alt="ha long">
                        </div>
                        <div class="swiper-slide flex items-center justify-center">
                            <img class="m-auto w-full h-full object-cover" src="/assets/images/ha_long_5.jpg" alt="ha long">
                        </div>
                    </div>
                    <!-- Add Pagination -->
                    <div class="swiper-pagination"></div>
                    <!-- <div class="swiper-button-next"></div>
                    <div class="swiper-button-prev"></div> -->
                </div>
            </div>

            <div class="w-[50%] h-full">
                <form id="form-login" action="" method="POST" class="w-full h-full px-12 py-6 space-y-8 flex flex-col">
                    <div class="w-full flex flex-col self-start">
                        <h1 class="text-lg font-semibold">Đăng Nhập</h1>
                        <!-- <p></p> -->
                    </div>
                    <div class="flex flex-col">
                        <label for="" class="block text-sm font-medium text-gray-700">Email</label>
                        <div class="flex items-center mt-1 w-full px-3 py-1 border border-gray-300 rounded-md shadow-sm">
                            <ion-icon name="mail-outline"></ion-icon>
                            <input name="email" type="email" placeholder="Nhập địa chỉ email..." class="bg-transparent border-none focus:ring-0 w-full" />
                        </div>
                    </div>
                    <div class="flex flex-col">
                        <label for="" class="block text-sm font-medium text-gray-700">Mật khẩu</label>

                        <div class="flex items-center mt-1 w-full px-3 py-1 border border-gray-300 rounded-md shadow-sm">
                            <ion-icon name="key-outline" class="mr-2"></ion-icon>
                            <input name="password" type="password" placeholder="***********" id="input-password" class="bg-transparent w-full border-none focus:ring-0" />
                            <button type="button" id="button-toggle-eye" class="ml-2">
                                <ion-icon id="toggle-icon" name="eye-off-outline"></ion-icon>
                            </button>
                        </div>

                    </div>
                    <button type="submit" class="btn btn-primary w-full self-end">Đăng Nhập</button>
                    <div class="w-full flex items-center gap-x-2">
                        <div class="flex-1 h-px bg-gray-300"></div>
                        <p class="text-sm font-medium text-gray-500 whitespace-nowrap">Hoặc đăng nhập với</p>
                        <div class="flex-1 h-px bg-gray-300"></div>
                    </div>

                    <div class="flex items-center w-full gap-x-6">
                        <a href="#" id="sign-in-google" class="flex-1 flex items-center justify-center gap-x-2 border-2 border-gray-300 rounded-md hover:bg-gray-100 transition duration-500">
                            <img width="36" height="36" src="https://img.icons8.com/color/48/google-logo.png" alt="google-logo" />
                            <p class="text-base font-medium text-gray-700">Google</p>
                        </a>
                        <a href="#" class="flex-1 flex items-center justify-center gap-x-2 border-2 border-gray-300 rounded-md hover:bg-gray-100 transition duration-500">
                            <img width="36" height="36" src="https://img.icons8.com/ios-filled/50/github.png" alt="github" />
                            <p class="text-base font-medium text-gray-700">Github</p>
                        </a>
                    </div>

                    <div class="flex mx-auto items-center text-base text-gray-600">
                        <p>Chưa có tài khoản? </p>
                        <a href="/register" class="text-blue-500">Đăng ký</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>
    <script type="module" src="/app.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>

</html>