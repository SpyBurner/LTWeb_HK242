<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin | Product Management</title>

    <?php require_once __DIR__ . "/../common/admin-link.php"; ?>
    <style>
        .product-image {
            max-width: 80px;
            max-height: 80px;
            object-fit: cover;
        }
        .no-products {
            text-align: center;
            padding: 20px;
            color: #6c757d;
        }
    </style>
</head>

<body>
    <div id="app">
        <?php require_once __DIR__ . "/../common/admin-sidebar.php"; ?>

        <div id="main">
            <?php require_once __DIR__ . "/../common/admin-header.php"; ?>

            <!-- Main content -->
            <div class="page-heading">
                <h3>Product Management</h3>
                <?php if (!empty($messages['success'])): ?>
                    <div class="alert alert-success">
                        <?= htmlspecialchars($messages['success']) ?>
                    </div>
                <?php endif; ?>
                <?php if (!empty($messages['error'])): ?>
                    <div class="alert alert-danger">
                        <?= htmlspecialchars($messages['error']) ?>
                    </div>
                <?php endif; ?>
            </div>

            <div id="page-content" class="row justify-content-center">
                <div class="col-12 col-md-10">
                    <!-- Search and filter section -->
                    <form method="get" action="/admin/products" class="mb-4">
                        <div class="row g-3 align-items-center">
                            <div class="col-md-6">
                                <div class="input-group">
                                    <input type="text" name="search" class="form-control" 
                                        placeholder="Search by name or description" 
                                        value="<?= htmlspecialchars($searchTerm ?? '') ?>">
                                    <button class="btn btn-primary" type="submit">
                                        <i class="bi bi-search"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <select class="form-select" name="category">
                                    <option value="">All Categories</option>
                                    <?php foreach ($categories as $category): ?>
                                        <option value="<?= $category->getCateid() ?>" 
                                            <?= ($selectedCategory ?? '') == $category->getCateid() ? 'selected' : '' ?>>
                                            <?= htmlspecialchars($category->getName()) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <a href="/admin/products" class="btn btn-outline-secondary w-100">
                                    Reset
                                </a>
                            </div>
                        </div>
                    </form>

                    <!-- Add product button and product count -->
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div class="text-muted">
                            Total: <?= count($products) ?> products
                        </div>
                        <div>
                            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addProductModal">
                                <i class="bi bi-plus-lg"></i> Add Product
                            </button>
                        </div>
                    </div>

                    <!-- Products table -->
                    <div class="card">
                        <div class="card-body">
                            <?php if (empty($products)): ?>
                                <div class="no-products">
                                    <i class="bi bi-box-seam" style="font-size: 3rem;"></i>
                                    <h5 class="mt-3">No products found</h5>
                                    <p class="text-muted">Try adjusting your search or add a new product</p>
                                </div>
                            <?php else: ?>
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th>ID</th>
                                                <th>Image</th>
                                                <th>Product Name</th>
                                                <th>Brand</th>
                                                <th>Category</th>
                                                <th>Price</th>
                                                <th>Stock</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($products as $product): ?>
                                                <tr>
                                                    <td><?= $product->getProductid() ?></td>
                                                    <td>
                                                        <img src="/<?= htmlspecialchars($product->getAvatarurl() ?: '/assets/repo/product/default-product.jpg') ?>" 
                                                            alt="<?= htmlspecialchars($product->getName()) ?>" 
                                                            class="product-image rounded">
                                                    </td>
                                                    <td>
                                                        <div class="fw-bold"><?= htmlspecialchars($product->getName()) ?></div>
                                                        <small class="text-muted text-truncate">
                                                            <?= htmlspecialchars(substr($product->getDescription(), 0, 50)) ?>...
                                                        </small>
                                                    </td>
                                                    <td><?= htmlspecialchars($product->getManufacturerName()) ?></td>
                                                    <td><?= htmlspecialchars($product->getCategoryName()) ?></td>
                                                    <td class="fw-bold text-primary">
                                                        <?= number_format($product->getPrice(), 0, ',', '.') ?>  VND
                                                    </td>
                                                    <td>
                                                        <span class="badge bg-<?= $product->getStock() > 0 ? 'success' : 'danger' ?>">
                                                            <?= $product->getStock() ?>
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <div class="d-flex gap-2">
                                                            <button class="btn btn-sm btn-outline-primary edit-btn"
                                                                data-bs-toggle="modal" 
                                                                data-bs-target="#editProductModal"
                                                                data-productid="<?= $product->getProductid() ?>"
                                                                data-name="<?= htmlspecialchars($product->getName()) ?>"
                                                                data-price="<?= $product->getPrice() ?>"
                                                                data-description="<?= htmlspecialchars($product->getDescription()) ?>"
                                                                data-mfgid="<?= $product->getMfgid() ?>"
                                                                data-cateid="<?= $product->getCateid() ?>"
                                                                data-stock="<?= $product->getStock() ?>"
                                                                data-avatarurl="/<?= htmlspecialchars($product->getAvatarurl()) ?>">
                                                                <i class="bi bi-pencil"></i>
                                                            </button>
                                                            <form action="/admin/products/delete/<?= $product->getProductid() ?>" 
                                                                method="post" class="d-inline">
                                                                <button type="submit" 
                                                                    class="btn btn-sm btn-outline-danger"
                                                                    onclick="return confirm('Are you sure you want to delete this product?')">
                                                                    <i class="bi bi-trash"></i>
                                                                </button>
                                                            </form>
                                                            <a href="/products/detail/<?= $product->getProductid() ?>" 
                                                                class="btn btn-sm btn-outline-secondary"
                                                                target="_blank">
                                                                <i class="bi bi-eye"></i>
                                                            </a>
                                                        </div>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Add Product Modal -->
        <div class="modal fade" id="addProductModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Add New Product</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="/admin/products/create" method="post" enctype="multipart/form-data">
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Product Name</label>
                                        <input type="text" name="name" class="form-control" required>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Price</label>
                                        <input type="number" name="price" class="form-control" required>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Stock</label>
                                        <input type="number" name="stock" class="form-control" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Brand</label>
                                        <select name="mfgid" class="form-select" required>
                                            <?php foreach ($manufacturers as $manufacturer): ?>
                                                <option value="<?= $manufacturer->getMfgid() ?>">
                                                    <?= htmlspecialchars($manufacturer->getName()) ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Category</label>
                                        <select name="cateid" class="form-select" required>
                                            <?php foreach ($categories as $category): ?>
                                                <option value="<?= $category->getCateid() ?>">
                                                    <?= htmlspecialchars($category->getName()) ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Product Image</label>
                                        <input type="file" name="image" class="form-control">
                                    </div>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Description</label>
                                <textarea name="description" class="form-control" rows="3"></textarea>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-primary">Save Product</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Edit Product Modal -->
        <div class="modal fade" id="editProductModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Product</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form id="editProductForm" method="post" enctype="multipart/form-data">
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Product Name</label>
                                        <input type="text" name="name" id="editName" class="form-control" required>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Price</label>
                                        <input type="number" name="price" id="editPrice" class="form-control" required>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Stock</label>
                                        <input type="number" name="stock" id="editStock" class="form-control" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Brand</label>
                                        <select name="mfgid" id="editMfgid" class="form-select" required>
                                            <?php foreach ($manufacturers as $manufacturer): ?>
                                                <option value="<?= $manufacturer->getMfgid() ?>">
                                                    <?= htmlspecialchars($manufacturer->getName()) ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Category</label>
                                        <select name="cateid" id="editCateid" class="form-select" required>
                                            <?php foreach ($categories as $category): ?>
                                                <option value="<?= $category->getCateid() ?>">
                                                    <?= htmlspecialchars($category->getName()) ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Product Image</label>
                                        <input type="file" name="image" class="form-control">
                                        <div class="mt-2">
                                            <img id="editAvatarPreview" src="" class="product-image">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Description</label>
                                <textarea name="description" id="editDescription" class="form-control" rows="3"></textarea>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-primary">Save Changes</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <?php require_once __DIR__ . "/../common/admin-script.php"; ?>
    <script>
        // Initialize edit modal with product data
        document.querySelectorAll('.edit-btn').forEach(button => {
            button.addEventListener('click', function() {
                const productId = this.getAttribute('data-productid');
                const form = document.getElementById('editProductForm');
                form.action = `/admin/products/edit/${productId}`;
                
                // Fill form with product data
                document.getElementById('editName').value = this.getAttribute('data-name');
                document.getElementById('editPrice').value = this.getAttribute('data-price');
                document.getElementById('editDescription').value = this.getAttribute('data-description');
                document.getElementById('editMfgid').value = this.getAttribute('data-mfgid');
                document.getElementById('editCateid').value = this.getAttribute('data-cateid');
                document.getElementById('editStock').value = this.getAttribute('data-stock');
                
                // Set image preview
                const avatarUrl = this.getAttribute('data-avatarurl');
                const preview = document.getElementById('editAvatarPreview');
                preview.src = avatarUrl || '/assets/images/default-product.png';
                preview.style.display = 'block';
            });
        });

        // Preview image before upload
        document.querySelector('input[name="image"]')?.addEventListener('change', function(e) {
            const preview = document.getElementById('editAvatarPreview');
            if (this.files && this.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
                }
                reader.readAsDataURL(this.files[0]);
            }
        });
    </script>
</body>
</html>