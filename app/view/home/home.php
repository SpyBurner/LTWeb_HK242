<!DOCTYPE html>
<html lang="en" data-theme="valentine">

<head>
    <?php require_once __DIR__ . "/../common/head.php"; ?>
    <title>CakeZone | Home</title>

    <style type="text/tailwindcss">
        .brand-name {
            @apply text-gray-500;
        }

        .product-name {
            @apply text-lg font-semibold text-black;
        }

        .price {
            @apply text-xl;
        }

        .sold-amt {
            @apply text-sm text-gray-500;
        }

    </style>
</head>

<body>
    <?php require_once __DIR__ . "/../common/header.php"; ?>

    <div id="body" class="m-6 md:w-3/4 md:mx-auto flex flex-col gap-10">
        <!-- carousel -->
        <div class="relative w-full mx-auto overflow-hidden rounded-lg">
            <!-- Carousel Wrapper -->
            <div id="carousel" class="flex transition-transform duration-500">
                <img src="/assets/img/banner-1.jpg" class="w-full flex-shrink-0" />
                <img src="/assets/img/banner-2.jpg" class="w-full flex-shrink-0" />
                <img src="/assets/img/banner-3.jpg" class="w-full flex-shrink-0" />
            </div>

            <!-- Navigation Buttons -->
            <button id="prevBtn" class="absolute left-4 top-1/2 transform -translate-y-1/2 hover:bg-base-200 border px-4 py-2 rounded-full">
                <i class="fa-solid fa-chevron-left"></i>
            </button>
            <button id="nextBtn" class="absolute right-4 top-1/2 transform -translate-y-1/2 hover:bg-base-200 border px-4 py-2 rounded-full">
                <i class="fa-solid fa-chevron-right"></i>
            </button>

            <!-- Indicators -->
            <div class="absolute bottom-4 left-1/2 transform -translate-x-1/2 flex space-x-2">
                <span class="dot w-3 h-3 bg-white rounded-full cursor-pointer"></span>
                <span class="dot w-3 h-3 bg-gray-400 rounded-full cursor-pointer"></span>
                <span class="dot w-3 h-3 bg-gray-400 rounded-full cursor-pointer"></span>
            </div>
        </div>

        <!-- products -->
        <div id="content" class="rounded-lg flex flex-col gap-6">
            <h2 class="text-3xl font-bold">OUR PRODUCTS</h2>
            <div class="tabs tabs-lift">
                <input type="radio" name="my_tabs_3" class="tab text-xl" aria-label="Newest" checked="checked" />
                <div class="tab-content bg-base-100 border-base-300 p-6">
                    <!-- products grid -->
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        <!-- product card -->
                        <div class="card bg-base-100 shadow-sm relative w-full">
                            <a href="#">
                                <img src="https://img.daisyui.com/images/stock/photo-1606107557195-0e29a4b5b4aa.webp"
                                    alt="product img" />
                                <div class="card-body">
                                    <p class="brand-name">CALBEE</p>
                                    <h2 class="product-name">Card Title Should Be Longer Than Ever</h2>
                                    <p class="price text-error">139,000đ</p>

                                    <div class="flex items-center">
                                        <p class="sold-amt">Đã bán: 206</p>
                                        <button class="btn btn-soft"><i class="fa-solid fa-cart-plus"></i></button>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>

                    <div class="text-center mt-6">
                        <button class="btn btn-lg btn-outline px-8">View all</button>
                    </div>
                </div>

                <input type="radio" name="my_tabs_3" class="tab text-xl" aria-label="Top rating" />
                <div class="tab-content bg-base-100 border-base-300 p-6">
                </div>

                <input type="radio" name="my_tabs_3" class="tab text-xl" aria-label="Best seller" />
                <div class="tab-content bg-base-100 border-base-300 p-6">
                </div>
            </div>

        </div>

        <!-- contact info -->
        <div class="hero bg-base-200 p-10">
            <div class="hero-content flex-col lg:flex-row-reverse">
                <div class="text-center lg:text-left">
                    <h1 class="text-5xl font-bold">Contact us!</h1>
                    <p class="py-6">
                        We are always here to help you with your sweetest dreams. Leave us a message and we will get
                        back
                        to you as soon as possible.
                        <br><br>
                        <strong>
                            Or you can visit us at our bakery at 123 Sweet Street, Sweetville, Sweetland.
                        </strong>
                    </p>
                </div>
                <div class="card bg-base-100 w-full max-w-sm shrink-0 shadow-2xl">
                    <div class="card-body">
                        <fieldset class="fieldset">
                            <label class="input rounded-md w-full">
                                <i class="fa-solid fa-user"></i>
                                <input type="input" required placeholder="name" />
                            </label>


                            <label class="input validator rounded-md w-full">
                                <i class="fa-solid fa-envelope"></i>
                                <input type="email" placeholder="mail@site.com" required />
                            </label>
                            <div class="validator-hint hidden">Enter valid email address</div>

                            <label class="input validator rounded-md w-full">
                                <i class="fa-solid fa-phone"></i>
                                <input type="tel" class="tabular-nums" required placeholder="Phone" pattern="[0-9]*"
                                    maxlength="11" title="Must be less than 12 digits" />
                            </label>
                            <p class="validator-hint hidden">Must be less than 12 digits</p>

                            <textarea class="textarea rounded-md w-full" placeholder="Message"></textarea>

                            <button class="btn btn-neutral mt-6 w-3/4 mx-auto">Send</button>
                        </fieldset>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php require_once __DIR__ . "/../common/footer.php"; ?>

    <script>
        // Carousel
        const carousel = document.getElementById("carousel");
        const dots = document.querySelectorAll(".dot");
        const prevBtn = document.getElementById("prevBtn");
        const nextBtn = document.getElementById("nextBtn");

        let index = 0;
        const totalSlides = carousel.children.length;
        let interval = setInterval(nextSlide, 3000); // Auto-slide every 3 seconds

        function updateCarousel() {
            carousel.style.transform = `translateX(-${index * 100}%)`;
            dots.forEach((dot, i) => dot.classList.toggle("bg-white", i === index));
            dots.forEach((dot, i) => dot.classList.toggle("bg-gray-400", i !== index));
        }

        function prevSlide() {
            index = (index - 1 + totalSlides) % totalSlides;
            updateCarousel();
        }

        function nextSlide() {
            index = (index + 1) % totalSlides;
            updateCarousel();
        }

        function goToSlide(i) {
            index = i;
            updateCarousel();
        }

        prevBtn.addEventListener("click", prevSlide);
        nextBtn.addEventListener("click", nextSlide);
        dots.forEach((dot, i) => dot.addEventListener("click", () => goToSlide(i)));

        // Pause auto-slide on hover
        document.querySelector(".relative").addEventListener("mouseenter", () => clearInterval(interval));
        document.querySelector(".relative").addEventListener("mouseleave", () => interval = setInterval(nextSlide, 3000));
    </script>

</body>

</html>