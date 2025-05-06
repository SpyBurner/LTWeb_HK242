<!-- place this at the top of <body> -->
<header class="bg-base-300 py-4">
    <div id="header-content" class="flex flex-row gap-10 justify-between items-center w-3/4 mx-auto max-md:gap-2 max-md:w-11/12">
        <!-- Sidebar Toggle Button -->
        <button id="toggleSidebar" class="btn btn-primary rounded-lg btn-lg md:hidden">
            <i class="fa-solid fa-bars"></i>
        </button>

        <!-- logo -->
        <div id="logo-header">
            <img src="../../../public/assets/img/logo_logo.png" alt="header logo" width="150">
        </div>

        <!-- navigation -->
        <nav id="nav" class="flex-5 flex flex-row flex-wrap justify-center max-md:hidden">
            <a href="#" class="btn btn-ghost hover:bg-base-100 rounded-none py-2 px-6">HOME</a>
            <a href="../about/about.html" class="btn btn-ghost hover:bg-base-100 rounded-none py-2 px-6">ABOUT</a>
            <a href="../about/about.html" class="btn btn-ghost hover:bg-base-100 rounded-none py-2 px-6">CONTACT</a>
            <a href="#" class="btn btn-ghost hover:bg-base-100 rounded-none py-2 px-6">QNA</a>
            <a href="#" class="btn btn-ghost hover:bg-base-100 rounded-none py-2 px-6">NEWS</a>
        </nav>

        <div id="profile" class="min-w-fit flex flex-col gap-4 items-center ">
            <!-- profile -->
            <div class="dropdown">
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
                            <!-- <span class="badge">New</span> -->
                        </a>
                    </li>
                    <!-- <li><a>Settings</a></li> -->
                    <li><a class="text-error">Logout</a></li>
                </ul>
            </div>
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
        <a href="../home/home.html" class="btn btn-ghost rounded-none py-6">Home</a>
        <a href="../about/about.html" class="btn btn-ghost rounded-none py-6">About</a>
        <a href="#" class="btn btn-ghost rounded-none py-6">QnA</a>
        <a href="#" class="btn btn-ghost rounded-none py-6">News</a>
        <a href="../account/login.php" class="btn btn-outline mx-4 my-2">Profile</a>
        <a href="../account/login.php" class="btn btn-error mx-4 my-2">Logout</a>
        <!-- <a href="../account/register.html" class="btn mx-4 my-2">Register</a> -->
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

    // Close sidebar on Escape key press
    document.addEventListener("keydown", (e) => {
        if (e.key === "Escape") closeSidebar();
    });
</script>