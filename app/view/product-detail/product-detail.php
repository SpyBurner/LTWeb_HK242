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
        <div class="w-full md:w-1/2">
          <div class="carousel rounded-box">
            <div class="carousel-item w-full">
              <img
                src="https://img.daisyui.com/images/stock/photo-1606107557195-0e29a4b5b4aa.webp"
                class="w-full"
                alt="Product Image 1"
              />
            </div>
            <div class="carousel-item w-full">
              <img
                src="https://img.daisyui.com/images/stock/photo-1606107557195-0e29a4b5b4aa.webp"
                class="w-full"
                alt="Product Image 2"
              />
            </div>
            <div class="carousel-item w-full">
              <img
                src="https://img.daisyui.com/images/stock/photo-1606107557195-0e29a4b5b4aa.webp"
                class="w-full"
                alt="Product Image 3"
              />
            </div>
          </div>
        </div>

        <div class="w-full md:w-1/2">
          <h1 class="text-3xl font-bold mb-4">Product Title</h1>
          <p class="text-gray-500 mb-4">Brand: CALBEE</p>
          <p class="text-2xl text-red-700 font-semibold mb-4">139,000đ</p>
          <p class="text-gray-600 mb-6">
            Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do
            eiusmod tempor incididunt ut labore et dolore magna aliqua.
          </p>

          <div class="flex items-center gap-4 mb-6">
            <span class="text-gray-600">Quantity:</span>
            <div class="flex items-center gap-2">
              <button class="btn btn-sm btn-outline">-</button>
              <input
                type="text"
                class="input input-bordered w-16 text-center"
                value="1"
              />
              <button class="btn btn-sm btn-outline">+</button>
            </div>
          </div>

          <button class="btn btn-primary w-full mb-6">
            <i class="fas fa-shopping-cart"></i>
            Add to Cart
          </button>
        </div>
      </div>

      <!-- Product Description -->
      <div class="mt-10 border-t pt-6">
        <h2 class="text-2xl font-bold mb-4">Thông tin chi tiết</h2>
        <ul class="list-disc list-inside text-gray-600">
          <li>Material: 100% Cotton</li>
          <li>Color: Red</li>
          <li>Size: M</li>
          <li>Weight: 0.5kg</li>
          <li>Warranty: 6 months</li>
          <li>Origin: Vietnam</li>
        </ul>
      </div>

      <!-- Rating and Reviews Section -->
      <div class="mt-10 border-t pt-6">
        <h2 class="text-2xl font-bold mb-6">Đánh giá sản phẩm</h2>

        <div class="flex items-center gap-4 mb-6">
          <div class="text-4xl font-bold">4.5</div>
          <div class="rating rating-md">
            <input
              type="radio"
              name="rating-2"
              class="mask mask-star-2 bg-orange-400"
            />
            <input
              type="radio"
              name="rating-2"
              class="mask mask-star-2 bg-orange-400"
              checked
            />
            <input
              type="radio"
              name="rating-2"
              class="mask mask-star-2 bg-orange-400"
            />
            <input
              type="radio"
              name="rating-2"
              class="mask mask-star-2 bg-orange-400"
            />
            <input
              type="radio"
              name="rating-2"
              class="mask mask-star-2 bg-orange-400"
            />
          </div>
          <span class="text-gray-600">(120 đánh giá)</span>
        </div>

        <!-- Rating -->
        <div class="mb-6">
          <h3 class="text-xl font-bold mb-4">Thống kê đánh giá</h3>
          <div class="space-y-2">
            <div class="flex items-center gap-2">
              <span class="w-16">5 sao</span>
              <progress
                class="progress progress-primary w-64"
                value="80"
                max="100"
              ></progress>
              <span class="text-gray-600">80%</span>
            </div>
            <div class="flex items-center gap-2">
              <span class="w-16">4 sao</span>
              <progress
                class="progress progress-primary w-64"
                value="15"
                max="100"
              ></progress>
              <span class="text-gray-600">15%</span>
            </div>
            <div class="flex items-center gap-2">
              <span class="w-16">3 sao</span>
              <progress
                class="progress progress-primary w-64"
                value="3"
                max="100"
              ></progress>
              <span class="text-gray-600">3%</span>
            </div>
            <div class="flex items-center gap-2">
              <span class="w-16">2 sao</span>
              <progress
                class="progress progress-primary w-64"
                value="1"
                max="100"
              ></progress>
              <span class="text-gray-600">1%</span>
            </div>
            <div class="flex items-center gap-2">
              <span class="w-16">1 sao</span>
              <progress
                class="progress progress-primary w-64"
                value="1"
                max="100"
              ></progress>
              <span class="text-gray-600">1%</span>
            </div>
          </div>
        </div>

        <!-- Customer Reviews -->
        <div>
          <h3 class="text-xl font-bold mb-4">Bình luận đánh giá</h3>
          <div class="space-y-6">
            <div class="border-b pb-4">
              <div class="flex items-center gap-2 mb-2">
                <div class="avatar">
                  <div class="w-10 rounded-full">
                    <img
                      src="https://img.daisyui.com/images/stock/photo-1534528741775-53994a69daeb.webp"
                    />
                  </div>
                </div>
                <span class="font-semibold">Người dùng 1</span>
              </div>
              <div class="rating rating-sm mb-2">
                <input
                  type="radio"
                  name="rating-1"
                  class="mask mask-star-2 bg-orange-400"
                />
                <input
                  type="radio"
                  name="rating-1"
                  class="mask mask-star-2 bg-orange-400"
                  checked
                />
                <input
                  type="radio"
                  name="rating-1"
                  class="mask mask-star-2 bg-orange-400"
                />
                <input
                  type="radio"
                  name="rating-1"
                  class="mask mask-star-2 bg-orange-400"
                />
                <input
                  type="radio"
                  name="rating-1"
                  class="mask mask-star-2 bg-orange-400"
                />
              </div>
              <p class="text-gray-600">
                Sản phẩm rất tốt, chất lượng đáng giá tiền!
              </p>
            </div>

            <div class="border-b pb-4">
              <div class="flex items-center gap-2 mb-2">
                <div class="avatar">
                  <div class="w-10 rounded-full">
                    <img
                      src="https://img.daisyui.com/images/stock/photo-1534528741775-53994a69daeb.webp"
                    />
                  </div>
                </div>
                <span class="font-semibold">Người dùng 2</span>
              </div>
              <div class="rating rating-sm mb-2">
                <input
                  type="radio"
                  name="rating-2"
                  class="mask mask-star-2 bg-orange-400"
                />
                <input
                  type="radio"
                  name="rating-2"
                  class="mask mask-star-2 bg-orange-400"
                  checked
                />
                <input
                  type="radio"
                  name="rating-2"
                  class="mask mask-star-2 bg-orange-400"
                />
                <input
                  type="radio"
                  name="rating-2"
                  class="mask mask-star-2 bg-orange-400"
                />
                <input
                  type="radio"
                  name="rating-2"
                  class="mask mask-star-2 bg-orange-400"
                />
              </div>
              <p class="text-gray-600">Giao hàng nhanh, đóng gói cẩn thận.</p>
            </div>
          </div>
        </div>
      </div>

      <!-- Related Products -->
      <div class="mt-10 border-t pt-6">
        <h2 class="text-2xl font-bold mb-6">Sản phẩm liên quan</h2>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
          <div class="card bg-base-100 shadow-sm relative w-full">
            <a href="#">
              <img
                src="https://img.daisyui.com/images/stock/photo-1606107557195-0e29a4b5b4aa.webp"
                alt="product img"
                class="w-full h-32 object-cover"
              />
              <div class="card-body p-2">
                <p
                  class="text-[10px] text-gray-500 font-semibold uppercase tracking-wide"
                >
                  CALBEE
                </p>
                <h2 class="text-xs font-bold text-gray-800 truncate">
                  Related Product 1
                </h2>
                <p class="text-base font-semibold text-primary">139,000đ</p>
                <div class="flex items-center justify-between mt-1">
                  <p class="text-[10px] text-gray-600">
                    Đã bán: <span class="font-semibold">206</span>
                  </p>
                  <button
                    class="btn btn-primary btn-xxs flex items-center gap-1"
                  >
                    <i class="fa-solid fa-cart-plus"></i>
                    <span>Thêm</span>
                  </button>
                </div>
              </div>
            </a>
          </div>

          <div class="card bg-base-100 shadow-sm relative w-full">
            <a href="#">
              <img
                src="https://img.daisyui.com/images/stock/photo-1606107557195-0e29a4b5b4aa.webp"
                alt="product img"
                class="w-full h-32 object-cover"
              />
              <div class="card-body p-2">
                <p
                  class="text-[10px] text-gray-500 font-semibold uppercase tracking-wide"
                >
                  CALBEE
                </p>
                <h2 class="text-xs font-bold text-gray-800 truncate">
                  Related Product 2
                </h2>
                <p class="text-base font-semibold text-primary">159,000đ</p>
                <div class="flex items-center justify-between mt-1">
                  <p class="text-[10px] text-gray-600">
                    Đã bán: <span class="font-semibold">150</span>
                  </p>
                  <button
                    class="btn btn-primary btn-xxs flex items-center gap-1"
                  >
                    <i class="fa-solid fa-cart-plus"></i>
                    <span>Thêm</span>
                  </button>
                </div>
              </div>
            </a>
          </div>
        </div>
      </div>
    </div>

    <?php require_once "../common/footer.php"; ?>

  </body>
</html>
