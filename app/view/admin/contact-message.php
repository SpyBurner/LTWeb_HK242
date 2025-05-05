<?php
assert(isset($title));
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?></title>

    <?php require_once __DIR__ . "/../common/admin-link.php"; ?>
</head>

<body>
    <div id="app">
        <?php require_once __DIR__ . "/../common/admin-sidebar.php"; ?>

        <div id="main">
            <?php require_once __DIR__ . "/../common/admin-header.php"; ?>

            <!-- put `the main content here -->
            <div class="page-heading">
                <h3>Contact Messages</h3>
            </div>

            <div id="page-content" class="row justify-content-center">
                <div class="col-12 col-md-10">
                    <!-- Filter Section -->
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <label class="fw-semibold me-2">Filter by Status:</label>
                        <select id="statusFilter" onchange="renderMessages()" class="form-select w-auto bg-primary text-white">
                            <!-- Options will be dynamically inserted -->
                        </select>
                    </div>
        
                    <!-- Messages Table -->
                    <div class="table-responsive border border-secondary rounded">
                        <table class="table table-bordered mb-0">
                            <thead class="table-primary">
                                <tr>
                                    <th>#</th>
                                    <th>Time</th>
                                    <th>Email</th>
                                    <th>Title</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody id="messagesTable">
                                <!-- Messages will be dynamically inserted -->
                            </tbody>
                        </table>
                    </div>

                    <div id="pagination" class="mt-3 text-center"></div>
        
                    <!-- Message View Modal -->
                    <div class="modal fade" id="messageModal" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content p-3">
                                <div class="modal-header">
                                    <h5 class="modal-title text-primary fw-bold">Message Details</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body">
                                    <p><strong>Status:</strong> <span id="modalStatus"></span></p>
                                    <p><strong>Name:</strong> <span id="modalName"></span></p>
                                    <p><strong>Email:</strong> <span id="modalEmail"></span></p>
                                    <p><strong>Title:</strong> <span id="modalPhone"></span></p>
                                    <p><strong>Message:</strong></p>
                                    <div id="modalMessage" class="bg-light p-3 rounded"></div>
                                </div>
                                <div class="modal-footer">
<!--                                    <button id="markReadBtn" class="btn btn-outline-info me-2" onclick="markAsRead()">Mark as Read</button>-->
                                    <button id="markRepliedBtn" class="btn btn-outline-success" onclick="markAsReplied()">Mark as Replied</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Contact message table -->
    <script>
        let currentMessageId = null;
        let currentPage = 1;
        let currentFilter = "All";

        function fetchMessages() {
            fetch(`/admin/contact-message/fetch?page=${currentPage}&filter=${currentFilter}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        console.log(data);
                        renderMessages(data.messages, data.page, data.limit);
                        renderPagination(data.total, data.page, data.limit);
                        renderFilterDropdown(data.unreadCount, data.readCount, data.repliedCount);
                    } else {
                        console.error("Failed to fetch messages:", data.message);
                    }
                })
                .catch(error => console.error("Error fetching messages:", error));
        }

        function renderMessages(messages, page, limit) {
            let tableBody = document.getElementById("messagesTable");
            tableBody.innerHTML = "";

            messages.forEach((msg, index) => {
                    tableBody.innerHTML += `
                    <tr class="${msg.status === 'Unread' ? 'table-warning fw-semibold' : msg.status === 'Replied' ? 'table-success fw-light' : 'fw-light'}">
                        <td>${(page-1) * limit + index + 1}</td>
                        <td>${msg.created_at}</td>
                        <td>${msg.email}</td>
                        <td>${msg.title}</td>
<!--                        <td>${msg.title.split(" ").slice(0, 5).join(" ")}...</td>-->
                        <td>
                            <button onclick="viewMessage(${msg.contactid})" class="btn icon btn-info"><i class="bi bi-info-circle"></i></button>
                            <button onclick="deleteMessage(${msg.contactid})" class="btn icon btn-danger"><i class="bi bi-x"></i></button>
                        </td>
                    </tr>
                `;
                });
        }

        function renderFilterDropdown(unreadCount, readCount, repliedCount) {
            let filterDropdown = document.getElementById("statusFilter");
            // let selectedValue = filterDropdown.value || "All";
            let allCount = unreadCount + readCount + repliedCount;

            filterDropdown.innerHTML = `
                <option value="All">All (${allCount})</option>
                <option value="Unread">Unread (${unreadCount})</option>
                <option value="Read">Read (${readCount})</option>
                <option value="Replied">Replied (${repliedCount})</option>
            `;

            filterDropdown.value = currentFilter; // Set the selected value to the current filter
        }

        function renderPagination(total, page, limit) {
            let totalPages = Math.ceil(total / limit);
            let pagination = document.getElementById("pagination");
            pagination.innerHTML = "";
            // pagination.innerHTML += `
            //     <button class="btn btn-outline-secondary" onclick="changePage(${page - 1})">Previous</button>
            // `;
            for (let i = 1; i <= totalPages; i++) {
                pagination.innerHTML += `
                        <button class="btn btn-outline-primary ${i === parseInt(page) ? 'active' : ''}" onclick="changePage(${i})">${i}</button>
                    `;
            }
            // pagination.innerHTML += `
            //     <button class="btn btn-outline-secondary" onclick="changePage(${page + 1})">Next</button>
            // `;

            // if (page === 1) {
            //     pagination.querySelector("button").disabled = true;
            // }
        }

        function changePage(page) {
            currentPage = page;
            fetchMessages();
        }

        function viewMessage(id) {
            fetch(`/admin/contact-message/view/${id}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        let msg = data.data;
                        currentMessageId = msg.contactid;

                        document.getElementById("modalName").innerText = msg.name;
                        document.getElementById("modalEmail").innerText = msg.email;
                        document.getElementById("modalPhone").innerText = msg.title;
                        document.getElementById("modalStatus").innerText = msg.status;
                        document.getElementById("modalMessage").innerText = msg.message;

                        // document.getElementById("markReadBtn").style.display = msg.status === "Unread" ? "block" : "none";
                        document.getElementById("markRepliedBtn").style.display = msg.status !== "Replied" ? "block" : "none";

                        let modal = new bootstrap.Modal(document.getElementById("messageModal"));
                        modal.show();

                        if (msg.status === "Unread") {
                            markAsRead();
                        }
                    } else {
                        console.error("Failed to fetch message details:", data.message);
                    }
                })
                .catch(error => console.error("Error fetching message details:", error));
        }

        function markAsRead() {
            fetch(`/admin/contact-message/mark-as-read/${currentMessageId}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        bootstrap.Modal.getInstance(document.getElementById("messageModal")).hide();
                        fetchMessages();
                    } else {
                        console.error("Failed to mark message as read:", data.message);
                    }
                })
                .catch(error => console.error("Error marking message as read:", error));
        }

        function markAsReplied() {
            fetch(`/admin/contact-message/mark-as-replied/${currentMessageId}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        bootstrap.Modal.getInstance(document.getElementById("messageModal")).hide();
                        fetchMessages();
                    } else {
                        console.error("Failed to mark message as replied:", data.message);
                    }
                })
                .catch(error => console.error("Error marking message as replied:", error));
        }

        function deleteMessage(id) {
            if (confirm("Are you sure you want to delete this message?")) {
                fetch(`/admin/contact-message/delete/${id}`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            renderMessages();
                        } else {
                            console.error("Failed to delete message:", data.message);
                        }
                    })
                    .catch(error => console.error("Error deleting message:", error));
            }
        }

        fetchMessages(); // Initial load

        document.getElementById("statusFilter").addEventListener("change", () => {
            currentFilter = document.getElementById("statusFilter").value;
            currentPage = 1; // reset to the first page
            fetchMessages();
        });
    </script>

    <?php require_once __DIR__ . "/../common/admin-script.php"; ?>
</body>

</html>