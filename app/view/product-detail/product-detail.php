<!DOCTYPE html>
<html lang="en" data-theme="valentine">
<head>
    <?php require_once __DIR__ . "/../common/head.php"; ?>
    <title>CakeZone | <?= htmlspecialchars($product->getName()) ?></title>

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

<div id="body" class="mx-6 md:w-3/4 md:mx-auto my-10">
    <div class="flex flex-col md:flex-row gap-8">
        <div class="w-full md:w-1/2">
            <div class="carousel rounded-box">
                <div class="carousel-item w-full">
                    <img
                        src="/<?= htmlspecialchars($product->getAvatarurl()) ?>"
                        class="w-full"
                        alt="<?= htmlspecialchars($product->getName()) ?>"
                    />
                </div>
            </div>
        </div>

        <div class="w-full md:w-1/2">
            <h1 class="text-3xl font-bold mb-4"><?= htmlspecialchars($product->getName()) ?></h1>
            <p class="text-gray-500 mb-4">Brand: <?= htmlspecialchars($product->getManufacturerName()) ?></p>
            <p class="text-2xl text-red-700 font-semibold mb-4"><?= number_format($product->getPrice(), 0, ',', '.') ?> VND</p>
            <!-- <p class="text-gray-600 mb-6">
                <?= nl2br(htmlspecialchars($product->getDescription())) ?>
            </p> -->

            <div class="flex items-center gap-4 mb-6">
                <span class="text-gray-600">Quantity:</span>
                <div class="flex items-center gap-2">
                    <button class="btn btn-sm btn-outline quantity-btn minus">-</button>
                    <input
                        type="text"
                        class="input input-bordered w-16 text-center input-quantity"
                        value="1"
                        min="1"
                    />
                    <button class="btn btn-sm btn-outline quantity-btn plus">+</button>
                </div>
            </div>

            <button class="btn btn-primary w-full mb-6" id="addToCartBtn" data-productid="<?= $product->getProductid() ?>">
                <i class="fas fa-shopping-cart"></i>
                Add to Cart
            </button>
        </div>
    </div>

    <div class="mt-10 border-t pt-6">
        <h2 class="text-2xl font-bold mb-4">Detailed Information</h2>
        <ul class="list-disc list-inside text-gray-600">
            <li>Category: <?= htmlspecialchars($product->getCategoryName()) ?></li>
            <li>Brand: <?= htmlspecialchars($product->getManufacturerName()) ?></li>
            <li>Description: <?= nl2br(htmlspecialchars($product->getDescription())) ?></li>
            <li>Average Rating: <?= number_format($product->getAvgrating(), 1) ?>/5</li>
            <li>Stock: <?= $product->getStock() ?> available</li>
            <li>Sold: <?= $product->getBought() ?> items</li>
        </ul>
    </div>

    <div class="mt-10 border-t pt-6">
        <h2 class="text-2xl font-bold mb-6">Product Reviews</h2>
        <div class="flex items-center gap-4 mb-6">
            <div class="text-4xl font-bold"><?= number_format($avgRating, 1) ?></div>
            <div class="rating rating-md">
                <?php for ($i = 1; $i <= 5; $i++): ?>
                    <input
                        type="radio"
                        name="rating-2"
                        class="mask mask-star-2 bg-orange-400"
                        <?= $i == round($avgRating) ? 'checked' : '' ?>
                        disabled
                    />
                <?php endfor; ?>
            </div>
            <span class="text-gray-600">(<?= $ratingCount ?> reviews)</span>
        </div>

        <div class="mb-6">
            <h3 class="text-xl font-bold mb-4">Rating Statistics</h3>
            <div class="space-y-2">
                <?php for ($i = 5; $i >= 1; $i--): ?>
                    <div class="flex items-center gap-2">
                        <span class="w-16"><?= $i ?> stars</span>
                        <progress
                            class="progress progress-primary w-64"
                            value="<?= $ratingCount > 0 ? ($ratingStats[$i] / $ratingCount * 100) : 0 ?>"
                            max="100"
                        ></progress>
                        <span class="text-gray-600"><?= $ratingCount > 0 ? round(($ratingStats[$i] / $ratingCount * 100), 1) : 0 ?>%</span>
                    </div>
                <?php endfor; ?>
            </div>
        </div>

        <div>
            <h3 class="text-xl font-bold mb-4">Review Comments</h3>
            <div class="space-y-6">
                <?php if (empty($reviews)): ?>
                    <p class="text-gray-600">No reviews for this product yet.</p>
                <?php else: ?>
                    <?php foreach ($reviews as $review): ?>
                        <div class="pb-4">
                            <div class="flex items-center gap-2 mb-2">
                                <div class="avatar">
                                    <div class="w-10 rounded-full">
                                        <img src="/<?= htmlspecialchars($review->getAvatarurl()) ?>" alt="<?= htmlspecialchars($review->getUsername()) ?>" />
                                    </div>
                                </div>
                                <span class="font-semibold"><?= htmlspecialchars($review->getUsername()) ?></span>
                            </div>
                            <div class="rating rating-sm mb-2">
                                <?php for ($i = 1; $i <= 5; $i++): ?>
                                    <input
                                        type="radio"
                                        name="rating-<?= $review->getProductid() ?>"
                                        class="mask mask-star-2 bg-orange-400"
                                        <?= $i == $review->getRating() ? 'checked' : '' ?>
                                        disabled
                                    />
                                <?php endfor; ?>
                            </div>
                            <p class="text-gray-600">
                                <?= nl2br(htmlspecialchars($review->getComment())) ?>
                            </p>
                            <p class="text-sm text-gray-500 mt-2">
                                <?= date('d/m/Y', strtotime($review->getRatingDate())) ?>
                            </p>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div class="mt-10 border-t pt-6">
        <h2 class="text-2xl font-bold mb-6">Related Products</h2>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <?php foreach ($relatedProducts as $related): ?>
                <div class="card bg-base-100 shadow-sm relative w-full">
                    <a href="/products/detail/<?= $related->getProductid() ?>">
                        <img
                            src="/<?= htmlspecialchars($related->getAvatarurl()) ?>"
                            alt="<?= htmlspecialchars($related->getName()) ?>"
                            class="w-full h-32 object-cover"
                        />
                        <div class="card-body p-2">
                            <p class="text-[10px] text-gray-500 font-semibold uppercase tracking-wide">
                                <?= htmlspecialchars($related->getManufacturerName()) ?>
                            </p>
                            <h2 class="text-xs font-bold text-gray-800 truncate">
                                <?= htmlspecialchars($related->getName()) ?>
                            </h2>
                            <p class="text-base font-semibold text-primary"><?= number_format($related->getPrice(), 0, ',', '.') ?> VND</p>
                            <div class="flex items-center justify-between mt-1">
                                <p class="text-[10px] text-gray-600">
                                    Sold: <span class="font-semibold"><?= $related->getBought() ?></span>
                                </p>
                                <button class="btn btn-primary btn-xxs flex items-center gap-1" 
                                        onclick="addToCart(<?= $related->getProductid() ?>, 1, event)">
                                    <i class="fa-solid fa-cart-plus"></i>
                                    <span>Add</span>
                                </button>
                            </div>
                        </div>
                    </a>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<?php require_once __DIR__ . "/../common/footer.php"; ?>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const minusBtn = document.querySelector('.quantity-btn.minus');
    const plusBtn = document.querySelector('.quantity-btn.plus');
    const quantityInput = document.querySelector('.input-quantity');
    const min = parseInt(quantityInput.getAttribute('min'));

    minusBtn.addEventListener('click', function(e) {
        e.preventDefault();
        let value = parseInt(quantityInput.value);
        if (value > min) {
            quantityInput.value = value - 1;
        }
    });

    plusBtn.addEventListener('click', function(e) {
        e.preventDefault();
        let value = parseInt(quantityInput.value);
        quantityInput.value = value + 1;
    });

    const addToCartBtn = document.getElementById('addToCartBtn');
    if (addToCartBtn) {
        addToCartBtn.addEventListener('click', function() {
            const productId = this.getAttribute('data-productid');
            const amount = parseInt(quantityInput.value);
            addToCart(productId, amount);
        });
    }
});

function addToCart(productId, amount = 1, event = null) {
    if (event) event.preventDefault();

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
            alert('Product added to cart!');
        } else {
            alert('Error: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while adding to cart');
    });
}
</script>
</body>
</html>