<?php
require_once __DIR__ . "/../common/head.php";
require_once __DIR__ . "/../common/header.php";

assert(isset($posts));
?>
<!DOCTYPE html>
<html lang="en" data-theme="valentine">
<head>
    <title>CakeZone Blog</title>
</head>
<body>
    <div id="body" class="m-6 md:w-3/4 md:mx-auto flex gap-8">
        <div class="w-1/4 flex-initial" id="related-posts">
            <ul class="menu w-full bg-white p-3 rounded-lg">
                <li><a>Item 1</a></li>
                <li><a>Item 2</a></li>
                <li><a>Item 3</a></li>
            </ul>
        </div>
        <div class="flex-1">
            <form action="/news/search" class="flex justify-end">
                <input type="text" name="keyword" placeholder="Tìm kiếm bài viết" class="input border-1 rounded-md"/>
                <button type="submit" class="btn btn-primary rounded-lg px-6">
                    <i class="fas fa-search" aria-hidden="true"></i>
                </button>
            </form>
            <div class="mt-4">
            <?php
                $count = 1;
                foreach ($posts as $row):
                    $url = "blog/view?id=" . htmlspecialchars($row->getBlogid());

                    // Thêm class "hidden" nếu số bài viết >= 4
                    $hiddenClass = ($count >= 4) ? 'hidden' : '';
            ?>
                <a href="<?= $url ?>" class="card-body rounded-lg bg-white mt-2 <?= $hiddenClass ?>">
                    <h2 class="card-title"><?= htmlspecialchars($row->getTitle()) ?></h2>
                    <p class="italic"><?= htmlspecialchars($row->getPostdate()) ?></p>
                    <p class="line-clamp-1"><?= htmlspecialchars(substr(strip_tags($row->getContent()), 0, 150)) ?>...</p>
                </a>
            <?php 
                $count++;
                endforeach;
            ?>
            </div>
            <div class="flex justify-center mt-4 gap-4" id="pagination">
                <!-- <button id="page-1" onclick="pagination('page-1')"><input
                  class="join-item btn btn-square"
                  type="radio"
                  name="options"
                  aria-label="1"
                  checked="checked" /></button>
                <button id="page-2" onclick="pagination('page-2')"><input class="join-item btn btn-square" type="radio" name="options" aria-label="2" /></button>
                <button id="page-3" onclick="pagination('page-3')"><input class="join-item btn btn-square" type="radio" name="options" aria-label="3" /></button>
                <button id="page-4" onclick="pagination('page-4')"><input class="join-item btn btn-square" type="radio" name="options" aria-label="4" /></button> -->
            </div>
        </div>
    </div>

    <script>
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
                if (i >= allCards.length) break;
                allCards[i].classList.remove("hidden");
            }
        }

        let allCards = document.querySelectorAll(".card-body").length;
        let pageNum = Math.ceil(allCards / 3);
        for (let i = 1; i <= pageNum; i++) {
            let inputTag = `<input class="join-item btn btn-square" type="radio" name="options" aria-label="${i}" `
                + (i === 1 ? 'checked' : '') + ` />`;
            document.getElementById("pagination").innerHTML += `
                <button id="page-${i}" onclick="pagination('page-${i}')">
                    ${inputTag}
                </button>`;
        }
    </script>

<?php
// Include footer if you have one
require_once __DIR__ . "/../common/footer.php";
?>
</body>

</html>