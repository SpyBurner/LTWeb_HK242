<!DOCTYPE html>
<html lang="en" data-theme="valentine">
<head>
    <?php require_once __DIR__ . "/../common/head.php"; ?>
    <title>CakeZone | Products</title>

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

<div id="body" class="md:w-full mx-6 md:mx-auto px-6">
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

    <form method="get" action="/products">
        <div class="my-10 rounded-lg flex flex-col md:flex-row gap-6">
            <!-- Filter Section -->
            <div class="w-full md:w-1/4 bg-base-100 p-6 rounded-lg shadow-sm">
                <h3 class="text-xl font-bold mb-4">Filters</h3>
                
                <!-- Search Filter -->
                <div class="mb-6">
                    <h4 class="font-semibold mb-2">Search</h4>
                    <input type="text" name="search" class="input input-bordered w-full" 
                           placeholder="Search products..." 
                           value="<?= htmlspecialchars($filters['search'] ?? '') ?>">
                </div>
                
                <!-- Category Filter -->
                <div class="mb-6">
                    <h4 class="font-semibold mb-2">Categories</h4>
                    <select name="category" class="select select-bordered w-full">
                        <option value="">All Categories</option>
                        <?php foreach ($categories as $category): ?>
                            <option value="<?= $category->getCateid() ?>" 
                                <?= ($filters['category'] ?? '') == $category->getCateid() ? 'selected' : '' ?>>
                                <?= htmlspecialchars($category->getName()) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <!-- Manufacturer Filter -->
                <div class="mb-6">
                    <h4 class="font-semibold mb-2">Manufacturers</h4>
                    <select name="manufacturer" class="select select-bordered w-full">
                        <option value="">All Manufacturers</option>
                        <?php foreach ($manufacturers as $manufacturer): ?>
                            <option value="<?= $manufacturer->getMfgid() ?>" 
                                <?= ($filters['manufacturer'] ?? '') == $manufacturer->getMfgid() ? 'selected' : '' ?>>
                                <?= htmlspecialchars($manufacturer->getName()) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <!-- Price Range Filter -->
                <div class="mb-6">
                    <h4 class="font-semibold mb-2">Price Range</h4>
                    <select name="price_range" class="select select-bordered w-full">
                        <option value="">All Prices</option>
                        <option value="under_100k" <?= ($filters['price_range'] ?? '') === 'under_100k' ? 'selected' : '' ?>>Under 100,000 VND</option>
                        <option value="100k_to_200k" <?= ($filters['price_range'] ?? '') === '100k_to_200k' ? 'selected' : '' ?>>100,000đ - 200,000 VND</option>
                        <option value="200k_to_500k" <?= ($filters['price_range'] ?? '') === '200k_to_500k' ? 'selected' : '' ?>>Over 200,000đ - 500,000 VND</option>
                        <option value="over_500k" <?= ($filters['price_range'] ?? '') === 'over_500k' ? 'selected' : '' ?>>Over 500,000 VND</option>
                    </select>
                </div>
                
                <!-- Filter Button -->
                <button type="submit" class="btn btn-primary w-full">Apply Filters</button>
                <a href="/products" class="btn btn-outline w-full mt-2">Reset Filters</a>
            </div>
            
            <!-- Product List Section -->
            <div class="w-full md:w-3/4">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-3xl font-bold">PRODUCT LIST</h2>
                    <div class="dropdown dropdown-end">
                        <div tabindex="0" role="button" class="btn btn-neutral rounded-md">
                            Sort by: <?= ucfirst(str_replace('_', ' ', $filters['sort'] ?? 'newest')) ?>
                        </div>
                        <ul tabindex="0" class="dropdown-content menu bg-base-100 rounded-md z-1 w-52 p-2 shadow-sm mt-2">
                            <li><a href="?sort=newest<?= $this->getQueryString(['sort', 'page']) ?>">Newest</a></li>
                            <li><a href="?sort=price_asc<?= $this->getQueryString(['sort', 'page']) ?>">Price: Low to High</a></li>
                            <li><a href="?sort=price_desc<?= $this->getQueryString(['sort', 'page']) ?>">Price: High to Low</a></li>
                            <li><a href="?sort=popular<?= $this->getQueryString(['sort', 'page']) ?>">Popularity</a></li>
                            <li><a href="?sort=top_rated<?= $this->getQueryString(['sort', 'page']) ?>">Top rated</a></li>
                        </ul>
                    </div>
                </div>
                
                <?php if (!empty($error)): ?>
                    <div class="alert alert-error mb-4">
                        <?= htmlspecialchars($error) ?>
                    </div>
                <?php endif; ?>
                
                <?php if (empty($products)): ?>
                    <div class="alert alert-info">
                        No products found matching your criteria.
                    </div>
                <?php else: ?>
                    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6 gap-4">
                        <?php foreach ($products as $product): ?>
                            <div class="card bg-base-100 shadow-md rounded-lg overflow-hidden w-full">
                                <a href="/products/detail/<?= $product->getProductid() ?>" class="block">
                                    <img src="/<?= htmlspecialchars($product->getAvatarurl()) ?>" 
                                         alt="<?= htmlspecialchars($product->getName()) ?>" 
                                         class="w-full h-32 object-cover" />
                                    <div class="card-body p-3">
                                        <p class="text-[10px] text-gray-500 font-semibold uppercase tracking-wide">
                                            <?= htmlspecialchars($product->getManufacturerName() ?? 'Unknown') ?>
                                        </p>
                                        <p class="text-sm font-semibold text-gray-800 line-clamp-2 hover:text-primary">
    <?= htmlspecialchars($product->getName()) ?>
