<!DOCTYPE html>
<html lang="en" data-theme="valentine">
<head>
    <?php require_once __DIR__ . "/../common/head.php"; ?>
    <title>CakeZone | My Orders</title>

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
        .status-pending {
            @apply bg-yellow-100 text-yellow-800;
        }
        .status-delivered {
            @apply bg-green-100 text-green-800;
        }
    </style>
</head>

<body>
    <?php require_once __DIR__ . "/../common/header.php"; ?>

    <div id="body" class="mx-6 md:w-3/4 md:mx-auto my-10">
        <!-- Messages -->
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

        <h2 class="text-2xl font-bold mb-6">My Orders</h2>

        <!-- Filters -->
        <div class="card bg-base-100 shadow-sm mb-6">
            <div class="card-body">
                <div class="flex flex-col md:flex-row gap-4">
                    <div class="form-control flex-1">
                        <input type="text" name="search" placeholder="Search for orders..." 
                               class="input input-bordered w-full" 
                               value="<?= htmlspecialchars($filters['search'] ?? '') ?>">
                    </div>
                    <div class="form-control">
                        <select name="status" class="select select-bordered">
                            <option value="">All statuses</option>
                            <option value="Preparing" <?= $filters['status'] === 'Preparing' ? 'selected' : '' ?>>Preparing</option>
                            <option value="Prepared" <?= $filters['status'] === 'Prepared' ? 'selected' : '' ?>>Prepared</option>
                            <option value="Delivering" <?= $filters['status'] === 'Delivering' ? 'selected' : '' ?>>Delivering</option>
                            <option value="Delivered" <?= $filters['status'] === 'Delivered' ? 'selected' : '' ?>>Delivered</option>
                        </select>
                    </div>
                    <button class="btn btn-primary" onclick="applyFilters()">Filter</button>
                </div>
            </div>
        </div>

        <!-- Orders List -->
        <div class="card bg-base-100 shadow-sm">
            <div class="card-body">
                <?php if (empty($orders)): ?>
                    <p class="text-gray-500">No orders found.</p>
                <?php else: ?>
                    <div class="space-y-4">
                        <?php foreach ($orders as $order): ?>
                            <div class="border rounded-lg p-4 hover:bg-gray-50">
                                <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                                    <div>
                                        <h3 class="text-lg font-semibold">
                                            Order #<?= htmlspecialchars($order->getOrderid()) ?>
                                        </h3>
                                        <p class="text-sm text-gray-500">
                                            Order date: <?= date('d/m/Y', strtotime($order->getOrderdate())) ?>
                                        </p>
                                        <p class="text-sm text-gray-500">
                                            Total cost: <?= number_format($order->getTotalcost() + 30000, 0, ',', '.') ?> VND
                                        </p>
                                    </div>
                                    <div class="flex items-center gap-4">
                                        <span class="badge <?= $order->getStatus() === 'Delivered' ? 'status-delivered' : 'status-pending' ?>">
                                            <?= htmlspecialchars($order->getStatus()) ?>
                                        </span>
                                        <a href="/orders/detail/<?= $order->getOrderid() ?>" 
                                           class="btn btn-outline btn-sm">View details</a>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <?php require_once __DIR__ . "/../common/footer.php"; ?>

    <script>
        function applyFilters() {
            const search = document.querySelector('input[name="search"]').value;
            const status = document.querySelector('select[name="status"]').value;
            let url = '/orders/my-orders';
            const params = new URLSearchParams();
            if (search) params.append('search', search);
            if (status) params.append('status', status);
            if (params.toString()) url += '?' + params.toString();
            window.location.href = url;
        }
    </script>
</body>
</html>