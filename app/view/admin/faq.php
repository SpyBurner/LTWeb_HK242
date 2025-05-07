<?php
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

        function closeModal(id){
            let modal = new bootstrap.Modal(document.getElementById(id));
            modal.hide();
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

                <div class="overflow-x-hidden py-4">
                    <table class="table w-full bg-base-100 shadow-md border border-gray-300">
                        <thead>
                        <tr class="bg-gray-200">
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
                                <button class="btn btn-sm btn-secondary" onclick="openEditModal('faq-edit')">Edit</button>
                                <button class="btn btn-sm btn-warning">Delete</button>
                            </td>
                        </tr>

                        <?php
                            }
                        ?>
                        </tbody>
                    </table>
                </div>

            </section>

            <div class="modal fade" id="faq-add" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog flex items-center justify-center min-h-screen">
                    <div class="modal-content p-6 w-full max-w-xl">
                        <h3 class="text-lg font-bold mb-6 text-center">Add FAQ</h3>

                        <form method="post" action="/admin/faq/add_faq" class="space-y-4">
                            <div class="flex items-center">
                                <label for="faq-question" class="w-1/4 text-right font-medium pr-4">Question</label>
                                <input name="question" id="faq-question" type="text" class="input input-bordered w-full" placeholder="Enter question" />
                            </div>

                            <div class="flex items-start">
                                <label for="faq-answer" class="w-1/4 text-right font-medium pr-4 pt-2">Answer</label>
                                <textarea name="answer" id="faq-answer" class="textarea textarea-bordered w-full" placeholder="Enter answer"></textarea>
                            </div>

                            <div class="modal-action justify-end">
                                <button type="submit" class="btn btn-primary">Save</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!--Admin FAQ edit modal-->
            <div class="modal fade" id="faq-edit" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog flex items-center justify-center min-h-screen">
                    <div class="modal-content p-6 w-full max-w-xl">
                        <h3 class="text-lg font-bold mb-6 text-center">Edit FAQ</h3>

                        <form method="post" action="/admin/faq/edit_faq" class="space-y-4">
                            <div class="flex items-center">
                                <div class="flex items-center">
                                    <label for="faq-question" class="w-1/4 text-right font-medium pr-4">Question</label>
                                    <input name="question" id="faq-edit-question" type="text" class="input input-bordered w-full" placeholder="Enter question" />
                                </div>

                                <div class="flex items-start">
                                    <label for="faq-answer" class="w-1/4 text-right font-medium pr-4 pt-2">Answer</label>
                                    <textarea name="answer" id="faq-edit-answer" class="textarea textarea-bordered w-full" placeholder="Enter answer"></textarea>
                                </div>

                                <div class="modal-action justify-end">
                                    <button type="submit" class="btn btn-primary">Save</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>


        </div>

    </div>
</div>

<?php require_once __DIR__ . "/../common/admin-script.php"; ?>
</body>

</html>