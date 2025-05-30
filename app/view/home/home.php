<?php

use const config\DEFAULT_PRODUCT_IMAGE_URL;
use const config\STATIC_IMAGE_URL;

assert(isset($newestProducts) && isset($topRatedProducts) && isset($bestSellers) && isset($address) && isset($carousel1) && isset($carousel2) && isset($carousel3) && isset($phone) && isset($header_logo));

// var_dump($newestProducts, $topRatedProducts, $bestSellers, $address); // Debugging line to check the variables
?>

<!DOCTYPE html>
<html lang="en" data-theme="valentine">

<head>
    <?php require_once __DIR__ . "/../common/head.php"; ?>
    <title>Buy Delicious Cakes Online | CakeZone</title>
    <meta name="description" content="Discover freshly baked cakes at CakeZone. Explore our best sellers, top-rated treats, and newest arrivals. Order online now!">

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

    <!-- JSON-LD for SEO -->
    <script type="application/ld+json">
        {
            "@context": "https://schema.org",
            "@type": "Organization",
            "name": "CakeZone",
            "url": "<?= BASE_URL ?>",
            "logo": "<?= STATIC_IMAGE_URL . $header_logo ?>",
            "contactPoint": {
                "@type": "ContactPoint",
                "telephone": "<?= $phone ?>",
                "contactType": "Customer Service"
            },
            "address": {
                "@type": "PostalAddress",
                "streetAddress": "<?= $address ?>",
                "addressCountry": "VN"
            }
        }
    </script>

    <script type="application/ld+json">
        {
            "@context": "https://schema.org",
            "@type": "ItemList",
            "itemListElement": [
                <?php foreach ($bestSellers as $index => $product): ?> {
                        "@type": "ListItem",
                        "position": <?= $index + 1 ?>,
                        "item": {
                            "@type": "Product",
                            "name": "<?= htmlspecialchars($product->getName()) ?>",
                            "image": "<?= htmlspecialchars($product->getAvatarurl()) ?>",
                            "brand": {
                                "@type": "Brand",
                                "name": "<?= htmlspecialchars($product->getManufacturerName()) ?>"
                            },
                            "offers": {
                                "@type": "Offer",
                                "priceCurrency": "VND",
                                "price": "<?= $product->getPrice() ?>",
                                "availability": "https://schema.org/InStock",
                                "url": "/products/detail/<?= $product->getProductid() ?>"
                            }
                        }
                    }
                    <?= $index < count($bestSellers) - 1 ? ',' : '' ?>
                <?php endforeach; ?>
            ]
        }
    </script>
</head>

