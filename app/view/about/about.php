<?php
use const config\STATIC_IMAGE_URL;
assert(isset($title) && isset($slogan) && isset($aboutUs) && isset($partners) && isset($banner));
?>

<!DOCTYPE html>
<html lang="en" data-theme="valentine">

<head>
    <?php require_once __DIR__ . "/../common/head.php"; ?>
    <title><?= $title ?></title>
</head>

<body>
<?php require_once __DIR__ . "/../common/header.php"; ?>

<div id="body" class="m-6 md:w-3/4 md:mx-auto">
    <!-- Hero Section -->
    <section class="relative w-full h-[500px] bg-cover bg-center"
             style="background-image: url('<?= STATIC_IMAGE_URL . $banner ?>');">
        <div class="absolute inset-0 bg-black opacity-60 flex items-center justify-center">
            <div class="text-center text-white px-4">
                <h1 class="text-5xl font-bold"><?= $slogan ?></h1>
                <!-- <p class="mt-3 text-lg">Crafting delicious cakes with love & passion.</p> -->
            </div>
        </div>
    </section>

    <!-- About Us -->
    <section class="py-16 px-6 md:px-20 bg-white">
        <div class="max-w-5xl mx-auto text-center">
            <h2 class="text-4xl font-semibold">About us</h2>
            <p class="mt-4 text-lg text-gray-600 leading-relaxed">
                <?= $aboutUs ?>
            </p>
        </div>

        <ul class="timeline timeline-vertical mt-8">
            <li>
                <div class="timeline-start">2004</div>
                <div class="timeline-middle">
                    <i class="fa-solid fa-circle-check"></i>
                </div>
                <div class="timeline-end timeline-box">Dropped out</div>
                <hr />
            </li>
            <li>
                <hr />
                <div class="timeline-start">2006</div>
                <div class="timeline-middle">
                    <i class="fa-solid fa-circle-check"></i>
                </div>
                <div class="timeline-end timeline-box">Borrowed money</div>
                <hr />
            </li>
            <li>
                <hr />
                <div class="timeline-start">2008</div>
                <div class="timeline-middle">
                    <i class="fa-solid fa-circle-check"></i>
                </div>
                <div class="timeline-end timeline-box">Opened CakeZone</div>
                <hr />
            </li>
            <li>
                <hr />
                <div class="timeline-start">2020</div>
                <div class="timeline-middle">
                    <i class="fa-solid fa-circle-check"></i>
                </div>
                <div class="timeline-end timeline-box">Closed CakeZone</div>
                <hr />
            </li>
            <li>
                <hr />
                <div class="timeline-start">2022</div>
                <div class="timeline-middle">
                    <i class="fa-solid fa-circle-check"></i>
                </div>
                <div class="timeline-end timeline-box">Returned to HCMUT</div>
            </li>
        </ul>
    </section>

    <!-- Why Choose Us -->
    <section class="py-16 px-6 md:px-20 bg-pink-100">
        <div class="max-w-5xl mx-auto text-center">
            <h2 class="text-4xl font-semibold">Why Choose Us?</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mt-8">
                <div class="p-6 bg-white rounded-lg shadow-md">
                    <i class="fa-solid fa-seedling text-4xl text-pink-600"></i>
                    <h3 class="mt-4 text-xl font-bold">Fresh Ingredients</h3>
                    <p class="mt-2 text-gray-600">We use only the finest organic and natural ingredients for our
                        cakes.</p>
                </div>
                <div class="p-6 bg-white rounded-lg shadow-md">
                    <i class="fa-solid fa-cake-candles text-4xl text-pink-600"></i>
                    <h3 class="mt-4 text-xl font-bold">Custom Creations</h3>
                    <p class="mt-2 text-gray-600">We design and bake cakes tailored to your special occasions.</p>
                </div>
                <div class="p-6 bg-white rounded-lg shadow-md">
                    <i class="fa-solid fa-heart text-4xl text-pink-600"></i>
                    <h3 class="mt-4 text-xl font-bold">Baked with Love</h3>
                    <p class="mt-2 text-gray-600">Every cake is made with love, care, and a passion for baking.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Our Team -->
    <section class="py-16 px-6 md:px-20 bg-white">
        <div class="max-w-5xl mx-auto text-center">
            <h2 class="text-4xl font-semibold">Our Partners</h2>
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mt-8">
                <?php foreach ($partners as $partner): ?>
                    <a href="<?= $partner['url'] ?>" class="p-6 bg-gray-50 rounded-lg shadow-md text-center">
                        <img src="<?= STATIC_IMAGE_URL . $partner['logo'] ?>" class="w-24 h-24 mx-auto rounded-full">
                        <h3 class="mt-4 text-xl font-bold"><?= $partner['name'] ?></h3>
                    </a>
                <?php endforeach; ?>
            </div>
        </div>
    </section>
</div>

<?php require_once __DIR__ . "/../common/footer.php"; ?>

</body>

</html>