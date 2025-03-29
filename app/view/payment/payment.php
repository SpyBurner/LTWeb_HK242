<!DOCTYPE html>
<html lang="en" data-theme="valentine">
<head>
    <?php require_once "../common/head.php"; ?>
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
        /* .payment-method {
            @apply flex items-center gap-3 p-4 border rounded-lg cursor-pointer hover:border-primary;
        } */
        /* .payment-method.active {
            @apply border-2 border-primary bg-primary/10;
        } */
    </style>
</head>

<body>
    <?php require_once "../common/header.php"; ?>

    <div id="body" class="mx-6 md:w-3/4 md:mx-auto my-10">
        <div class="flex flex-col md:flex-row gap-8">
            <!-- Left Column - Payment Form -->
            <div class="w-full md:w-2/3">
                <h2 class="text-2xl font-bold mb-6">Thông tin thanh toán</h2>

                <!-- Customer Information -->
                <div class="card bg-base-100 shadow-sm mb-6">
                    <div class="card-body">
                        <h3 class="text-lg font-semibold mb-4">Thông tin khách hàng</h3>
                        <div class="space-y-4">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="label">
                                        <span class="label-text">Họ và tên</span>
                                    </label>
                                    <input type="text" class="input input-bordered w-full" value="Nguyễn Văn A" required>
                                </div>
                                <div>
                                    <label class="label">
                                        <span class="label-text">Số điện thoại</span>
                                    </label>
                                    <input type="tel" class="input input-bordered w-full" value="0987654321" required>
                                </div>
                            </div>
                            <div>
                                <label class="label">
                                    <span class="label-text">Email</span>
                                </label>
                                <input type="email" class="input input-bordered w-full" value="nguyenvana@example.com" required>
                            </div>
                            <div>
                                <label class="label">
                                    <span class="label-text">Địa chỉ giao hàng</span>
                                </label>
                                <textarea class="textarea textarea-bordered w-full" rows="2">123 Đường ABC, Quận 1, TP.HCM</textarea>
                            </div>
                            <div>
                                <label class="label">
                                    <span class="label-text">Ghi chú đơn hàng</span>
                                </label>
                                <textarea class="textarea textarea-bordered w-full" rows="2" placeholder="Ghi chú cho người bán..."></textarea>
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
                                <input type="radio" name="payment-method" class="radio radio-primary" checked>
                                <div>
                                    <h4 class="font-medium">Thanh toán khi nhận hàng (COD)</h4>
                                    <p class="text-sm text-gray-500">Thanh toán bằng tiền mặt khi nhận hàng</p>
                                </div>
                            </div>
                            <div class="payment-method" onclick="selectPaymentMethod(this)">
                                <input type="radio" name="payment-method" class="radio radio-primary">
                                <div>
                                    <h4 class="font-medium">Chuyển khoản ngân hàng</h4>
                                    <p class="text-sm text-gray-500">Chuyển khoản qua tài khoản ngân hàng</p>
                                </div>
                            </div>
                            <div class="payment-method" onclick="selectPaymentMethod(this)">
                                <input type="radio" name="payment-method" class="radio radio-primary">
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
                            <!-- Product 1 -->
                            <div class="flex items-center gap-4">
                                <img src="https://img.daisyui.com/images/stock/photo-1606107557195-0e29a4b5b4aa.webp" 
                                     alt="product img" 
                                     class="w-16 h-16 object-cover rounded-lg">
                                <div class="flex-1">
                                    <h4 class="font-medium">Product Title 1</h4>
                                    <p class="text-gray-500">139,000đ × 1</p>
                                </div>
                                <p class="font-semibold">139,000đ</p>
                            </div>
                            <!-- Product 2 -->
                            <div class="flex items-center gap-4">
                                <img src="https://img.daisyui.com/images/stock/photo-1606107557195-0e29a4b5b4aa.webp" 
                                     alt="product img" 
                                     class="w-16 h-16 object-cover rounded-lg">
                                <div class="flex-1">
                                    <h4 class="font-medium">Product Title 2</h4>
                                    <p class="text-gray-500">159,000đ × 1</p>
                                </div>
                                <p class="font-semibold">159,000đ</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column - Order Summary -->
            <div class="w-full md:w-1/3">
                <div class="card bg-base-100 shadow-sm p-6 sticky top-6">
                    <h2 class="text-2xl font-bold mb-6">Tóm tắt đơn hàng</h2>

                    <!-- Order Details -->
                    <div class="space-y-4 mb-6">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Tạm tính:</span>
                            <span class="font-semibold">298,000đ</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Phí vận chuyển:</span>
                            <span class="font-semibold">30,000đ</span>
                        </div>
                        <div class="flex justify-between border-t pt-4">
                            <span class="text-gray-600">Tổng cộng:</span>
                            <span class="text-xl font-bold text-red-700">328,000đ</span>
                        </div>
                    </div>

                    <!-- Terms and Conditions -->
                    <div class="form-control mb-6">
                        <label class="label cursor-pointer justify-start gap-3">
                            <input type="checkbox" checked="checked" class="checkbox checkbox-primary">
                            <span class="label-text">Tôi đồng ý với <a href="#" class="link link-primary">điều khoản</a></span>
                        </label>
                    </div>

                    <!-- Place Order Button -->
                    <button class="btn btn-primary w-full">
                        <i class="fas fa-shopping-bag"></i>
                        Đặt hàng
                    </button>
                </div>
            </div>
        </div>
    </div>

    <?php require_once "../common/footer.php"; ?>

    <script>
        // Payment method selection
        function selectPaymentMethod(element) {
            document.querySelectorAll('.payment-method').forEach(method => {
                method.classList.remove('active');
                method.querySelector('input[type="radio"]').checked = false;
            });
            
            element.classList.add('active');
            element.querySelector('input[type="radio"]').checked = true;
        }
    </script>
</body>
</html>