<!DOCTYPE html>
<html lang="en" data-theme="valentine">
<head>
    <?php require_once __DIR__ . "/../common/head.php"; ?>
    <title>CakeZone | Thanh toán</title>

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

        <?php
        // Lấy danh sách liên hệ của khách hàng
        $contactResult = \service\ContactService::findAllByCustomerId($userId);
        if (!$contactResult['success'] || empty($contactResult['data'])): ?>
            <div class="alert alert-warning mb-4">
                Bạn chưa có thông tin liên hệ. Vui lòng <a href="/account/contacts" class="link link-primary">thêm liên hệ</a> trước khi thanh toán.
            </div>
        <?php endif; ?>

        <div class="flex flex-col md:flex-row gap-8">
            <!-- Left Column - Payment Form -->
            <div class="w-full md:w-2/3">
                <h2 class="text-2xl font-bold mb-6">Thông tin thanh toán</h2>

                <!-- Customer Information -->
                <div class="card bg-base-100 shadow-sm mb-6">
                    <div class="card-body">
                        <h3 class="text-lg font-semibold mb-4">Thông tin khách hàng</h3>
                        <div class="space-y-4">
                            <!-- Dropdown for selecting contact -->
                            <div>
                                <label class="label">
                                    <span class="label-text">Chọn thông tin liên hệ</span>
                                </label>
                                <select id="contactId" class="select select-bordered w-full" onchange="loadContactInfo(this)" required>
                                    <option value="">Chọn một liên hệ</option>
                                    <?php
                                    if ($contactResult['success'] && !empty($contactResult['data'])) {
                                        foreach ($contactResult['data'] as $contact) {
                                            echo '<option value="' . htmlspecialchars($contact->getContactId()) . '" 
                                                        data-name="' . htmlspecialchars($contact->getName()) . '" 
                                                        data-phone="' . htmlspecialchars($contact->getPhone()) . '" 
                                                        data-address="' . htmlspecialchars($contact->getAddress()) . '">
                                                        ' . htmlspecialchars($contact->getName()) . ' - ' . htmlspecialchars($contact->getPhone()) . '
                                                  </option>';
                                        }
                                    }
                                    ?>
                                </select>
                            </div>

                            <!-- Display selected contact information -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="label">
                                        <span class="label-text">Họ và tên</span>
                                    </label>
                                    <input type="text" id="name" class="input input-bordered w-full" value="" readonly>
                                </div>
                                <div>
                                    <label class="label">
                                        <span class="label-text">Số điện thoại</span>
                                    </label>
                                    <input type="tel" id="phone" class="input input-bordered w-full" value="" readonly>
                                </div>
                            </div>
                            <div>
                                <label class="label">
                                    <span class="label-text">Email</span>
                                </label>
                                <input type="email" id="email" class="input input-bordered w-full" value="" required>
                            </div>
                            <div>
                                <label class="label">
                                    <span class="label-text">Địa chỉ giao hàng</span>
                                </label>
                                <textarea id="address" class="textarea textarea-bordered w-full" rows="2" readonly></textarea>
                            </div>
                            <div>
                                <label class="label">
                                    <span class="label-text">Ghi chú đơn hàng</span>
                                </label>
                                <textarea id="note" class="textarea textarea-bordered w-full" rows="2" placeholder="Ghi chú cho người bán..."></textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Payment Methods -->
                <div class="card bg-base-100 shadow-sm mb-6">
                    <div class="card-body">
                        <h3 class="text-lg font-semibold mb-4">Phương thức thanh toán</h3>
                        <div class="space-y-3">
                            <div class="payment-method active" onclick="selectPaymentMethod(this)">
                                <input type="radio" name="payment_method" value="COD" class="radio radio-primary" checked>
                                <div>
                                    <h4 class="font-medium">Thanh toán khi nhận hàng (COD)</h4>
                                    <p class="text-sm text-gray-500">Thanh toán bằng tiền mặt khi nhận hàng</p>
                                </div>
                            </div>
                            <div class="payment-method" onclick="selectPaymentMethod(this)">
                                <input type="radio" name="payment_method" value="BankTransfer" class="radio radio-primary">
                                <div>
                                    <h4 class="font-medium">Chuyển khoản ngân hàng</h4>
                                    <p class="text-sm text-gray-500">Chuyển khoản qua tài khoản ngân hàng</p>
                                </div>
                            </div>
                            <div class="payment-method" onclick="selectPaymentMethod(this)">
                                <input type="radio" name="payment_method" value="Momo" class="radio radio-primary">
                                <div>
                                    <h4 class="font-medium">Ví điện tử Momo</h4>
                                    <p class="text-sm text-gray-500">Thanh toán qua ứng dụng Momo</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Order Review -->
                <div class="card bg-base-100 shadow-sm">
                    <div class="card-body">
                        <h3 class="text-lg font-semibold mb-4">Kiểm tra đơn hàng</h3>
                        <div class="space-y-4">
                            <?php foreach ($cart->products as $product): ?>
                                <div class="flex items-center gap-4">
                                    <img src="<?= htmlspecialchars($product['avatarurl']) ?>" 
                                         alt="<?= htmlspecialchars($product['name']) ?>" 
                                         class="w-16 h-16 object-cover rounded-lg">
                                    <div class="flex-1">
                                        <h4 class="font-medium"><?= htmlspecialchars($product['name']) ?></h4>
                                        <p class="text-gray-500"><?= number_format($product['price'], 0, ',', '.') ?> VND × <?= $product['amount'] ?></p>
                                    </div>
                                    <p class="font-semibold"><?= number_format($product['price'] * $product['amount'], 0, ',', '.') ?> VND</p>
                                </div>
                            <?php endforeach; ?>
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
                            <span class="font-semibold"><?= number_format($cart->getTotalcost(), 0, ',', '.') ?> VND</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Phí vận chuyển:</span>
                            <span class="font-semibold">30,000 VND</span>
                        </div>
                        <div class="flex justify-between border-t pt-4">
                            <span class="text-gray-600">Tổng cộng:</span>
                            <span class="text-xl font-bold text-red-700"><?= number_format($cart->getTotalcost() + 30000, 0, ',', '.') ?> VND</span>
                        </div>
                    </div>

                    <div class="form-control mb-6">
                        <label class="label cursor-pointer justify-start gap-3">
                            <input type="checkbox" id="terms" class="checkbox checkbox-primary" checked>
                            <span class="label-text">Tôi đồng ý với <a href="#" class="link link-primary">điều khoản</a></span>
                        </label>
                    </div>

                    <button class="btn btn-primary w-full" onclick="placeOrder(<?= $userId ?>)">
                        <i class="fas fa-shopping-bag"></i>
                        Đặt hàng
                    </button>
                </div>
            </div>
        </div>
    </div>

    <?php require_once __DIR__ . "/../common/footer.php"; ?>

    <script>
        function selectPaymentMethod(element) {
            document.querySelectorAll('.payment-method').forEach(method => {
                method.classList.remove('active');
                method.querySelector('input[type="radio"]').checked = false;
            });
            
            element.classList.add('active');
            element.querySelector('input[type="radio"]').checked = true;
        }

        function loadContactInfo(select) {
            const selectedOption = select.options[select.selectedIndex];
            document.getElementById('name').value = selectedOption.getAttribute('data-name') || '';
            document.getElementById('phone').value = selectedOption.getAttribute('data-phone') || '';
            document.getElementById('address').value = selectedOption.getAttribute('data-address') || '';
        }

        function placeOrder(userId) {
            const contactId = document.getElementById('contactId').value;
            const email = document.getElementById('email').value;
            const note = document.getElementById('note').value;
            const paymentMethod = document.querySelector('input[name="payment_method"]:checked').value;
            const termsAgreed = document.getElementById('terms').checked;

            if (!contactId || !email || !paymentMethod || !termsAgreed) {
                alert('Vui lòng chọn thông tin liên hệ, điền email, chọn phương thức thanh toán và đồng ý với điều khoản!');
                return;
            }

            const formData = new FormData();
            formData.append('userId', userId);
            formData.append('contactId', contactId);
            formData.append('email', email);
            formData.append('note', note);
            formData.append('payment_method', paymentMethod);

            fetch('/checkout/paymentValid', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    window.location.href = `/checkout/confirmation/${data.orderId}`;
                } else {
                    alert('Error: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while placing the order');
            });
        }
    </script>
</body>
</html>