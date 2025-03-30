<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin | Product Management</title>

    <?php require_once "../common/admin-link.php"; ?>
    <style>
        .product-image {
            max-width: 80px;
            max-height: 80px;
            object-fit: cover;
        }
    </style>
</head>

<body>
    <div id="app">
        <?php require_once "../common/admin-sidebar.php"; ?>

        <div id="main">
            <?php require_once "../common/admin-header.php"; ?>

            <!-- Main content -->
            <div class="page-heading">
                <h3>Product Management</h3>
            </div>

            <div id="page-content" class="row justify-content-center">
                <div class="col-12 col-md-10">
                    <!-- Search and filter section -->
                    <!-- <div class="row mb-3">
                        <div class="col-md-6 mb-2">
                            <input type="text" placeholder="Search products..." class="form-control" id="searchInput">
                        </div>
                        <div class="col-md-6 mb-2">
                            <select class="form-select" id="categoryFilter">
                                <option value="">All categories</option>
                                <option value="Category 1">Category 1</option>
                                <option value="Category 2">Category 2</option>
                                <option value="Category 3">Category 3</option>
                            </select>
                        </div>
                    </div> -->
                    <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
                        <div class="flex-grow-1">
                            <input type="text" placeholder="Search products..." class="form-control" id="searchInput">
                        </div>
                        <div>
                            <select class="form-select" id="categoryFilter">
                                <option value="">All categories</option>
                                <option value="Category 1">Category 1</option>
                                <option value="Category 2">Category 2</option>
                                <option value="Category 3">Category 3</option>
                                <option value="Category 4">Category 4</option>
                            </select>
                        </div>
                    </div>

                    <!-- Add product button -->
                    <div class="d-flex justify-content-end mb-3">
                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addProductModal">
                            <i class="bi bi-plus"></i> Add Product
                        </button>
                    </div>

                    <!-- Products table -->
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead class="table-primary">
                                <tr>
                                    <th>ID</th>
                                    <th>Image</th>
                                    <th>Product Name</th>
                                    <th>Brand</th>
                                    <th>Category</th>
                                    <th>Price</th>
                                    <th>Quantity</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody id="productsTable">
                                <!-- Product 1 -->
                                <tr>
                                    <td>1</td>
                                    <td>
                                        <img src="https://img.daisyui.com/images/stock/photo-1606107557195-0e29a4b5b4aa.webp" alt="Product 1" class="product-image">
                                    </td>
                                    <td>Product Title 1</td>
                                    <td>CALBEE</td>
                                    <td>Category 1</td>
                                    <td>139,000đ</td>
                                    <td>50</td>
                                    <td>
                                        <button class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#editProductModal">
                                            <i class="bi bi-pencil"></i>
                                        </button>
                                        <button class="btn btn-sm btn-danger ms-2" onclick="deleteProduct(1)">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                                <!-- Product 2 -->
                                <tr>
                                    <td>2</td>
                                    <td>
                                    <img src="https://img.daisyui.com/images/stock/photo-1606107557195-0e29a4b5b4aa.webp" alt="Product 1" class="product-image">
                                    </td>
                                    <td>Product Title 2</td>
                                    <td>CALBEE</td>
                                    <td>Category 2</td>
                                    <td>159,000đ</td>
                                    <td>30</td>
                                    <td>
                                        <button class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#editProductModal">
                                            <i class="bi bi-pencil"></i>
                                        </button>
                                        <button class="btn btn-sm btn-danger ms-2" onclick="deleteProduct(2)">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- Add Product Modal -->
                    <div class="modal fade" id="addProductModal" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Add New Product</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body">
                                    <form>
                                        <div class="mb-3">
                                            <label class="form-label">Product Name</label>
                                            <input type="text" class="form-control" placeholder="Enter product name">
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Brand</label>
                                            <input type="text" class="form-control" placeholder="Enter brand">
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Category</label>
                                            <select class="form-select">
                                                <option value="Category 1">Category 1</option>
                                                <option value="Category 2">Category 2</option>
                                                <option value="Category 3">Category 3</option>
                                            </select>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Price</label>
                                            <input type="number" class="form-control" placeholder="Enter price">
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Quantity</label>
                                            <input type="number" class="form-control" placeholder="Enter quantity">
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Image</label>
                                            <input type="file" class="form-control">
                                        </div>
                                    </form>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-primary">Save</button>
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Edit Product Modal -->
                    <div class="modal fade" id="editProductModal" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Edit Product</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body">
                                    <form>
                                        <div class="mb-3">
                                            <label class="form-label">Product Name</label>
                                            <input type="text" class="form-control" value="Product Title 1">
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Brand</label>
                                            <input type="text" class="form-control" value="CALBEE">
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Category</label>
                                            <select class="form-select">
                                                <option value="Category 1" selected>Category 1</option>
                                                <option value="Category 2">Category 2</option>
                                                <option value="Category 3">Category 3</option>
                                            </select>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Price</label>
                                            <input type="number" class="form-control" value="139000">
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Quantity</label>
                                            <input type="number" class="form-control" value="50">
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Image</label>
                                            <input type="file" class="form-control">
                                            <small class="text-muted">Current image:</small>
                                            <img src="../img/product-placeholder.jpg" alt="Current product" class="product-image mt-2">
                                        </div>
                                    </form>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-primary">Save Changes</button>
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Search and Filter functionality
        const searchInput = document.getElementById("searchInput");
        const categoryFilter = document.getElementById("categoryFilter");
        const productsTable = document.getElementById("productsTable");

        function filterProducts() {
            const searchTerm = searchInput.value.toLowerCase();
            const selectedCategory = categoryFilter.value;

            Array.from(productsTable.rows).forEach((row) => {
                const productName = row.cells[2].textContent.toLowerCase();
                const productCategory = row.cells[4].textContent;

                const matchesSearch = productName.includes(searchTerm);
                const matchesCategory = 
                    selectedCategory === "" || productCategory === selectedCategory;

                row.style.display = matchesSearch && matchesCategory ? "" : "none";
            });
        }

        searchInput.addEventListener("input", filterProducts);
        categoryFilter.addEventListener("change", filterProducts);

        function deleteProduct(productId) {
            if (confirm('Are you sure you want to delete this product?')) {
                // Here you would typically make an AJAX call to delete the product
                console.log(`Product ${productId} deleted`);
                // Then refresh the table or remove the row
            }
        }

        // Initialize the filter on page load
        filterProducts();
    </script>

    <?php require_once "../common/admin-script.php"; ?>
</body>

</html>