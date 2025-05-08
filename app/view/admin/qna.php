<?php

use model\MessageModel;
use model\QnaEntryModel;
assert(isset($qna_list)); // $qna_list is an array of QnaModel

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Q&A Management</title>
    <?php require_once __DIR__ . "/../common/admin-link.php"; ?>
    <script>
        function openModal(id) {
            let modal = new bootstrap.Modal(document.getElementById(id));
            modal.show();
        }

        function openEditModal(id) {
            window.location.href = "/admin/qna?edit=" + id;
        }

        function deleteMessage(id) {
            if (confirm("Are you sure you want to delete this message entry?")) {
                window.location.href = "/admin/qna/delete_message?id=" + id;
            }
        }

        function deleteQna(id){
            if (confirm("Are you sure you want to delete this Q&A entry?")) {
                window.location.href = "/admin/qna/delete_qna?id=" + id;
            }
        }
    </script>

    <style>
        .table-responsive thead th {
            position: sticky;
            top: 0;
            background-color: #f1f1f1; /* Matches bg-gray-200 */
            z-index: 1;
        }
    </style>

</head>
<body>
<div id="app">
    <?php require_once __DIR__ . "/../common/admin-sidebar.php"; ?>

    <div id="main">
        <?php require_once __DIR__ . "/../common/admin-header.php"; ?>

        <div class="page-heading">
            <h3>Q&A Management</h3>
        </div>

        <div class="max-w-5xl mx-auto p-6">
            <h2 class="text-3xl font-bold text-center mb-6">Q&A Entries</h2>

            <div class="table-responsive" style="max-height: 500px; overflow-y: auto;">

                <table class="table table-striped table-bordered">
                    <thead class="bg-gray-200">
                    <tr>
                        <th class="px-4 py-3">#</th>
                        <th class="px-4 py-3">Question</th>
                        <th class="px-5 py-3">Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($qna_list as $qna):
                        assert($qna instanceof QnaEntryModel);
                    ?>
                        <tr>
                            <td><?= $qna->getQnaid(); ?></td>
                            <td><?= nl2br(htmlspecialchars($qna->getMessage()->getContent())) ?></td>
                            <td>
                                <button class="btn btn-sm btn-secondary" onclick="openEditModal(<?= $qna->getQnaid(); ?>)">Edit</button>
                                <button class="btn btn-sm btn-warning" onclick="deleteQna(<?= $qna->getQnaid(); ?>)">Delete</button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <?php
        if (isset($edit_qna_entry) && $edit_qna_entry instanceof QnaEntryModel):
        ?>
            <!-- Edit Q&A Modal -->
            <div class="modal fade" id="qna-edit" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-lg modal-dialog-centered">
                    <div class="modal-content">
                        <form method="post" action="/admin/qna/edit_qna">
                            <div class="modal-header">
                                <h5 class="modal-title">Edit Q&A Entry #<?= htmlspecialchars($edit_qna_entry->getQnaid()) ?></h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                <input type="hidden" name="id" value="<?= $edit_qna_entry->getQnaid(); ?>">

                                <!-- Messages Table -->
                                <div class="table-responsive" style="max-height: 500px; overflow-y: auto;">
                                    <table class="table table-bordered table-striped align-middle">
                                        <thead class="table-dark">
                                        <tr>
                                            <th>Message ID</th>
                                            <th>User ID</th>
                                            <th>Content</th>
                                            <th>Send Date</th>
                                            <th>Action</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php if (!empty($edit_qna_messages)): ?>
                                            <?php foreach ($edit_qna_messages as $msg):
                                                    assert($msg instanceof MessageModel);
                                                ?>
                                                <tr>
                                                    <td><?= htmlspecialchars($msg->getMsgid()) ?></td>
                                                    <td><?= htmlspecialchars($msg->getUserid()) ?></td>
                                                    <td><?= nl2br(htmlspecialchars($msg->getContent())) ?></td>
                                                    <td><?= htmlspecialchars($msg->getSenddate()) ?></td>
                                                    <td>
                                                        <form method="post" action="/admin/qna/delete_message" onsubmit="return confirm('Are you sure you want to delete this message?');">
                                                            <input type="hidden" name="msgid" value="<?= htmlspecialchars($msg->getMsgid()) ?>">
                                                            <button class="btn btn-danger btn-sm" type="button" onclick="deleteMessage(<?= $msg->getMsgid() ?>)">Delete</button>
                                                        </form>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <tr>
                                                <td colspan="5" class="text-center">No messages found for this entry.</td>
                                            </tr>
                                        <?php endif; ?>
                                        </tbody>
                                    </table>
                                </div>

                            </div>
                            <div class="modal-footer">
                                <button class="btn btn-secondary" data-bs-dismiss="modal" type="button">Close</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <script>
                document.addEventListener("DOMContentLoaded", () => {
                    openModal('qna-edit');
                });
            </script>
        <?php endif; ?>



    </div>
</div>

<?php require_once __DIR__ . "/../common/admin-script.php"; ?>
</body>
</html>
