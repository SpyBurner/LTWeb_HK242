<!DOCTYPE html>
<html lang="en" data-theme="valentine">
<head>
    <?php require_once "../common/head.php"; ?>
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
  <?php require_once "../common/header.php"; ?>

    <div id="body" class="mx-6 md:w-3/4 md:mx-auto my-10">
      <div class="flex flex-col md:flex-row gap-8">
        <div class="w-full md:w-2/3">
          <h2 class="text-2xl font-bold mb-6">Giỏ hàng của bạn</h2>

          <div class="card bg-base-100 shadow-sm mb-4">
            <div class="card-body flex flex-col md:flex-row items-center gap-4">
              <img
                src="https://img.daisyui.com/images/stock/photo-1606107557195-0e29a4b5b4aa.webp"
                alt="product img"
                class="w-24 h-24 object-cover rounded-lg"
              />
              <div class="flex-1">
                <h3 class="text-lg font-semibold">Product Title 1</h3>
                <p class="text-gray-500">Brand: CALBEE</p>
                <p class="text-xl text-red-700 font-semibold">139,000đ</p>
              </div>
              <div class="flex items-center gap-4">
                <div class="flex items-center gap-2">
                  <button class="btn btn-sm btn-outline">-</button>
                  <input
                    type="text"
                    class="input input-bordered w-16 text-center"
                    value="1"
                  />
                  <button class="btn btn-sm btn-outline">+</button>
                </div>
                <button class="btn btn-error btn-sm">
                  <i class="fas fa-trash"></i>
                </button>
              </div>
            </div>
          </div>

          <!-- Cart Item 2 -->
          <div class="card bg-base-100 shadow-sm mb-4">
            <div class="card-body flex flex-col md:flex-row items-center gap-4">
              <img
                src="https://img.daisyui.com/images/stock/photo-1606107557195-0e29a4b5b4aa.webp"
                alt="product img"
                class="w-24 h-24 object-cover rounded-lg"
              />
              <div class="flex-1">
                <h3 class="text-lg font-semibold">Product Title 2</h3>
                <p class="text-gray-500">Brand: CALBEE</p>
                <p class="text-xl text-red-700 font-semibold">159,000đ</p>
              </div>
              <div class="flex items-center gap-4">
                <div class="flex items-center gap-2">
                  <button class="btn btn-sm btn-outline">-</button>
                  <input
                    type="text"
                    class="input input-bordered w-16 text-center"
                    value="1"
                  />
                  <button class="btn btn-sm btn-outline">+</button>
                </div>
                <button class="btn btn-error btn-sm">
                  <i class="fas fa-trash"></i>
                </button>
              </div>
            </div>
          </div>
        </div>

        <!-- Order Summary -->
        <div class="w-full md:w-1/3">
          <div class="card bg-base-100 shadow-sm p-6">
            <h2 class="text-2xl font-bold mb-6">Tóm tắt đơn hàng</h2>

            <!-- Subtotal -->
            <div class="flex justify-between mb-4">
              <span class="text-gray-600">Tạm tính:</span>
              <span class="text-xl font-semibold">298,000đ</span>
            </div>

            <!-- Shipping -->
            <div class="flex justify-between mb-4">
              <span class="text-gray-600">Phí vận chuyển:</span>
              <span class="text-xl font-semibold">30,000đ</span>
            </div>

            <!-- Total -->
            <div class="flex justify-between mb-6">
              <span class="text-gray-600">Tổng cộng:</span>
              <span class="text-2xl font-bold text-red-700">328,000đ</span>
            </div>

            <!-- Checkout Button -->
            <button class="btn btn-primary w-full">
              <i class="fas fa-shopping-cart"></i>
              Thanh toán
            </button>
          </div>
        </div>
      </div>
    </div>

    <?php require_once "../common/footer.php"; ?>

    
  </body>
</html>
