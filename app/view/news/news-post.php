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
            #container {
                overflow: hidden;
            }
            .other-posts {
                box-sizing: border-box;
                width: calc((100% - 1rem * 2) / 3) !important;
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
                @apply hidden;
            }
            #container {
                overflow: hidden;
            }
            .other-posts{
                box-sizing: border-box;
                @apply w-full;
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

    $url = $_SERVER['REQUEST_URI']; // Lấy đường dẫn hiện tại
    $parts = explode("/", $url); // Tách thành mảng
    $id = end($parts); // Lấy phần cuối cùng (ID)

    $sql = "SELECT blogid, title, postdate, content FROM blogpost WHERE blogid = $id";
    $result = mysqli_query($conn, $sql);

    $sql_likes = "SELECT COUNT(*) as amount FROM `like` WHERE blogid = ?";
    $stmt_like = $conn->prepare($sql_likes);
    $stmt_like->bind_param("i", $id); // "i" là kiểu integer
    $stmt_like->execute();
    $result_like = $stmt_like->get_result();

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

    <div id="body" class="m-6 md:w-3/4 md:mx-auto">
        <div class="flex gap-8">
            <div class="w-1/4 flex-initial" id="related-posts">
                <ul class="menu w-full bg-white p-3 rounded-lg">
                    <li><a>Item 1</a></li>
                    <li><a>Item 2</a></li>
                    <li><a>Item 3</a></li>
                </ul>
            </div>
            <div class="flex-1">
                <?php
                    $row = $result->fetch_assoc();
                ?>
                <h5 class='italic font-semibold'>Bài viết</h5>
                <h1 class='text-2xl font-bold mt-4'><?= $row["title"] ?></h1>
                <p class='text-sm italic mt-2'><?= $row["postdate"] ?></p>
                <p class='mt-4'><?= $row["content"] ?></p>
            </div>
        </div>
        <div class="flex justify-end mt-8">
            <a href="#"><i class="fa-solid fa-thumbs-up h-full text-2xl"></i></a>
            <?php
                $row_likes = $result_like->fetch_assoc()
            ?>
            <p class="text-xl ml-4"><?= $row_likes['amount'] ?></p>
        </div>
        <div class="mt-8 p-4 border rounded-md shadow-md bg-base-100 w-full">                
            <div class="flex items-start space-x-3">
              <!-- User Avatar -->
              <div class="avatar">
                <div class="w-10 rounded-full">
                  <img src="https://i.pravatar.cc/100" alt="User Avatar">
                </div>
              </div>
          
              <!-- Comment Input -->
              <div class="flex-1">
                <textarea class="textarea textarea-bordered w-full" placeholder="Viết bình luận"></textarea>
                
                <!-- Action Buttons -->
                <div class="flex justify-end space-x-2 mt-2">
                  <button class="btn btn-ghost btn-md">Cancel</button>
                  <button class="btn btn-primary btn-md">Post</button>
                </div>
              </div>
            </div>
            <h2 class="font-bold mt-6 text-2xl">Tất cả bình luận</h2>
            <?php 
            $sql_comments = "SELECT username, commentdate, content 
                            FROM blogcomment 
                            NATURAL JOIN user  
                            WHERE blogid = ?";

            $stmt_comment = $conn->prepare($sql_comments);
            $stmt_comment->bind_param("i", $id); // "i" là kiểu integer
            $stmt_comment->execute();
            $result_comment = $stmt_comment->get_result();

            while ($row_comment = $result_comment->fetch_assoc()): 
                // Bảo vệ dữ liệu đầu ra tránh XSS
                $username = htmlspecialchars($row_comment['username']);
                $commentDate = htmlspecialchars($row_comment['commentdate']);
                $content = htmlspecialchars($row_comment['content']);
            ?>
                <div class="flex items-start mt-4 space-x-3">
                    <div class="avatar">
                        <div class="w-10 rounded-full">
                            <img src="https://i.pravatar.cc/100" alt="User Avatar">
                        </div>
                    </div>
                    <div class="flex-1">
                        <div class="flex items-center justify-between">
                            <h6 class="font-bold"><?= $username ?></h6>
                            <p class="text-sm text-gray-500"><?= $commentDate ?></p>
                        </div>
                        <p class="mt-2"><?= $content ?></p>
                    </div>
                </div>
            <?php 
            endwhile;
            $stmt_comment->close();
            ?>
        </div>
        <div class="mt-8">
            <h1 class="text-2xl text-center font-bold mt-4">Bài viết liên quan</h1>
            <div class="flex items-center gap-4 mt-6 max-h-64 box-border">
                <button class="btn btn-circle" id="slider-left">❮</button>
                <div class="flex gap-4" id="container">
                <?php
                    $sql_related_posts = "SELECT blogid, title, postdate, content FROM blogpost WHERE blogid != ?";
                    $stmt_related_posts = $conn->prepare($sql_related_posts);
                    $stmt_related_posts->bind_param("i", $id); 
                    $stmt_related_posts->execute();
                    $result_related_posts = $stmt_related_posts->get_result();
                    $related_posts = $result_related_posts->fetch_all(MYSQLI_ASSOC);

                    foreach ($related_posts as $post):
                        $blogid = htmlspecialchars($post['blogid']);
                        $title = htmlspecialchars($post['title']);
                        $postdate = htmlspecialchars($post['postdate']);
                        $content = htmlspecialchars($post['content']);
                ?>
                    <div class="carousel-item bg-white card-body rounded-lg other-posts" id="post<?= $blogid ?>">
                        <h2 class="card-title"><?= $title ?></h2>
                        <p class="italic"><?= $postdate ?></p>
                        <p class="line-clamp-3"><?= $content ?></p>
                    </div>
                <?php endforeach; ?>
                </div>
                <button class="btn btn-circle" id="slider-right">❯</button>
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

        document.getElementById("slider-right").addEventListener("click", () => {
            let all = document.querySelectorAll('[id^="post"]')

            const boxWidth = document.getElementById("container").offsetWidth
            const container = document.getElementById("container");

            if (screen.width >= 768) {
                let idName = all[2].id
                let idNum = parseInt(idName.replace("post", ""))

                if (idNum === all.length) return;
                for (let i = 0; i < all.length; i++) {
                    all[i].style.transition = "transform 0.6s ease";
                    all[i].style.transform = `translateX(-${(boxWidth - 16 * 2) / 3 + 16}px)`;
                }

                setTimeout(() => {
                    container.appendChild(all[0]);

                    for (let i = 0; i < all.length; i++) {
                        all[i].style.transition = "";
                        all[i].style.transform = "";
                    }
                }, 600);
            }
            else {
                let idName = all[0].id
                let idNum = parseInt(idName.replace("post", ""))

                if (idNum === all.length) return;
                
                for (let i = 0; i < all.length; i++) {
                    all[i].style.transition = "transform 0.3s ease";
                    all[i].style.transform = `translateX(-${boxWidth}px)`;
                }
                setTimeout(() => {
                    container.appendChild(all[0]);

                    for (let i = 0; i < all.length; i++) {
                        all[i].style.transition = "";
                        all[i].style.transform = "";
                    }
                }, 300);
            }
        });

        document.getElementById("slider-left").addEventListener("click", () => {
            let all = document.querySelectorAll('[id^="post"]')
            let idName = all[0].id
            let idNum = parseInt(idName.replace("post", ""))

            if (idNum === 1) return;

            const boxWidth = document.getElementById("container").offsetWidth
            const container = document.getElementById("container");

            if(screen.width >= 768) {
                for (let i = all.length - 1; i >= 0; i--) {
                    all[i].style.transform = `translateX(-${(boxWidth - 16 * 2) / 3 + 16}px)`;
                }
            }
            else {
                for (let i = all.length - 1; i >= 0; i--) {
                    all[i].style.transform = `translateX(-${boxWidth}px)`;
                }
            }

            container.insertBefore(all[all.length - 1], container.firstChild);

            setTimeout(() => {
                for (let i = 0; i < all.length; i++) {
                    all[i].style.transform = "";
                    if(screen.width > 768) all[i].style.transition = "transform 0.6s ease";
                    else all[i].style.transition = "transform 0.3s ease";
                }
            }, 10);

            if(screen.width >= 768) {
                setTimeout(() => {
                    for (let i = 0; i < all.length; i++) {
                        all[i].style.transition = "";
                    }
                }, 590);
            }
            else {
                setTimeout(() => {
                    for (let i = 0; i < all.length; i++) {
                        all[i].style.transition = "";
                    }
                }, 290);
            }
        });
    </script>

</body>

</html>