</p>
</h2>
                                        <p class="text-base font-semibold text-primary">
                                            <?= number_format($product->getPrice(), 0, ',', '.') ?> VND
                                        </p>
                                        <div class="flex items-center justify-between mt-1">
                                            <p class="text-[10px] text-gray-600">
                                                Sold: <span class="font-semibold"><?= $product->getBought() ?></span>
                                            </p>
                                            <button class="btn btn-primary btn-xxs flex items-center gap-1" 
        onclick="addToCart(<?= $product->getProductid() ?>)">
    <i class="fa-solid fa-cart-plus"></i>
    <span>Add</span>
</button>

<script>
function addToCart(productId, amount = 1, event = null) {
    if (event) event.preventDefault();

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
                                        </div>
                                    </div>
                                </a>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    
                    <!-- Pagination -->
                    <?php if ($totalPages > 1): ?>
                        <div class="flex justify-center mt-10">
                            <div class="btn-group">
                                <?php if ($currentPage > 1): ?>
                                    <a href="?page=<?= $currentPage - 1 ?><?= $this->getQueryString(['page']) ?>" 
                                       class="btn btn-outline">«</a>
                                <?php endif; ?>
                                
                                <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                                    <a href="?page=<?= $i ?><?= $this->getQueryString(['page']) ?>" 
                                       class="btn btn-outline <?= $currentPage == $i ? 'btn-active' : '' ?>">
                                        <?= $i ?>
                                    </a>
                                <?php endfor; ?>
                                
                                <?php if ($currentPage < $totalPages): ?>
                                    <a href="?page=<?= $currentPage + 1 ?><?= $this->getQueryString(['page']) ?>" 
                                       class="btn btn-outline">»</a>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
        </div>
    </form>
</div>

<?php require_once __DIR__ . "/../common/footer.php"; ?>

<script>
// Add to cart functionality
document.querySelectorAll('.add-to-cart').forEach(button => {
    button.addEventListener('click', function(e) {
        e.preventDefault();
        const productId = this.getAttribute('data-product-id');
        
        fetch('/cart/add/' + productId, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
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
        });
    });
});
</script>

</body>
</html>