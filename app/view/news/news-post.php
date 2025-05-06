<?php
assert(isset($blog_info));
assert(isset($comments));
assert(isset($commentUser));
assert(isset($related_posts));
assert(isset($likes));
assert(isset($isLiked));
?>
<!DOCTYPE html>
<html lang="en" data-theme="valentine">

<head>
    <?php require_once __DIR__ . "/../common/head.php"; ?>
    <title>CakeZone Blog</title>
    <meta name="keywords" content="CakeZone, Bánh Kẹo, Ngọt">
    <meta name="description" content="<?= htmlspecialchars(strip_tags($blog_info->getContent())) ?>">
    <style type="text/tailwindcss">
        @media (min-width: 768px) {
            #container {
                overflow: hidden;
            }
            .other-posts {
                box-sizing: border-box;
                width: calc((100% - 1rem * 2) / 3) !important;
            }
            .posts-container {
                margin-left: 20%;
                margin-right: 20%;
            }
        }
        @media (max-width: 768px) {
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

        .blog-content ul {
            list-style-type: disc !important;
            padding-left: 2rem !important;
            margin-bottom: 1rem !important;
        }
        
        .blog-content ol {
            list-style-type: decimal !important;
            padding-left: 2rem !important;
            margin-bottom: 1rem !important;
        }
        
        .blog-content ul li, .blog-content ol li {
            margin-bottom: 0.5rem !important;
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
                <img src="../../../public/assets/img/logo_logo.png" alt="header logo" width="250">
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
                <p class='italic font-semibold'>Bài viết</p>
                <h1 class='text-2xl font-bold mt-4'><?= $blog_info->getTitle() ?></h1>
                <p class='text-sm italic mt-2'><?= $blog_info->getPostdate() ?></p>
                <div class='mt-10 blog-content'>
                    <?= $blog_info->getContent() ?>
                </div>
            </div>
        </div>
        <div class="flex justify-end mt-8">
            <button id="likeButton" data-blogid="<?= $blog_info->getBlogid() ?>">
                <i id="likeIcon" class="fa-solid fa-thumbs-up text-2xl <?= $isLiked ? 'text-primary' : '' ?>"></i>
            </button>
            <span id="likeCount" class="text-xl ml-2"><?= $likes ?></span>
        </div>
        <div class="mt-8 p-4 border rounded-md shadow-md bg-base-100 w-full">                
            <form method="post" action="/blog/view?id=<?= $blog_info->getBlogid() ?>" class="flex-1">
                <!-- User Avatar -->
                <textarea class="textarea px-4 w-full" name="content" placeholder="Viết bình luận"></textarea>
                    
                <!-- Action Buttons -->
                <div class="flex justify-end space-x-2 mt-2">
                    <button type="reset" class="btn btn-ghost btn-md">Reset</button>
                    <button type="submit" class="btn btn-primary btn-md">Post</button>
                </div>
            </form>
            <h2 class="font-bold mt-6 text-2xl">Tất cả bình luận</h2>
            <?php 
            $i = 0;
            while ($i < count($comments)): 
                // Bảo vệ dữ liệu đầu ra tránh XSS
                $username = htmlspecialchars($commentUser[$i]['username']);
                $avatar = '/'.$commentUser[$i]['avatar'];
                $commentDate = htmlspecialchars($comments[$i]->getCommentdate());
                $content = htmlspecialchars($comments[$i]->getContent());
            ?>
                <div class="flex items-start mt-4 space-x-3">
                    <div class="avatar">
                        <div class="w-10 rounded-full">
                            <img src="<?= $avatar ?>" alt="User Avatar">
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
                $i++;
                endwhile;
            ?>
        </div>
        <div class="mt-8">
            <h1 class="text-2xl text-center font-bold mt-4">Bài viết liên quan</h1>
            <div class="flex items-center gap-4 mt-6 max-h-64 box-border" id="related-posts-container">
                <button class="btn btn-circle" id="slider-left">❮</button>
                <div class="flex flex-1 gap-4" id="container">
                <?php
                    $count = 1;
                    foreach ($related_posts as $post):
                        $url = "/blog/view?id=" . htmlspecialchars($post->getBlogid());
                        $title = htmlspecialchars($post->getTitle());
                        $postdate = htmlspecialchars($post->getPostdate());
                        $content = htmlspecialchars($post->getContent());
                ?>
                    <a href="<?= $url ?>" class="carousel-item bg-white card-body rounded-lg other-posts" id="post<?= $count ?>">
                        <h2 class="card-title"><?= $title ?></h2>
                        <p class="italic"><?= $postdate ?></p>
                        <p class="line-clamp-3"><?= strip_tags(html_entity_decode($content)) ?></p>
                    </a>
                <?php 
                $count++;
                endforeach; 
                ?>
                </div>
                <button class="btn btn-circle" id="slider-right">❯</button>
            </div>
        </div>
    </div>

    <script>
        if(screen.width >= 768) {
            let all = document.querySelectorAll('[id^="post"]')
            if (all.length >= 3) {
                if (all.length == 3) {
                    document.getElementById("slider-left").classList.add("hidden");
                    document.getElementById("slider-right").classList.add("hidden");
                }
                for (let i = 0; i < all.length; i++) {
                    all[i].classList.add("carousel-item");
                }
            }
            else {
                document.getElementById("related-posts-container").classList.add("posts-container");
                document.getElementById("container").classList.add("w-full");
                for (let i = 0; i < all.length; i++) {
                    all[i].classList.remove("carousel-item");
                }
                document.getElementById("slider-left").classList.add("hidden");
                document.getElementById("slider-right").classList.add("hidden");
            }
        }

        else {
            document.getElementById("slider-left").classList.remove("hidden");
            document.getElementById("slider-right").classList.remove("hidden");
        }

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

        document.addEventListener('DOMContentLoaded', function() {
            const likeButton = document.getElementById('likeButton');
            const likeIcon = document.getElementById('likeIcon');
            const likeCount = document.getElementById('likeCount');
            const blogId = likeButton.getAttribute('data-blogid');
            
            let isLiked = likeIcon.classList.contains('text-primary');
            let currentLikes = parseInt(likeCount.textContent);
            
            likeButton.addEventListener('click', function() {
                if (!isLiked) {
                    likeIcon.classList.add('text-primary');
                    currentLikes++;
                    isLiked = true;
                }
                else return;
                
                likeCount.textContent = currentLikes;
                
                // Gửi yêu cầu AJAX đến server
                fetch('/blog/like', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify({
                        blogid: blogId
                    })
                })
                .then(response => {
                    if (!response.ok) {
                        return Promise.reject('Lỗi khi thích bài viết');
                    }
                    return response.json();
                })
                .catch(error => {
                    console.error('Lỗi:', error);
                });
            });
        });
    </script>

    <?php
    // Include footer if you have one
    require_once __DIR__ . "/../common/footer.php";
    ?>
</body>
</html>