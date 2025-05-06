<?php
assert(isset($header_isLoggedIn));
assert(isset($header_isAdmin));
assert(isset($header_username));
assert(isset($header_avatar));
assert(isset($header_categories));
assert(isset($header_logo));

use const config\STATIC_IMAGE_URL;
?>

<header class="bg-base-300 py-4">
    <!-- logo, search, login, cart -->
    <div id="header-content" class="flex flex-row gap-10 justify-between items-center w-3/4 mx-auto max-md:gap-2 max-md:w-11/12">
        <!-- Sidebar Toggle Button -->
        <button id="toggleSidebar" class="btn btn-primary rounded-lg btn-lg md:hidden">
            <i class="fa-solid fa-bars"></i>
        </button>

        <!-- logo -->
        <div id="logo-header">
            <a href="/"><img src="<?= STATIC_IMAGE_URL . $header_logo ?>" alt="logo" width="150" class="rounded-full"></a>
        </div>

        <!-- search, cate dropdown, navigation -->
        <div id="search-nav" class="flex-5 flex flex-col gap-6 justify-between max-md:hidden">
            <!-- search bar -->
            <form class="p-2 bg-base-100 border border-gray-300 rounded-lg flex gap-2 justify-between" action="/products" method="GET">
                <!-- <label> -->
                <input type="text" placeholder="Search for products" name="search" class="input border-0 w-full rounded-md" />
                <!-- </label> -->
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
                        <?php
                        foreach ($header_categories as $category) {
                            echo '<li><a href="/products?category=' . $category->getCateid() . '">' . $category->getName() . '</a></li>';
                        }
                        ?>
                    </ul>
                </div>

                <nav class="flex-5 flex flex-row flex-wrap justify-center">
                    <a href="/" class="btn btn-ghost hover:bg-base-100 rounded-none py-2 px-6">HOME</a>
                    <a href="/about" class="btn btn-ghost hover:bg-base-100 rounded-none py-2 px-6">ABOUT</a>
                    <a href="/qna" class="btn btn-ghost hover:bg-base-100 rounded-none py-2 px-6">QNA</a>
                    <a href="/news" class="btn btn-ghost hover:bg-base-100 rounded-none py-2 px-6">NEWS</a>
                </nav>
            </div>

        </div>

        <!-- profile/login, cart -->
        <div id="profile-cart" class="flex flex-col gap-4 items-center">
            <?php if ($header_isLoggedIn) { ?>
                <!-- PROFILE -->
                <div class="dropdown">
                    <div tabindex="0" role="button" class="flex items-center gap-4 cursor-pointer">
                        <div class="avatar">
                            <div class="w-12 rounded-full">
                                <img src="/<?= $header_avatar ?>" alt="avatar" />
                            </div>
                        </div>

                        <div class="username flex gap-2 items-center">
                            <span><?= $header_username ?></span>
                            <i class="fas fa-angle-down"></i>
                        </div>
                    </div>
                    <ul tabindex="0"
                        class="menu menu-sm dropdown-content bg-base-100 rounded-box z-1 mt-3 w-52 p-2 shadow">
                        <li>
                            <a href="/profile">Profile</a>
                        </li>
                        <li>
                            <a href="/orders/my-orders">My Orders</a>
                        </li>
                        <!--                    <li><a>Settings</a></li>-->
                        <li><a href="/account/logout">Logout</a></li>
                    </ul>
                </div>

                <div class="flex gap-4 items-center">
                    <a href="/cart" class="btn btn-outline btn-primary rounded-lg btn-lg">
                        <i class="fas fa-shopping-cart"></i>
                        Cart
                    </a>

                    <?php if ($header_isAdmin) { ?>
                        <a href="/admin" class="btn btn-ghost btn-neutral rounded-full btn-lg">
                            <i class="fa-solid fa-screwdriver-wrench"></i>
                        </a>
                    <?php } ?>
                </div>

            <?php } else { ?>
                <!-- LOGIN/REGISTER -->
                <div id="login-register" class="flex-none flex items-center gap-6 justify-center max-md:hidden">
                    <a href="/account/login">Login</a>
                    <a href="/account/register" class="btn btn-primary rounded-md">Register</a>
                </div>
            <?php } ?>
        </div>
    </div>
</header>

<!-- Sidebar -->
<div id="sidebar" class="fixed top-0 left-0 w-64 h-full bg-white shadow-lg transform -translate-x-full transition-transform duration-300 z-50">
    <div class="border-b flex justify-between items-center p-4">
        <h2 class="text-lg font-bold">MENU</h2>
        <button id="closeSidebar" class="btn btn-ghost">
            <i class="fa-solid fa-xmark"></i>
        </button>
    </div>

    <nav class="flex flex-col">
        <a href="/" class="btn btn-ghost rounded-none py-6">Home</a>
        <a href="/about" class="btn btn-ghost rounded-none py-6">About</a>
        <a href="/qna" class="btn btn-ghost rounded-none py-6">QnA</a>
        <a href="/news" class="btn btn-ghost rounded-none py-6">News</a>
        <a href="/account/login" class="btn btn-outline mx-4 my-2">Login</a>
        <a href="/account/register" class="btn mx-4 my-2">Register</a>
    </nav>
</div>

<!-- Overlay -->
<div id="overlay" class="hidden fixed inset-0 z-40"></div>


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

    // Close the sidebar on Escape key press
    document.addEventListener("keydown", (e) => {
        if (e.key === "Escape") closeSidebar();
    });
</script>

<?php
global $MESSAGE_DURATION;

//Include alert box
require_once __DIR__ . "/../../component/AlertBox.php";

use core\SessionHelper;

// Retrieve session data and clear it after displaying
$formData = SessionHelper::getFlash('form_data');
$error = SessionHelper::getFlash('error');
$success = SessionHelper::getFlash('success');

// Show alerts if any
if ($error) {
    showAlert($error, "error", MESSAGE_DURATION);
}
if ($success) {
    showAlert($success, "success", MESSAGE_DURATION);
}
?>