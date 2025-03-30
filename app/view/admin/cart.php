<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin | Orders Management</title>

    <?php require_once "../common/admin-link.php"; ?>
    <style>
        .status-preparing {
            background-color: #fff3cd; /* Light yellow for preparing */
        }
        .status-prepared {
            background-color: #d1e7dd; /* Light green for prepared */
        }
        .status-delivering {
            background-color: #cfe2ff; /* Light blue for delivering */
        }
        .status-delivered {
            background-color: #e2e3e5; /* Light gray for delivered */
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
                <h3>Orders Management</h3>
            </div>

            <div id="page-content" class="row justify-content-center">
                <div class="col-12 col-md-10">
                    <!-- Search and filter section -->
                    <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
                        <div class="flex-grow-1">
                            <input type="text" placeholder="Search orders..." class="form-control" id="searchInput">
                        </div>
                        <div>
                            <select class="form-select" id="statusFilter">
                                <option value="">All statuses</option>
                                <option value="Preparing">Preparing</option>
                                <option value="Prepared">Prepared</option>
                                <option value="Delivering">Delivering</option>
                                <option value="Delivered">Delivered</option>
                            </select>
                        </div>
                    </div>

                    <!-- Orders table -->
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead class="table-primary">
                                <tr>
                                    <th>ID</th>
                                    <th>Customer</th>
                                    <th>Order Date</th>
                                    <th>Total</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody id="ordersTable">
                                <tr class="status-preparing">
                                    <td>1</td>
                                    <td>Nguyễn Văn A</td>
                                    <td>2023-10-01</td>
                                    <td>500,000đ</td>
                                    <td>
                                        <select class="form-select form-select-sm" onchange="updateOrderStatus(this, 1)" data-status="Preparing">
                                            <option value="Preparing" selected>Preparing</option>
                                            <option value="Prepared">Prepared</option>
                                            <option value="Delivering">Delivering</option>
                                            <option value="Delivered">Delivered</option>
                                        </select>
                                    </td>
                                    <td>
                                        <button class="btn btn-sm btn-info" onclick="viewOrderDetails(1)">
                                            <i class="bi bi-eye"></i>
                                        </button>
                                    </td>
                                </tr>
                                <tr class="status-prepared">
                                    <td>2</td>
                                    <td>Trần Thị B</td>
                                    <td>2023-10-02</td>
                                    <td>750,000đ</td>
                                    <td>
                                        <select class="form-select form-select-sm" onchange="updateOrderStatus(this, 2)" data-status="Prepared">
                                            <option value="Preparing">Preparing</option>
                                            <option value="Prepared" selected>Prepared</option>
                                            <option value="Delivering">Delivering</option>
                                            <option value="Delivered">Delivered</option>
                                        </select>
                                    </td>
                                    <td>
                                        <button class="btn btn-sm btn-info" onclick="viewOrderDetails(2)">
                                            <i class="bi bi-eye"></i>
                                        </button>
                                    </td>
                                </tr>
                                <tr class="status-delivering">
                                    <td>3</td>
                                    <td>Lê Văn C</td>
                                    <td>2023-10-03</td>
                                    <td>1,000,000đ</td>
                                    <td>
                                        <select class="form-select form-select-sm" onchange="updateOrderStatus(this, 3)" data-status="Delivering">
                                            <option value="Preparing">Preparing</option>
                                            <option value="Prepared">Prepared</option>
                                            <option value="Delivering" selected>Delivering</option>
                                            <option value="Delivered">Delivered</option>
                                        </select>
                                    </td>
                                    <td>
                                        <button class="btn btn-sm btn-info" onclick="viewOrderDetails(3)">
                                            <i class="bi bi-eye"></i>
                                        </button>
                                    </td>
                                </tr>
                                <tr class="status-delivered">
                                    <td>4</td>
                                    <td>Phạm Thị D</td>
                                    <td>2023-10-04</td>
                                    <td>1,200,000đ</td>
                                    <td>
                                        <select class="form-select form-select-sm" onchange="updateOrderStatus(this, 4)" data-status="Delivered">
                                            <option value="Preparing">Preparing</option>
                                            <option value="Prepared">Prepared</option>
                                            <option value="Delivering">Delivering</option>
                                            <option value="Delivered" selected>Delivered</option>
                                        </select>
                                    </td>
                                    <td>
                                        <button class="btn btn-sm btn-info" onclick="viewOrderDetails(4)">
                                            <i class="bi bi-eye"></i>
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- Order Details Modal -->
                    <div class="modal fade" id="orderDetailsModal" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content p-3">
                                <div class="modal-header">
                                    <h5 class="modal-title text-primary fw-bold">Order Details</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body" id="orderDetailsContent">
                                    <!-- Content will be dynamically inserted -->
                                </div>
                                <div class="modal-footer">
                                    <button class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
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
        const statusFilter = document.getElementById("statusFilter");
        const ordersTable = document.getElementById("ordersTable");

        function filterOrders() {
            const searchTerm = searchInput.value.toLowerCase();
            const selectedStatus = statusFilter.value;

            Array.from(ordersTable.rows).forEach((row) => {
                const customerName = row.cells[1].textContent.toLowerCase();
                const orderId = row.cells[0].textContent;
                const orderStatus = row.cells[4].querySelector("select").value;

                const matchesSearch = 
                    customerName.includes(searchTerm) || orderId.includes(searchTerm);
                const matchesStatus = 
                    selectedStatus === "" || orderStatus === selectedStatus;

                row.style.display = matchesSearch && matchesStatus ? "" : "none";
            });
        }

        searchInput.addEventListener("input", filterOrders);
        statusFilter.addEventListener("change", filterOrders);

        function updateOrderStatus(selectElement, orderId) {
            const newStatus = selectElement.value;
            const row = selectElement.closest('tr');
            
            // Remove all status classes
            row.classList.remove('status-preparing', 'status-prepared', 'status-delivering', 'status-delivered');
            
            // Add the new status class
            row.classList.add(`status-${newStatus.toLowerCase()}`);
            
            // Update the data-status attribute
            selectElement.setAttribute('data-status', newStatus);
            
            console.log(`Updated order #${orderId} status to: ${newStatus}`);
            // Here you would typically make an AJAX call to update the status in your database
        }

        function viewOrderDetails(orderId) {
            const orderDetailsContent = document.getElementById("orderDetailsContent");
            orderDetailsContent.innerHTML = `
                <p><strong>Order ID:</strong> ${orderId}</p>
                <p><strong>Customer:</strong> ${document.querySelector(`tr[class*="status-"]:nth-child(${orderId}) td:nth-child(2)`).textContent}</p>
                <p><strong>Order Date:</strong> ${document.querySelector(`tr[class*="status-"]:nth-child(${orderId}) td:nth-child(3)`).textContent}</p>
                <p><strong>Total:</strong> ${document.querySelector(`tr[class*="status-"]:nth-child(${orderId}) td:nth-child(4)`).textContent}</p>
                <p><strong>Status:</strong> ${document.querySelector(`tr[class*="status-"]:nth-child(${orderId}) select`).value}</p>
                <h5 class="fw-bold mt-4">Products:</h5>
                <ul>
                    <li>Product ${orderId} - 139,000đ x 2</li>
                    <li>Product ${orderId+1} - 159,000đ x 1</li>
                </ul>
            `;
            
            // Show the modal using Bootstrap's modal
            const modal = new bootstrap.Modal(document.getElementById('orderDetailsModal'));
            modal.show();
        }
    </script>

    <?php require_once "../common/admin-script.php"; ?>
</body>

</html>