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
    <?php require_once __DIR__ . "/../common/header.php"; ?>
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