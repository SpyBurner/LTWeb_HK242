<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Base Layout</title>

    <?php require_once __DIR__ . "/../common/admin-link.php"; ?>
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
                            <th class="p-3 border">Question</th>
                            <th class="p-3 border">Answer</th>
                            <th class="p-3 border">Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td class="p-3 border">What is DaisyUI?</td>
                            <td class="p-3 border">DaisyUI is a Tailwind CSS component library.</td>
                            <td class="p-3 border text-center">
                                <button class="btn btn-sm btn-secondary" onclick="openModal('faq-edit')">Edit</button>
                                <button class="btn btn-sm btn-error">Delete</button>
                            </td>
                        </tr>
                        <tr>
                            <td class="p-3 border">How do I install DaisyUI?</td>
                            <td class="p-3 border">Run <code>npm install daisyui</code> with Tailwind CSS installed.</td>
                            <td class="p-3 border text-center">
                                <button class="btn btn-sm btn-secondary" onclick="openModal('faq-edit')">Edit</button>
                                <button class="btn btn-sm btn-error">Delete</button>
                            </td>
                        </tr>
                        <tr>
                            <td class="p-3 border">Is DaisyUI free to use?</td>
                            <td class="p-3 border">Yes, DaisyUI is open-source and free to use.</td>
                            <td class="p-3 border text-center">
                                <button class="btn btn-sm btn-secondary" onclick="openModal('faq-edit')">Edit</button>
                                <button class="btn btn-sm btn-error">Delete</button>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>

            </section>

            <!--Admin FAQ add modal-->
            <dialog id="faq-add" class="modal">
                <div class="modal-box">
                    <h3 class="text-lg font-bold mb-4">Add FAQ</h3>

                    <!-- Edit Form -->
                    <form method="dialog">
                        <label class="block font-medium mb-1">Question</label>
                        <input type="text" class="input input-bordered w-full mb-3" placeholder="Enter question" />

                        <label class="block font-medium mb-1">Answer</label>
                        <textarea class="textarea textarea-bordered w-full mb-3" placeholder="Enter answer"></textarea>

                        <div class="modal-action">
                            <button type="submit" class="btn btn-primary">Save</button>
                            <button class="btn">Close</button>
                        </div>
                    </form>
                </div>
            </dialog>

            <!--Admin FAQ edit modal-->
            <dialog id="faq-edit" class="modal">
                <div class="modal-box">
                    <h3 class="text-lg font-bold mb-4">Edit FAQ</h3>

                    <!-- Edit Form -->
                    <form method="dialog">
                        <label class="block font-medium mb-1">Question</label>
                        <input type="text" class="input input-bordered w-full mb-3" placeholder="Enter question" />

                        <label class="block font-medium mb-1">Answer</label>
                        <textarea class="textarea textarea-bordered w-full mb-3" placeholder="Enter answer"></textarea>

                        <div class="modal-action">
                            <button type="submit" class="btn btn-primary">Save</button>
                            <button class="btn">Close</button>
                        </div>
                    </form>
                </div>
            </dialog>
        </div>

    </div>
</div>

<?php require_once __DIR__ . "/../common/admin-script.php"; ?>
</body>

</html>