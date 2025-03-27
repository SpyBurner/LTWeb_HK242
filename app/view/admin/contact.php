<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin | Contact</title>

    <?php require_once "../common/admin-link.php"; ?>
</head>

<body>
    <div id="app">
        <?php require_once "../common/admin-sidebar.php"; ?>

        <div id="main">
            <?php require_once "../common/admin-header.php"; ?>

            <!-- put main content here -->
            <div class="page-heading">
                <h3>Contact Messages</h3>
            </div>

            <div id="page-content" class="row justify-content-center">
                <div class="col-12 col-md-10">
                    <!-- Filter Section -->
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <label class="fw-semibold me-2">Filter by Status:</label>
                        <select id="statusFilter" onchange="renderMessages()" class="form-select w-auto">
                            <!-- Options will be dynamically inserted -->
                        </select>
                    </div>
        
                    <!-- Messages Table -->
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead class="table-primary">
                                <tr>
                                    <th>#</th>
                                    <th>Date</th>
                                    <th>Email</th>
                                    <th>Message Preview</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody id="messagesTable">
                                <!-- Messages will be dynamically inserted -->
                            </tbody>
                        </table>
                    </div>
        
                    <!-- Message View Modal -->
                    <div class="modal fade" id="messageModal" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content p-3">
                                <div class="modal-header">
                                    <h5 class="modal-title text-primary fw-bold">Message Details</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body">
                                    <p><strong>Name:</strong> <span id="modalName"></span></p>
                                    <p><strong>Email:</strong> <span id="modalEmail"></span></p>
                                    <p><strong>Phone:</strong> <span id="modalPhone"></span></p>
                                    <p><strong>Status:</strong> <span id="modalStatus"></span></p>
                                    <p><strong>Message:</strong></p>
                                    <div id="modalMessage" class="bg-light p-3 rounded"></div>
                                </div>
                                <div class="modal-footer">
                                    <button id="markReadBtn" class="btn btn-outline-info me-2" onclick="markAsRead()">Mark as Read</button>
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
        let messages = [{
                id: 1,
                date: "2024-03-10",
                name: "John Doe",
                email: "john@example.com",
                phone: "123-456-7890",
                message: "I need a custom cake for my wedding next month. Can you help?",
                status: "Unread"
            },
            {
                id: 2,
                date: "2024-03-11",
                name: "Jane Smith",
                email: "jane@example.com",
                phone: "123-456-7890",
                message: "Do you deliver to remote areas? I live quite far and wanted to check.",
                status: "Read"
            },
            {
                id: 3,
                date: "2024-03-12",
                name: "Alex Brown",
                email: "alex@example.com",
                phone: "123-456-7890",
                message: "Great service! I loved the cake and will definitely order again!",
                status: "Replied"
            }
        ];

        let currentMessageId = null;

        function updateFilterDropdown() {
            let filterDropdown = document.getElementById("statusFilter");
            let selectedValue = filterDropdown.value || "All";

            let unreadCount = messages.filter(m => m.status === "Unread").length;
            let readCount = messages.filter(m => m.status === "Read").length;
            let repliedCount = messages.filter(m => m.status === "Replied").length;

            filterDropdown.innerHTML = `
                <option value="All">All (${messages.length})</option>
                <option value="Unread">Unread (${unreadCount})</option>
                <option value="Read">Read (${readCount})</option>
                <option value="Replied">Replied (${repliedCount})</option>
            `;

            filterDropdown.value = selectedValue;
        }

        function renderMessages() {
            let tableBody = document.getElementById("messagesTable");
            let filter = document.getElementById("statusFilter").value;
            tableBody.innerHTML = "";

            messages
                .filter(msg => filter === "All" || msg.status === filter)
                .forEach((msg, index) => {
                    tableBody.innerHTML += `
                    <tr class="${msg.status === 'Unread' ? 'table-warning fw-semibold' : msg.status === 'Replied' ? 'table-success fw-light' : 'fw-light'}">
                        <td>${index + 1}</td>
                        <td>${msg.date}</td>
                        <td>${msg.email}</td>
                        <td>${msg.message.split(" ").slice(0, 5).join(" ")}...</td>
                        <td>
                            <button onclick="viewMessage(${msg.id})" class="btn icon btn-info"><i class="bi bi-info-circle"></i></button>
                            <button onclick="deleteMessage(${msg.id})" class="btn icon btn-danger"><i class="bi bi-x"></i></button>
                        </td>
                    </tr>
                `;
                });

            updateFilterDropdown();
        }

        function viewMessage(id) {
            let msg = messages.find(m => m.id === id);
            if (!msg) return;

            currentMessageId = id;

            document.getElementById("modalName").innerText = msg.name;
            document.getElementById("modalEmail").innerText = msg.email;
            document.getElementById("modalPhone").innerText = msg.phone;
            document.getElementById("modalStatus").innerText = msg.status;
            document.getElementById("modalMessage").innerText = msg.message;

            document.getElementById("markReadBtn").style.display = msg.status === "Unread" ? "block" : "none";
            document.getElementById("markRepliedBtn").style.display = msg.status !== "Replied" ? "block" : "none";

            let modal = new bootstrap.Modal(document.getElementById("messageModal"));
            modal.show();
        }

        function markAsRead() {
            if (currentMessageId !== null) {
                let msg = messages.find(m => m.id === currentMessageId);
                if (msg) {
                    msg.status = "Read";
                    renderMessages();
                    bootstrap.Modal.getInstance(document.getElementById("messageModal")).hide();
                }
            }
        }

        function markAsReplied() {
            if (currentMessageId !== null) {
                let msg = messages.find(m => m.id === currentMessageId);
                if (msg) {
                    msg.status = "Replied";
                    renderMessages();
                    bootstrap.Modal.getInstance(document.getElementById("messageModal")).hide();
                }
            }
        }

        function deleteMessage(id) {
            messages = messages.filter(m => m.id !== id);
            renderMessages();
        }

        // Initial Render
        updateFilterDropdown();
        renderMessages();
    </script>

    <?php require_once "../common/admin-script.php"; ?>
</body>

</html>