<!DOCTYPE html>
<html lang="en" data-theme="valentine">
<head>
    <?php require_once __DIR__ . "/../common/head.php"; ?>
    <title>CakeZone | Cart</title>

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
    <!-- Display messages -->
    <?php if (!empty($messages['success'])): ?>
        <div class="alert alert-success mb-4">
            <?= htmlspecialchars($messages['success']) ?>
        </div>
    <?php endif; ?>
    <?php if (!empty($messages['error'])): ?>
        <div class="alert alert-error mb-4">
            <?= htmlspecialchars($messages['error']) ?>
        </div>
    <?php endif; ?>

    <div class="flex flex-col md:flex-row gap-8">
        <div class="w-full md:w-2/3">
            <h2 class="text-2xl font-bold mb-6">Giỏ hàng của bạn</h2>

            <?php if (empty($cart) || empty($cart->products)): ?>
                <div class="alert alert-info">
                    Giỏ hàng của bạn đang trống. <a href="/products" class="link">Tiếp tục mua sắm</a>
                </div>
            <?php else: ?>
                <?php foreach ($cart->products as $product): ?>
                    <div class="card bg-base-100 shadow-sm mb-4">
                        <div class="card-body flex flex-col md:flex-row items-center gap-4">
                            <img
                                src="<?= htmlspecialchars($product['avatarurl']) ?>"
                                alt="<?= htmlspecialchars($product['name']) ?>"
                                class="w-24 h-24 object-cover rounded-lg"
                            />
                            <div class="flex-1">
                                <h3 class="text-lg font-semibold"><?= htmlspecialchars($product['name']) ?></h3>
                                <p class="text-gray-500">Brand: <?= htmlspecialchars($product['manufacturer_name']) ?></p>
                                <p class="text-xl text-red-700 font-semibold">
                                    <?= number_format($product['price'], 0, ',', '.') ?> VND
                                </p>
                            </div>
                            <div class="flex items-center gap-4">
                                <div class="flex items-center gap-2">
                                    <button class="btn btn-sm btn-outline <?= $product['amount'] <= 1 ? 'btn-disabled' : '' ?>" 
                                            onclick="reduceFromCart(<?= $product['productid'] ?>)">-</button>
                                    <input
                                        type="text"
                                        class="input input-bordered w-16 text-center"
                                        value="<?= $product['amount'] ?>"
                                        readonly
                                    />
                                    <button class="btn btn-sm btn-outline" 
                                            onclick="updateCart(<?= $product['productid'] ?>)">+</button>
                                </div>
                                <button class="btn btn-error btn-sm" 
                                        onclick="removeFromCart(<?= $product['productid'] ?>)">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

        <!-- Order Summary -->
        <div class="w-full md:w-1/3">
            <div class="card bg-base-100 shadow-sm p-6 sticky top-6">
                <h2 class="text-2xl font-bold mb-6">Tóm tắt đơn hàng</h2>

                <div class="flex justify-between mb-4">
                    <span class="text-gray-600">Tạm tính:</span>
                    <span class="text-xl font-semibold">
                        <?= $cart ? number_format($cart->getTotalcost(), 0, ',', '.') . ' VND' : '0 VND' ?>
                    </span>
                </div>

                <div class="flex justify-between mb-4">
                    <span class="text-gray-600">Phí vận chuyển:</span>
                    <span class="text-xl font-semibold">30,000 VND</span>
                </div>

                <div class="flex justify-between mb-6">
                    <span class="text-gray-600">Tổng cộng:</span>
                    <span class="text-2xl font-bold text-red-700">
                        <?= $cart ? number_format($cart->getTotalcost() + 30000, 0, ',', '.') . ' VND' : '30,000 VND' ?>
                    </span>
                </div>

                <a href="/checkout" class="btn btn-primary w-full <?= empty($cart) || empty($cart->products) ? 'btn-disabled' : '' ?>">
                    <i class="fas fa-shopping-cart"></i>
                    Thanh toán
                </a>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . "/../common/footer.php"; ?>

<script>
function updateCart(productId) {
    fetch(`/cart/add/${productId}?product_id=${productId}&amount=1`, {
        method: 'GET',
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        } else {
            alert('Error: ' + data.message);
        }
    });
}

function reduceFromCart(productId) {
    fetch(`/cart/reduce/${productId}?product_id=${productId}&amount=1`, {
        method: 'GET',
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        } else {
            alert('Error: ' + data.message);
        }
    });
}

function removeFromCart(productId) {
    fetch(`/cart/remove/${productId}?product_id=${productId}`, {
        method: 'GET',
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        } else {
            alert('Error: ' + data.message);
        }
    });
}
</script>

</body>
</html>