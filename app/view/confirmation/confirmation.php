<!DOCTYPE html>
<html lang="en" data-theme="valentine">
<head>
    <?php require_once __DIR__ . "/../common/head.php"; ?>
    <title>CakeZone | Xác nhận đơn hàng</title>

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
        <!-- Success Message -->
        <?php if (!empty($messages['success'])): ?>
            <div class="alert alert-success mb-6">
                <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                <span><?= htmlspecialchars($messages['success']) ?></span>
            </div>
        <?php endif; ?>
        <?php if (!empty($messages['error'])): ?>
            <div class="alert alert-error mb-6">
                <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                <span><?= htmlspecialchars($messages['error']) ?></span>
            </div>
        <?php endif; ?>

        <div class="flex flex-col md:flex-row gap-8">
            <!-- Left Column - Order Details -->
            <div class="w-full md:w-2/3">
                <h2 class="text-2xl font-bold mb-6">Xác nhận đơn hàng #<?= htmlspecialchars($order->getOrderid()) ?></h2>

                <!-- Order Information -->
                <div class="card bg-base-100 shadow-sm mb-6">
                    <div class="card-body">
                        <h3 class="text-lg font-semibold mb-4">Thông tin đơn hàng</h3>
                        <div class="space-y-2">
                            <p><strong>Ngày đặt hàng:</strong> <?= date('d/m/Y H:i:s', strtotime($order->getOrderdate())) ?></p>
                            <p><strong>Trạng thái:</strong> <?= htmlspecialchars($order->getStatus()) ?></p>
                            <p><strong>Tổng tiền:</strong> <?= number_format($order->getTotalcost(), 0, ',', '.') ?>đ</p>
                        </div>
                    </div>
                </div>

                <!-- Products -->
                <div class="card bg-base-100 shadow-sm mb-6">
                    <div class="card-body">
                        <h3 class="text-lg font-semibold mb-4">Sản phẩm trong đơn hàng</h3>
                        <div class="space-y-4">
                            <?php foreach ($order->products as $product): ?>
                                <div class="flex items-center gap-4">
                                    <img src="<?= htmlspecialchars($product['avatarurl']) ?>" 
                                         alt="<?= htmlspecialchars($product['name']) ?>" 
                                         class="w-16 h-16 object-cover rounded-lg">
                                    <div class="flex-1">
                                        <h4 class="font-medium"><?= htmlspecialchars($product['name']) ?></h4>
                                        <p class="text-gray-500"><?= number_format($product['price'], 0, ',', '.') ?>đ × <?= $product['amount'] ?></p>
                                    </div>
                                    <p class="font-semibold"><?= number_format($product['price'] * $product['amount'], 0, ',', '.') ?>đ</p>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>

                <!-- Contact Information -->
                <div class="card bg-base-100 shadow-sm">
                    <div class="card-body">
                        <h3 class="text-lg font-semibold mb-4">Thông tin liên hệ</h3>
                        <div class="space-y-2">
                            <p><strong>Họ và tên:</strong> <?= htmlspecialchars($contact->getName()) ?></p>
                            <p><strong>Số điện thoại:</strong> <?= htmlspecialchars($contact->getPhone()) ?></p>
                            <p><strong>Địa chỉ giao hàng:</strong> <?= htmlspecialchars($contact->getAddress()) ?></p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column - Order Summary -->
            <div class="w-full md:w-1/3">
                <div class="card bg-base-100 shadow-sm p-6 sticky top-6">
                    <h2 class="text-2xl font-bold mb-6">Tóm tắt đơn hàng</h2>

                    <div class="space-y-4 mb-6">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Tạm tính:</span>
                            <span class="font-semibold"><?= number_format($order->getTotalcost(), 0, ',', '.') ?>đ</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Phí vận chuyển:</span>
                            <span class="font-semibold">30,000đ</span>
                        </div>
                        <div class="flex justify-between border-t pt-4">
                            <span class="text-gray-600">Tổng cộng:</span>
                            <span class="text-xl font-bold text-red-700"><?= number_format($order->getTotalcost() + 30000, 0, ',', '.') ?>đ</span>
                        </div>
                    </div>

                    <a href="/products" class="btn btn-primary w-full">
                        <i class="fas fa-shopping-bag"></i>
                        Tiếp tục mua sắm
                    </a>
                </div>
            </div>
        </div>
    </div>

    <?php require_once __DIR__ . "/../common/footer.php"; ?>
</body>
</html>