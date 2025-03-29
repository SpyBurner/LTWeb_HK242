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

    <div id="body" class="md:w-full mx-6 md:mx-auto px-6">
      <div class="my-10 rounded-lg flex flex-col md:flex-row gap-6">
        <!-- Filter  -->
        <div class="w-full md:w-1/4 bg-base-100 p-6 rounded-lg shadow-sm">
          <h3 class="text-xl font-bold mb-4">Filters</h3>

          <div class="mb-6">
            <h4 class="font-semibold mb-2">Categories</h4>
            <div class="flex flex-col gap-2">
              <label class="flex items-center gap-2">
                <input type="checkbox" class="checkbox" />
                <span>Category 1</span>
              </label>
              <label class="flex items-center gap-2">
                <input type="checkbox" class="checkbox" />
                <span>Category 2</span>
              </label>
              <label class="flex items-center gap-2">
                <input type="checkbox" class="checkbox" />
                <span>Category 3</span>
              </label>
            </div>
          </div>

          <div>
            <h4 class="font-semibold mb-2">Price Range</h4>
            <div class="flex flex-col gap-2">
              <label class="flex items-center gap-2">
                <input type="checkbox" class="checkbox" />
                <span>Under 100,000đ</span>
              </label>
              <label class="flex items-center gap-2">
                <input type="checkbox" class="checkbox" />
                <span>100,000đ - 200,000đ</span>
              </label>
              <label class="flex items-center gap-2">
                <input type="checkbox" class="checkbox" />
                <span>200,000đ - 500,000đ</span>
              </label>
              <label class="flex items-center gap-2">
                <input type="checkbox" class="checkbox" />
                <span>Over 500,000đ</span>
              </label>
            </div>
          </div>
        </div>

        <!-- Product List -->
        <div class="w-full md:w-3/4">
          <div class="flex justify-between items-center mb-6">
            <h2 class="text-3xl font-bold">PRODUCT LIST</h2>
            <div class="dropdown dropdown-end">
              <div
                tabindex="0"
                role="button"
                class="btn btn-neutral rounded-md"
              >
                Sort by
              </div>
              <ul
                tabindex="0"
                class="dropdown-content menu bg-base-100 rounded-md z-1 w-52 p-2 shadow-sm mt-2"
              >
                <li><a>Price: Low to High</a></li>
                <li><a>Price: High to Low</a></li>
                <li><a>Popularity</a></li>
                <li><a>Newest</a></li>
              </ul>
            </div>
          </div>

          <div
            class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6 gap-4"
          >
            <div
              class="card bg-base-100 shadow-md rounded-lg overflow-hidden w-full"
            >
              <a href="#" class="block">
                <img
                  src="https://img.daisyui.com/images/stock/photo-1606107557195-0e29a4b5b4aa.webp"
                  alt="product img"
                  class="w-full h-32 object-cover"
                />
                <div class="card-body p-3">
                  <p
                    class="text-[10px] text-gray-500 font-semibold uppercase tracking-wide"
                  >
                    CALBEE
                  </p>
                  <h2 class="text-xs font-bold text-gray-800 truncate">
                    Card Title Should Be Longer Than Ever
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

            <div
              class="card bg-base-100 shadow-md rounded-lg overflow-hidden w-full"
            >
              <a href="#" class="block">
                <img
                  src="https://img.daisyui.com/images/stock/photo-1606107557195-0e29a4b5b4aa.webp"
                  alt="product img"
                  class="w-full h-32 object-cover"
                />
                <div class="card-body p-3">
                  <p
                    class="text-[10px] text-gray-500 font-semibold uppercase tracking-wide"
                  >
                    CALBEE
                  </p>
                  <h2 class="text-xs font-bold text-gray-800 truncate">
                    Another Product Title
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
            <div
              class="card bg-base-100 shadow-md rounded-lg overflow-hidden w-full"
            >
              <a href="#" class="block">
                <img
                  src="https://img.daisyui.com/images/stock/photo-1606107557195-0e29a4b5b4aa.webp"
                  alt="product img"
                  class="w-full h-32 object-cover"
                />
                <div class="card-body p-3">
                  <p
                    class="text-[10px] text-gray-500 font-semibold uppercase tracking-wide"
                  >
                    CALBEE
                  </p>
                  <h2 class="text-xs font-bold text-gray-800 truncate">
                    Another Product Title
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
            <div
              class="card bg-base-100 shadow-md rounded-lg overflow-hidden w-full"
            >
              <a href="#" class="block">
                <img
                  src="https://img.daisyui.com/images/stock/photo-1606107557195-0e29a4b5b4aa.webp"
                  alt="product img"
                  class="w-full h-32 object-cover"
                />
                <div class="card-body p-3">
                  <p
                    class="text-[10px] text-gray-500 font-semibold uppercase tracking-wide"
                  >
                    CALBEE
                  </p>
                  <h2 class="text-xs font-bold text-gray-800 truncate">
                    Another Product Title
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
            <div
              class="card bg-base-100 shadow-md rounded-lg overflow-hidden w-full"
            >
              <a href="#" class="block">
                <img
                  src="https://img.daisyui.com/images/stock/photo-1606107557195-0e29a4b5b4aa.webp"
                  alt="product img"
                  class="w-full h-32 object-cover"
                />
                <div class="card-body p-3">
                  <p
                    class="text-[10px] text-gray-500 font-semibold uppercase tracking-wide"
                  >
                    CALBEE
                  </p>
                  <h2 class="text-xs font-bold text-gray-800 truncate">
                    Another Product Title
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
            <div
              class="card bg-base-100 shadow-md rounded-lg overflow-hidden w-full"
            >
              <a href="#" class="block">
                <img
                  src="https://img.daisyui.com/images/stock/photo-1606107557195-0e29a4b5b4aa.webp"
                  alt="product img"
                  class="w-full h-32 object-cover"
                />
                <div class="card-body p-3">
                  <p
                    class="text-[10px] text-gray-500 font-semibold uppercase tracking-wide"
                  >
                    CALBEE
                  </p>
                  <h2 class="text-xs font-bold text-gray-800 truncate">
                    Another Product Title
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
            <div
              class="card bg-base-100 shadow-md rounded-lg overflow-hidden w-full"
            >
              <a href="#" class="block">
                <img
                  src="https://img.daisyui.com/images/stock/photo-1606107557195-0e29a4b5b4aa.webp"
                  alt="product img"
                  class="w-full h-32 object-cover"
                />
                <div class="card-body p-3">
                  <p
                    class="text-[10px] text-gray-500 font-semibold uppercase tracking-wide"
                  >
                    CALBEE
                  </p>
                  <h2 class="text-xs font-bold text-gray-800 truncate">
                    Another Product Title
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

          <!-- Pagination -->
          <div class="flex justify-center mt-10">
            <div class="btn-group">
              <button class="btn btn-outline">«</button>
              <button class="btn btn-outline">1</button>
              <button class="btn btn-outline">2</button>
              <button class="btn btn-outline">3</button>
              <button class="btn btn-outline">4</button>
              <button class="btn btn-outline">»</button>
            </div>
          </div>
        </div>
      </div>
    </div>

    <?php require_once "../common/footer.php"; ?>
  </body>
</html>