<body>
    <?php require_once __DIR__ . "/../common/header.php"; ?>

    <div id="body" class="m-6 md:w-3/4 md:mx-auto flex flex-col gap-10">
        <!-- carousel -->
        <div class="relative w-full mx-auto overflow-hidden rounded-lg aspect-[3/1] bg-white">
            <!-- Carousel Wrapper -->
            <div id="carousel" class="flex transition-transform duration-500">
                <img loading="lazy" src="<?= STATIC_IMAGE_URL . $carousel1 ?>" class="w-full flex-shrink-0" alt="banner 1" />
                <img loading="lazy" src="<?= STATIC_IMAGE_URL . $carousel2 ?>" class="w-full flex-shrink-0" alt="banner 2" />
                <img loading="lazy" src="<?= STATIC_IMAGE_URL . $carousel3 ?>" class="w-full flex-shrink-0" alt="banner 3" />
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
                <span class="dot w-3 h-3 bg-primary rounded-full cursor-pointer"></span>
                <span class="dot w-3 h-3 bg-gray-400 rounded-full cursor-pointer"></span>
                <span class="dot w-3 h-3 bg-gray-400 rounded-full cursor-pointer"></span>
            </div>
        </div>

        <h1 class="text-4xl font-bold text-center">Freshly Baked Cakes Delivered to Your Door</h1>

        <!-- products -->
        <div id="content" class="rounded-lg flex flex-col gap-6">
            <h2 class="text-3xl font-bold">OUR PRODUCTS</h2>
            <div class="tabs tabs-lift">
                <input type="radio" name="my_tabs_3" class="tab text-xl" aria-label="Newest" checked="checked" />
                <div class="tab-content bg-base-100 border-base-300 p-6">
                    <!-- product grid -->
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        <?php foreach ($newestProducts as $product): ?>
                            <!-- product card -->
                            <div class="card bg-base-100 shadow-sm relative w-full rounded-lg overflow-hidden">
                                <a href="/products/detail/<?= $product->getProductid() ?>" class="block">
                                    <div class="image-container relative w-full h-[200px]">
                                        <img src="/<?= htmlspecialchars($product->getAvatarurl() == '' ? DEFAULT_PRODUCT_IMAGE_URL : $product->getAvatarurl()) ?>"
                                            alt="Cake - <?= htmlspecialchars($product->getName()) ?>"
                                            class="absolute inset-0 w-full h-full object-cover" />
                                    </div>
                                    <div class="card-body">
                                        <p class="brand-name"><?= htmlspecialchars($product->getManufacturerName()) ?></p>
                                        <h2 class="product-name"><?= htmlspecialchars($product->getName()) ?></h2>
                                        <p class="price text-error"><?= htmlspecialchars(number_format($product->getPrice(), 0, ',', '.')) ?> VND</p>

                                        <div class="flex items-center">
                                            <p class="sold-amt">Sold: <?= htmlspecialchars($product->getBought()) ?></p>
                                            <button
                                                aria-label="Add <?= htmlspecialchars($product->getName()) ?> to cart"
                                                class="btn btn-soft add-to-cart"
                                                data-product-id="<?= htmlspecialchars($product->getProductid()) ?>"
                                                data-amount="1">
                                                <i class="fa-solid fa-cart-plus"></i>
                                            </button>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <div class="text-center mt-6">
                        <a class="btn btn-lg btn-outline px-8" href="/products?sort=newest">View all</a>
                    </div>
                </div>

                <input type="radio" name="my_tabs_3" class="tab text-xl" aria-label="Top rated" />
                <div class="tab-content bg-base-100 border-base-300 p-6">
                    <!-- product grid -->
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        <?php foreach ($topRatedProducts as $product): ?>
                            <!-- product card -->
                            <div class="card bg-base-100 shadow-sm relative w-full rounded-lg overflow-hidden">
                                <a href="/products/detail/<?= $product->getProductid() ?>" class="block">
                                    <div class="image-container relative w-full h-[200px]">
                                        <img src="/<?= htmlspecialchars($product->getAvatarurl() == '' ? DEFAULT_PRODUCT_IMAGE_URL : $product->getAvatarurl()) ?>"
                                            alt="Cake - <?= htmlspecialchars($product->getName()) ?>"
                                            class="absolute inset-0 w-full h-full object-cover" />
                                    </div>
                                    <div class="card-body">
                                        <p class="brand-name"><?= htmlspecialchars($product->getManufacturerName()) ?></p>
                                        <h2 class="product-name"><?= htmlspecialchars($product->getName()) ?></h2>
                                        <p class="price text-error"><?= htmlspecialchars(number_format($product->getPrice(), 0, ',', '.')) ?> VND</p>

                                        <div class="flex items-center">
                                            <p class="sold-amt">Sold: <?= htmlspecialchars($product->getBought()) ?></p>
                                            <button
                                                aria-label="Add <?= htmlspecialchars($product->getName()) ?> to cart"
                                                class="btn btn-soft add-to-cart"
                                                data-product-id="<?= htmlspecialchars($product->getProductid()) ?>"
                                                data-amount="1">
                                                <i class="fa-solid fa-cart-plus"></i>
                                            </button>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <div class="text-center mt-6">
                        <a class="btn btn-lg btn-outline px-8" href="/products?sort=top_rated">View all</a>
                    </div>
                </div>

                <input type="radio" name="my_tabs_3" class="tab text-xl" aria-label="Best seller" />
                <div class="tab-content bg-base-100 border-base-300 p-6">
                    <!-- product grid -->
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        <?php foreach ($bestSellers as $product): ?>
                            <!-- product card -->
                            <div class="card bg-base-100 shadow-sm relative w-full rounded-lg overflow-hidden">
                                <a href="/products/detail/<?= $product->getProductid() ?>" class="block">
                                    <div class="image-container relative w-full h-[200px]">
                                        <img src="/<?= htmlspecialchars($product->getAvatarurl() == '' ? DEFAULT_PRODUCT_IMAGE_URL : $product->getAvatarurl()) ?>"
                                            alt="Cake - <?= htmlspecialchars($product->getName()) ?>"
                                            class="absolute inset-0 w-full h-full object-cover" />
                                    </div>
                                    <div class="card-body">
                                        <p class="brand-name"><?= htmlspecialchars($product->getManufacturerName()) ?></p>
                                        <h2 class="product-name"><?= htmlspecialchars($product->getName()) ?></h2>
                                        <p class="price text-error"><?= htmlspecialchars(number_format($product->getPrice(), 0, ',', '.')) ?> VND</p>

                                        <div class="flex items-center">
                                            <p class="sold-amt">Sold: <?= htmlspecialchars($product->getBought()) ?></p>
                                            <button
                                                aria-label="Add <?= htmlspecialchars($product->getName()) ?> to cart"
                                                class="btn btn-soft add-to-cart"
                                                data-product-id="<?= htmlspecialchars($product->getProductid()) ?>"
                                                data-amount="1">
                                                <i class="fa-solid fa-cart-plus"></i>
                                            </button>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <div class="text-center mt-6">
                        <a class="btn btn-lg btn-outline px-8" href="/products?sort=popular">View all</a>
                    </div>
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
                        <strong>You can visit us at our bakery at <?= $address ?> or contact us via <?= $phone ?>.</strong>
                    </p>
                </div>
                <div class="card bg-base-100 w-full max-w-sm shrink-0 shadow-2xl">
                    <form id="contact-form">
                        <div class="card-body">
                            <fieldset class="fieldset">
                                <label class="input rounded-md w-full">
                                    <i class="fa-solid fa-user"></i>
                                    <input name="name" type="text" placeholder="Name" required />
                                </label>

                                <label class="input validator rounded-md w-full">
                                    <i class="fa-solid fa-envelope"></i>
                                    <input name="email" type="email" placeholder="mail@site.com" required />
                                </label>
                                <div class="validator-hint hidden">Enter valid email address</div>

                                <label class="input rounded-md w-full">
                                    <input name="title" type="text" class="tabular-nums" placeholder="Title"
                                        maxlength=" 50" />
                                </label>

                                <label>
                                    <textarea name="message" class="textarea rounded-md w-full" placeholder="Message"></textarea>
                                </label>

                                <button type="submit" class="btn btn-neutral mt-6 w-3/4 mx-auto">Send</button>
                            </fieldset>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <?php require_once __DIR__ . "/../common/footer.php"; ?>

    <script>
        // CAROUSEL ANIMATION
        const carousel = document.getElementById("carousel");
        const dots = document.querySelectorAll(".dot");
        const prevBtn = document.getElementById("prevBtn");
        const nextBtn = document.getElementById("nextBtn");

        let index = 0;
        const totalSlides = carousel.children.length;
        let interval = setInterval(nextSlide, 3000); // Auto-slide every 3 seconds

        function updateCarousel() {
            carousel.style.transform = `translateX(-${index * 100}%)`;
            dots.forEach((dot, i) => dot.classList.toggle("bg-primary", i === index));
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

        // add to cart
        document.addEventListener("DOMContentLoaded", () => {
            document.querySelectorAll(".add-to-cart").forEach(button => {
                button.addEventListener("click", function(e) {
                    e.preventDefault();

                    const productId = this.dataset.productId;
                    const amount = this.dataset.amount || 1;
                    addToCart(productId, amount);
                });
            });
        });


        // send contact message using AJAX
        document.getElementById("contact-form").addEventListener("submit", function(e) {
            e.preventDefault(); // Prevent the default form submission

            const formData = new FormData(this);

            fetch("/contact-message", {
                    method: "POST",
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showAlert("Message sent successfully!", "success");
                        this.reset(); // Reset the form
                    } else {
                        showAlert(data.message || "Failed to send message.", "error");
                    }
                })
                .catch(error => {
                    console.error("Error:", error);
                    showAlert("An error occurred while sending the message.", "error");
                });
        });



        function addToCart(productId, amount = 1) {
            console.log(`Adding product ${productId} with amount ${amount} to cart`);

            fetch(`/cart/add/${productId}?product_id=${productId}&amount=${amount}`, {
                    method: 'GET',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showAlert('Product added to cart successfully!', 'success');
                    } else {
                        showAlert(data.message || 'Failed to add product to cart.', 'error');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showAlert('Cannot add product to cart!. Please login or try again later.', 'error');
                });
        }
    </script>

</body>

</html>