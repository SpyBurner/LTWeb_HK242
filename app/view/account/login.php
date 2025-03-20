<!DOCTYPE html>
<html lang="en" data-theme="valentine">

<head>
    <title>CakeZone Login</title>

    <?php
        require_once __DIR__."/../common/head.php";
    ?>
</head>

<body>
    <?php
        require_once __DIR__."/../common/header.php";
    ?>

    <div id="body" class="m-6 md:w-3/4 md:mx-auto">
        <!-- name of each tab group should be unique -->

        <div class="card bg-base-100 max-w-2xl w-full shadow-2xl mx-auto p-4">
            <div class="rounded-full bg-base-200 p-2 flex gap-2 w-1/2 mx-auto">
                <a href="login" class="btn flex-1 bg-base-100 border border-primary">LOGIN</a>
                <a href="register" class="btn flex-1">REGISTER</a>
            </div>

            <div class="card-body">
                <fieldset class="fieldset">
                    <label class="input rounded-md w-full">
                        <i class="fa-solid fa-envelope"></i>
                        <input type="email" placeholder="mail@site.com" required />
                    </label>

                    <label class="input rounded-md w-full">
                        <i class="fa-solid fa-key"></i>
                        <input type="password" required placeholder="Password" />
                    </label>

                    <div><a class="link link-hover">Forgot password?</a></div>
                    <button class="btn btn-primary mt-6 w-1/2 mx-auto">Login</button>
                </fieldset>
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
    </script>

    <?php
        require_once __DIR__ . "/../common/footer.php";
    ?>

</body>

</html>