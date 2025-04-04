<!DOCTYPE html>
<html lang="en" data-theme="valentine">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>

    <!-- icons -->
    <script src="https://kit.fontawesome.com/d2a9bab36d.js" crossorigin="anonymous"></script>
    <!-- daisyui -->
    <link href="https://cdn.jsdelivr.net/npm/daisyui@5" rel="stylesheet" type="text/css" />
    <link href="https://cdn.jsdelivr.net/npm/daisyui@5.0.0/themes.css" rel="stylesheet" type="text/css" />
    <!-- tailwindcss -->
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>

    <style type="text/tailwindcss">
        @media (min-width: 768px) {
            #sidebar {
                display: none;
            }
        }
        @media (max-width: 768px) {
            #search-nav, #login-register {
                display: none;
            }
            #sidebar {
                display: block;
            }
            #header-content {
                @apply gap-2 w-11/12;
            }
            #related-posts {
                @apply hidden w-0;
            }
        }

    </style>
</head>

<body>
    <?php
    $env = parse_ini_file("../../../config/.env");
    $servername = $env["DB_HOST"];
    $username = "root";
    $password = "";
    $dbname = $env["DB_NAME"];

    // Create connection
    $conn = mysqli_connect($servername, $username, $password, $dbname);

    // Check connection
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }
    
    $sql = 'select blogid, title, postdate, content FROM blogpost';
    $result = mysqli_query($conn, $sql);

    ?>
    <header class="bg-pink-200 py-4">
        <!-- logo, search, login, cart -->
        <div id="header-content" class="flex flex-row gap-10 justify-between items-center w-3/4 mx-auto">
            <!-- Sidebar Toggle Button -->
            <button id="toggleSidebar" class="btn btn-primary rounded-lg btn-lg md:hidden">
                <i class="fa-solid fa-bars"></i>
            </button>

            <!-- logo -->
            <div id="logo-header" class="max-w-50">
                <img src="../../../public/assets/img/header-logo2-nobg.png" alt="header logo" width="250">
            </div>

            <!-- search, cate dropdown, navigation -->
            <div id="search-nav" class="flex flex-col gap-6 justify-between w-full">
                <!-- search bar -->
                <form class="p-2 bg-base-100 border border-gray-300 rounded-lg flex gap-2 justify-between">
                    <input type="text" placeholder="Search for products" class="input border-0 w-full rounded-md" />
                    <button type="submit" class="btn btn-primary rounded-lg px-6">
                        <i class="fas fa-search"></i>
                    </button>
                </form>

                <div class="flex flex-row items-center">
                    <!-- category dropdown -->
                    <div class="flex-1 dropdown dropdown-start">
                        <div tabindex="0" role="button" class="btn btn-neutral rounded-md">
                            <!-- <i class="fa-solid fa-list fa-xl"></i> -->
                            Categories
                        </div>
                        <ul tabindex="0"
                            class="dropdown-content menu bg-base-100 rounded-md z-1 w-52 p-2 shadow-sm mt-2">
                            <li><a>Item 1</a></li>
                            <li><a>Item 2</a></li>
                        </ul>
                    </div>

                    <nav class="flex-5 flex flex-row justify-center">
                        <a href="#" class="btn btn-ghost hover:bg-base-100 rounded-none py-2 px-6">HOME</a>
                        <a href="#" class="btn btn-ghost hover:bg-base-100 rounded-none py-2 px-6">ABOUT</a>
                        <a href="#" class="btn btn-ghost hover:bg-base-100 rounded-none py-2 px-6">QNA</a>
                        <a href="#" class="btn btn-ghost hover:bg-base-100 rounded-none py-2 px-6">NEWS</a>
                    </nav>
                </div>

            </div>

            <!-- profile/login, cart -->
            <div id="profile-cart" class="flex flex-col gap-4 items-center">
                <!-- profile -->
                <!-- <div class="dropdown">
                    <div tabindex="0" role="button" class="flex items-center gap-4 cursor-pointer">
                        <div class="avatar">
                            <div class="w-12 rounded-full">
                                <img src="https://img.daisyui.com/images/stock/photo-1534528741775-53994a69daeb.webp" />
                            </div>
                        </div>

                        <div class="username flex gap-2 items-center">
                            <span>Linh Thinh</span>
                            <i class="fas fa-angle-down"></i>
                        </div>
                    </div>
                    <ul tabindex="0"
                        class="menu menu-sm dropdown-content bg-base-100 rounded-box z-1 mt-3 w-52 p-2 shadow">
                        <li>
                            <a class="justify-between">
                                Profile
                                <span class="badge">New</span>
                            </a>
                        </li>
                        <li><a>Settings</a></li>
                        <li><a>Logout</a></li>
                    </ul>
                </div> -->


                <!-- login / register (replaced by profile after logged in) -->
                <div id="login-register" class="flex-none flex items-center gap-6 justify-center">
                    <a href="#">Login</a>
                    <a href="#" class="btn btn-primary rounded-md">Register</a>
                </div>

                <!-- cart -->
                <a href="#" class="btn btn-outline btn-primary rounded-lg btn-lg">
                    <i class="fas fa-shopping-cart"></i>
                    Cart
                </a>
            </div>
        </div>
    </header>

    <!-- Sidebar -->
    <div id="sidebar"
        class="fixed top-0 left-0 w-64 h-full bg-white shadow-lg transform -translate-x-full transition-transform duration-300 z-50">
        <div class="border-b flex justify-between items-center p-4">
            <h2 class="text-lg font-bold">MENU</h2>
            <button id="closeSidebar" class="btn btn-ghost">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>

        <nav class="flex flex-col">
            <a href="#" class="btn btn-ghost rounded-none py-6">Home</a>
            <a href="#" class="btn btn-ghost rounded-none py-6">About</a>
            <a href="#" class="btn btn-ghost rounded-none py-6">QnA</a>
            <a href="#" class="btn btn-ghost rounded-none py-6">News</a>
            <a href="#" class="btn btn-outline mx-4 my-2">Login</a>
            <a href="#" class="btn mx-4 my-2">Register</a>
        </nav>
    </div>

    <!-- Overlay -->
    <div id="overlay" class="hidden fixed inset-0 z-40"></div>

    <div id="body" class="m-6 md:w-3/4 md:mx-auto flex gap-8">
        <div class="w-1/4 flex-initial" id="related-posts">
            <ul class="menu w-full bg-white p-3 rounded-lg">
                <li><a>Item 1</a></li>
                <li><a>Item 2</a></li>
                <li><a>Item 3</a></li>
            </ul>
        </div>
        <div class="flex-1">
            <form action="" class="flex justify-end">
                <input type="text" placeholder="Tìm kiếm bài viết" class="input border-1 rounded-md"/>
                <button type="submit" class="btn btn-primary rounded-lg px-6" fdprocessedid="h4w6ky">
                    <i class="fas fa-search" aria-hidden="true"></i>
                </button>
            </form>
            <div class="mt-4">
            <?php if ($result->num_rows > 0): 
                $count = 1;
                while ($row = $result->fetch_assoc()): 
                    $url = "news-post.php/" . htmlspecialchars($row["blogid"]);
                    $title = htmlspecialchars($row["title"]);
                    $postdate = htmlspecialchars($row["postdate"]);
                    $content = htmlspecialchars($row["content"]);

                    // Thêm class "hidden" nếu số bài viết >= 4
                    $hiddenClass = ($count >= 4) ? 'hidden' : '';
            ?>
                    <a href="<?= $url ?>" class="card-body rounded-lg bg-white mt-2 <?= $hiddenClass ?>">
                        <h2 class="card-title"><?= $title ?></h2>
                        <p class="italic"><?= $postdate ?></p>
                        <p><?= $content ?></p>
                    </a>
            <?php 
                $count++;
                endwhile;
            else: ?>
                <p>0 results</p>
            <?php endif; ?>
            </div>
            <div class="flex justify-center mt-4 gap-4">
                <button id="page-1" onclick="pagination('page-1')"><input
                  class="join-item btn btn-square"
                  type="radio"
                  name="options"
                  aria-label="1"
                  checked="checked" /></button>
                <button id="page-2" onclick="pagination('page-2')"><input class="join-item btn btn-square" type="radio" name="options" aria-label="2" /></button>
                <button id="page-3" onclick="pagination('page-3')"><input class="join-item btn btn-square" type="radio" name="options" aria-label="3" /></button>
                <button id="page-4" onclick="pagination('page-4')"><input class="join-item btn btn-square" type="radio" name="options" aria-label="4" /></button>
            </div>
        </div>
    </div>

    <footer class="footer sm:footer-horizontal bg-base-300 text-base-content p-10 lg:px-40">
        <nav>
            <h6 class="footer-title">Services</h6>
            <a class="link link-hover">Branding</a>
            <a class="link link-hover">Design</a>
            <a class="link link-hover">Marketing</a>
            <a class="link link-hover">Advertisement</a>
        </nav>
        <nav>
            <h6 class="footer-title">Company</h6>
            <a class="link link-hover">About us</a>
            <a class="link link-hover">Contact</a>
            <a class="link link-hover">Jobs</a>
            <a class="link link-hover">Press kit</a>
        </nav>
        <nav>
            <h6 class="footer-title">Social</h6>
            <div class="grid grid-flow-col gap-4">
                <a>
                    <i class="fa-solid fa-map-location-dot fa-2xl"></i>
                </a>
                <a>
                    <i class="fa-brands fa-instagram fa-2xl"></i>
                </a>
                <a>
                    <i class="fa-brands fa-facebook fa-2xl"></i>
                </a>
            </div>
        </nav>
    </footer>


    <script>
        // toggle sidebar
        const sidebar = document.getElementById("sidebar");
        const overlay = document.getElementById("overlay");
        const toggleBtn = document.getElementById("toggleSidebar");
        const closeBtn = document.getElementById("closeSidebar");

        function openSidebar() {
            sidebar.classList.remove("-translate-x-full");
            overlay.classList.remove("hidden");
        }

        function closeSidebar() {
            sidebar.classList.add("-translate-x-full");
            overlay.classList.add("hidden");
        }

        toggleBtn.addEventListener("click", openSidebar);
        closeBtn.addEventListener("click", closeSidebar);
        overlay.addEventListener("click", closeSidebar);

        // Close sidebar on Escape key press
        document.addEventListener("keydown", (e) => {
            if (e.key === "Escape") closeSidebar();
        });

        function pagination(idName) {
            let buttons = document.getElementById(idName);

            let idNum = parseInt(idName.replace("page-", ""))

            let allCards = document.querySelectorAll(".card-body");

            allCards.forEach(card => {
                card.classList.add("hidden");
            });

            for (let i = (idNum - 1) * 3; i < idNum * 3; i++) {
                allCards[i].classList.remove("hidden");
            }
        }
    </script>
</body>

</html>