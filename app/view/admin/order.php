<?php
/** @var array $orders */
/** @var array $statuses */
/** @var string $searchTerm */
/** @var string $selectedStatus */
use const config\MAZER_BASE_URL;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin | Orders Management</title>
    <?php require_once __DIR__ . "/../common/admin-link.php"; ?>
    <style>
        .status-preparing { background-color: #fff3cd; }
        .status-prepared { background-color: #d1e7dd; }
        .status-delivering { background-color: #cfe2ff; }
        .status-delivered { background-color: #e2e3e5; }
    </style>
</head>

<body>
    <div id="app">
        <?php require_once __DIR__ . "/../common/admin-sidebar.php"; ?>
        <div id="main">
            <?php require_once __DIR__ . "/../common/admin-header.php"; ?>
            
            <div class="page-heading">
                <h3>Orders Management</h3>
            </div>

            <div class="page-content">
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-md-6">
                                <input type="text" class="form-control" placeholder="Search orders..." 
                                       id="searchInput" value="<?= htmlspecialchars($searchTerm) ?>">
                            </div>
                            <div class="col-md-6">
                                <select class="form-select" id="statusFilter">
                                    <?php foreach ($statuses as $value => $label): ?>
                                        <option value="<?= $value ?>" <?= $selectedStatus === $value ? 'selected' : '' ?>>
                                            <?= $label ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Customer</th>
                                        <th>Date</th>
                                        <th>Total</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($orders as $order): ?>
                                        <tr class="status-<?= strtolower($order->getStatus()) ?>">
                                            <td><?= $order->getOrderid() ?></td>
                                            <td><?= htmlspecialchars($order->customerName) ?></td>
                                            <td><?= date('d M Y', strtotime($order->getOrderdate())) ?></td>
                                            <td><?= number_format($order->getTotalcost()) ?> VND</td>
                                            <td>
                                                <select class="form-select form-select-sm" 
                                                        onchange="updateOrderStatus(this, <?= $order->getOrderid() ?>)"
                                                        data-status="<?= $order->getStatus() ?>">
                                                    <?php foreach ($statuses as $value => $label): ?>
                                                        <?php if($value !== ''): ?>
                                                            <option value="<?= $value ?>" <?= $order->getStatus() === $value ? 'selected' : '' ?>>
                                                                <?= $label ?>
                                                            </option>
                                                        <?php endif; ?>
                                                    <?php endforeach; ?>
                                                </select>
                                            </td>
                                            <td>
                                                <button class="btn btn-sm btn-outline-primary" 
                                                        onclick="showOrderDetails(<?= $order->getOrderid() ?>)">
                                                    <i class="bi bi-eye"></i> Details
                                                </button>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="orderDetailsModal" tabindex="-1" aria-labelledby="orderDetailsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-primary">
                    <h5 class="modal-title text-white" id="orderDetailsModalLabel">Order #<span id="detailOrderId"></span></h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <p><strong>Customer:</strong> <span id="detailCustomer"></span></p>
                            <p><strong>Contact ID:</strong> <span id="detailContactId"></span></p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Order Date:</strong> <span id="detailOrderDate"></span></p>
                            <p><strong>Status:</strong> <span id="detailStatus" class="badge"></span></p>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead class="bg-light">
                                <tr>
                                    <th>Product</th>
                                    <th>Unit Price</th>
                                    <th>Quantity</th>
                                    <th>Subtotal</th>
                                </tr>
                            </thead>
                            <tbody id="detailProducts"></tbody>
                            <tfoot class="table-primary">
                                <tr>
                                    <th colspan="3" class="text-end">Total</th>
                                    <th id="detailTotal"></th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Search and filter functionality
        const searchInput = document.getElementById('searchInput');
        const statusFilter = document.getElementById('statusFilter');
        
        function applyFilters() {
            const searchTerm = searchInput.value.toLowerCase();
            const selectedStatus = statusFilter.value;
            
            document.querySelectorAll('tbody tr').forEach(row => {
                const customerName = row.cells[1].textContent.toLowerCase();
                const orderId = row.cells[0].textContent;
                const status = row.cells[4].querySelector('select').value;
                
                const matchesSearch = customerName.includes(searchTerm) || orderId.includes(searchTerm);
                const matchesStatus = selectedStatus === '' || status === selectedStatus;
                
                row.style.display = matchesSearch && matchesStatus ? '' : 'none';
            });
        }
        
        searchInput.addEventListener('input', applyFilters);
        statusFilter.addEventListener('change', applyFilters);
    });

    function showOrderDetails(orderId) {
        try {
            const orders = <?= json_encode(array_map(function($order) {
                return [
                    'orderid' => $order->getOrderid(),
                    'status' => $order->getStatus(),
                    'totalcost' => $order->getTotalcost(),
                    'orderdate' => $order->getOrderdate(),
                    'customerName' => $order->customerName,
                    'contactid' => $order->getContactid(),
                    'products' => $order->products
                ];
            }, $orders)) ?>;
            
            const order = orders.find(o => o.orderid == orderId);
            
            if (!order) {
                console.error('Order not found');
                return;
            }

            // Fill modal content
            document.getElementById('detailOrderId').textContent = order.orderid;
            document.getElementById('detailCustomer').textContent = order.customerName;
            document.getElementById('detailContactId').textContent = order.contactid;
            document.getElementById('detailOrderDate').textContent = new Date(order.orderdate).toLocaleString();
            
            const statusBadge = document.getElementById('detailStatus');
            statusBadge.textContent = order.status;
            statusBadge.className = 'badge bg-' + 
                (order.status === 'Delivered' ? 'success' : 
                 order.status === 'Preparing' ? 'warning' : 
                 order.status === 'Delivering' ? 'info' : 'primary');

            // Fill products table
            const productsBody = document.getElementById('detailProducts');
            productsBody.innerHTML = '';
            
            order.products.forEach(product => {
                const subtotal = product.price * product.amount;
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td>
                        <div class="d-flex align-items-center">
                            <img src="${product.avatarurl || ''}" width="40" height="40" class="rounded-circle me-2" 
                                 onerror="this.src='<?php echo MAZER_BASE_URL ?>/static/images/default-image.jpg'">
                            <div>
                                <h6 class="mb-0">${product.name || 'Unknown Product'}</h6>
                                <small class="text-muted">${product.description || 'No description'}</small>
                            </div>
                        </div>
                    </td>
                    <td>${Number(product.price || 0).toLocaleString()} VND</td>
                    <td>${product.amount || 0}</td>
                    <td>${Number(subtotal || 0).toLocaleString()} VND</td>
                `;
                productsBody.appendChild(row);
            });
            
            document.getElementById('detailTotal').textContent = 
                Number(order.totalcost || 0).toLocaleString() + ' VND';

            // Show modal using Bootstrap
            const modalElement = document.getElementById('orderDetailsModal');
            const modal = new bootstrap.Modal(modalElement, {
                keyboard: true,
                backdrop: 'static'
            });
            modal.show();

        } catch (error) {
            console.error('Error showing order details:', error);
            alert('Error loading order details');
        }
    }

    async function updateOrderStatus(selectElement, orderId) {
    const newStatus = selectElement.value;
    const row = selectElement.closest('tr');
    const originalStatus = selectElement.dataset.status;

    // Cập nhật giao diện ngay lập tức (tạm thời)
    row.classList.remove(`status-${originalStatus.toLowerCase()}`);
    row.classList.add(`status-${newStatus.toLowerCase()}`);

    try {
        // Gửi yêu cầu cập nhật trạng thái qua fetch
        const response = await fetch('/admin/order/update-status', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                orderId: orderId,
                status: newStatus
            })
        });

        // Kiểm tra phản hồi từ server
        if (!response.ok) {
            throw new Error(`Server responded with status: ${response.status}`);
        }

        const result = await response.json();

        // Kiểm tra kết quả từ server
        if (!result.success) {
            throw new Error(result.message || 'Unknown error occurred');
        }

        // Cập nhật trạng thái gốc nếu thành công
        selectElement.dataset.status = newStatus;
        console.log(`Order ${orderId} status updated to ${newStatus}`);
    } catch (error) {
        // Xử lý lỗi: khôi phục trạng thái ban đầu
        console.error('Failed to update order status:', error.message);
        
        // Hiển thị thông báo lỗi thân thiện hơn
        alert(`Không thể cập nhật trạng thái: ${error.message}. Vui lòng thử lại.`);

        // Khôi phục giao diện và giá trị select về trạng thái cũ
        row.classList.remove(`status-${newStatus.toLowerCase()}`);
        row.classList.add(`status-${originalStatus.toLowerCase()}`);
        selectElement.value = originalStatus;
    }
}
    </script>

    <!-- Mazer Scripts -->
    <?php require_once __DIR__ . "/../common/admin-script.php"; ?>
</body>
</html>