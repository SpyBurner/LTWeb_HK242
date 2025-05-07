<?php

use core\Logger;
use model\FAQEntryModel;

    assert(isset($faq_list));
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FAQ</title>

    <?php require_once __DIR__ . "/../common/admin-link.php"; ?>
    <script>
        function openModal(id) {
            let modal = new bootstrap.Modal(document.getElementById(id));
            modal.show();
        }

        function openEditModal(id) {
            window.location.href = "/admin/faq?edit=" + id;
        }

        function closeModal(id){
            let modal = new bootstrap.Modal(document.getElementById(id));
            modal.hide();
        }

        function deleteFaq(id){
            if (confirm("Are you sure you want to delete this FAQ entry?")) {
                window.location.href = "/admin/faq/delete_faq?id=" + id;
            }
        }

    </script>
</head>

<body>
<div id="app">
    <?php require_once __DIR__ . "/../common/admin-sidebar.php"; ?>

    <div id="main">
        <?php require_once __DIR__ . "/../common/admin-header.php"; ?>

        <!-- put main content here -->
        <div class="page-heading">
            <h3>Site title</h3>
        </div>

        <div id="admin-mode">
            <section class="max-w-5xl mx-auto p-6 ">
                <h2 class="text-3xl font-bold text-center mb-6">Frequently Asked Questions (edit mode)</h2>

                <div class="flex flew-row justify-end">
                    <button class="btn btn-primary" onclick="openModal('faq-add')">Add FAQ</button>
                </div>

                <div class="table-responsive" style="max-height: 24rem; overflow-y: auto;">
                    <table class="table table-striped table-bordered w-100">
                        <thead class="bg-gray-200 sticky-top" style="top: 0; background-color: #f3f4f6; z-index: 1;">
                        <tr>
                            <th class="p-1 border">#</th>
                            <th class="p-3 border">Question</th>
                            <th class="p-3 border">Answer</th>
                            <th class="p-3 border">Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        foreach ($faq_list as $entry) {
                            if (!($entry instanceof FAQEntryModel)) {
                                throw new TypeError("Expected QnaEntryModel");
                            }
                            ?>
                            <tr>
                                <td class="p-1 border"><?= $entry->getFaqid(); ?></td>
                                <td class="p-3 border"><?= $entry->getQuestion(); ?></td>
                                <td class="p-3 border"><?= $entry->getAnswer(); ?></td>
                                <td class="p-3 border text-center">
                                    <button class="btn btn-sm btn-secondary" onclick="openEditModal(<?=$entry->getFaqid()?>)">Edit</button>
                                    <button class="btn btn-sm btn-warning" onclick="deleteFaq(<?= $entry->getFaqid()?>)">Delete</button>
                                </td>
                            </tr>
                            <?php
                        }
                        ?>
                        </tbody>
                    </table>
                </div>
            </section>

            <!-- Admin faq add -->
            <div class="modal fade" id="faq-add" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-lg modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Add FAQ</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <form method="post" action="/admin/faq/add_faq">
                            <div class="modal-body">
                                <div class="mb-3 row">
                                    <label for="faq-question" class="col-sm-3 col-form-label text-end">Question</label>
                                    <div class="col-sm-9">
                                        <input name="question" id="faq-question" type="text" class="form-control" placeholder="Enter question" required>
                                    </div>
                                </div>
                                <div class="mb-3 row">
                                    <label for="faq-answer" class="col-sm-3 col-form-label text-end">Answer</label>
                                    <div class="col-sm-9">
                                        <textarea name="answer" id="faq-answer" class="form-control" rows="5" placeholder="Enter answer" required></textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                <button type="submit" class="btn btn-primary">Save FAQ</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>


            <?php
                if (isset($edit_faq_entry)){
                    if (!$edit_faq_entry instanceof FAQEntryModel)
                        throw new TypeError("Expected FAQEntryModel");
            ?>
            <!--Admin FAQ edit modal-->
                    <div class="modal fade" id="faq-edit" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-lg modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Edit FAQ</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <form method="post" action="/admin/faq/edit_faq">
                                    <div class="modal-body">
                                        <div class="mb-3 row">
                                            <label for="edit-faq-id" class="col-sm-3 col-form-label text-end">ID</label>
                                            <div class="col-sm-9">
                                                <input name="id" id="edit-faq-id" type="text" class="form-control" value="<?= $edit_faq_entry->getFaqid() ?>" readonly>
                                            </div>
                                        </div>
                                        <div class="mb-3 row">
                                            <label for="edit-faq-question" class="col-sm-3 col-form-label text-end">Question</label>
                                            <div class="col-sm-9">
                                                <input name="question" id="edit-faq-question" type="text" class="form-control" value="<?= $edit_faq_entry->getQuestion() ?>">
                                            </div>
                                        </div>
                                        <div class="mb-3 row">
                                            <label for="edit-faq-answer" class="col-sm-3 col-form-label text-end">Answer</label>
                                            <div class="col-sm-9">
                                                <textarea name="answer" id="edit-faq-answer" class="form-control" rows="5"><?= trim($edit_faq_entry->getAnswer()) ?></textarea>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                        <button type="submit" class="btn btn-primary">Save</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>



            <?php
                }
                if (isset($edit_faq_entry)){
                    Logger::log("Edit FAQ entry with ID: " . $edit_faq_entry->getFaqid());
            ?>
                <script>
                    document.addEventListener('DOMContentLoaded', function () {
                        openModal('faq-edit');
                    });
                </script>
            <?php
                }
            ?>

        </div>

    </div>
</div>

<?php require_once __DIR__ . "/../common/admin-script.php"; ?>
</body>

</html>