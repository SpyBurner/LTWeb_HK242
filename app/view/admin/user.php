<?php

use model\UserModel;
assert(isset($user_list)); // $user_list is an array of UserModel

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>User Management</title>
    <?php require_once __DIR__ . "/../common/admin-link.php"; ?>
    <script>
        function openModal(id) {
            let modal = new bootstrap.Modal(document.getElementById(id));
            modal.show();
        }

        function openEditModal(id) {
            window.location.href = "/admin/users?edit=" + id;
        }

        function deleteUser(id) {
            if (confirm("Are you sure you want to delete this user?")) {
                window.location.href = "/admin/users/delete_user?id=" + id;
            }
        }

        function openResetPasswordModal(userId) {
            document.querySelector("#reset-password-user-id").value = userId;
            new bootstrap.Modal(document.getElementById("reset-password-modal")).show();
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
            <h3>User Management</h3>
        </div>

        <div class="max-w-5xl mx-auto p-6">
            <h2 class="text-3xl font-bold text-center mb-6">User List</h2>

            <div class="flex justify-end mb-4">
                <button class="btn btn-primary" onclick="openModal('user-add')">Add User</button>
            </div>

            <div class="table-responsive" style="max-height: 500px; overflow-y: auto;">

                <table class="table table-striped table-bordered">
                    <thead class="bg-gray-200">
                    <tr>
                        <th>#</th>
                        <th>Username</th>
                        <th>Email</th>
                        <th>Is Admin</th>
                        <th>Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($user_list as $user): ?>
                        <tr>
                            <td><?= $user->getUserid(); ?></td>
                            <td><?= $user->getUsername(); ?></td>
                            <td><?= $user->getEmail(); ?></td>
                            <td><?= $user->getIsadmin() ? 'Yes' : 'No'; ?></td>
                            <td>
                                <button class="btn btn-sm btn-secondary" onclick="openEditModal(<?= $user->getUserid(); ?>)">Edit</button>
                                <button class="btn btn-sm btn-warning" onclick="deleteUser(<?= $user->getUserid(); ?>)">Delete</button>
                                <button class="btn btn-sm btn-danger" onclick="openResetPasswordModal(<?= $user->getUserid(); ?>)">Reset Password</button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Add User Modal -->
        <div class="modal fade" id="user-add" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content">
                    <form method="post" action="/admin/users/add_user">
                        <div class="modal-header">
                            <h5 class="modal-title">Add User</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3 row">
                                <label class="col-sm-3 col-form-label text-end">Username</label>
                                <div class="col-sm-9">
                                    <input name="username" class="form-control" required>
                                </div>
                            </div>
                            <div class="mb-3 row">
                                <label class="col-sm-3 col-form-label text-end">Email</label>
                                <div class="col-sm-9">
                                    <input name="email" type="email" class="form-control" required>
                                </div>
                            </div>
                            <div class="mb-3 row">
                                <label class="col-sm-3 col-form-label text-end">Password</label>
                                <div class="col-sm-9">
                                    <input name="password" type="password" class="form-control" required>
                                </div>
                            </div>
                            <div class="mb-3 row">
                                <label class="col-sm-3 col-form-label text-end">Is Admin</label>
                                <div class="col-sm-9">
                                    <select name="isadmin" class="form-select">
                                        <option value="0">No</option>
                                        <option value="1">Yes</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button class="btn btn-secondary" data-bs-dismiss="modal" type="button">Cancel</button>
                            <button class="btn btn-primary" type="submit">Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <?php if (isset($edit_user_entry) && $edit_user_entry instanceof UserModel): ?>
            <!-- Edit User Modal -->
            <div class="modal fade" id="user-edit" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-lg modal-dialog-centered">
                    <div class="modal-content">
                        <form method="post" action="/admin/users/edit_user">
                            <div class="modal-header">
                                <h5 class="modal-title">Edit User</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                <input type="hidden" name="id" value="<?= $edit_user_entry->getUserid(); ?>">
                                <div class="mb-3 row">
                                    <label class="col-sm-3 col-form-label text-end">Username</label>
                                    <div class="col-sm-9">
                                        <input name="username" class="form-control" value="<?= $edit_user_entry->getUsername(); ?>" required>
                                    </div>
                                </div>
                                <div class="mb-3 row">
                                    <label class="col-sm-3 col-form-label text-end">Email</label>
                                    <div class="col-sm-9">
                                        <input name="email" type="email" class="form-control" value="<?= $edit_user_entry->getEmail(); ?>" required>
                                    </div>
                                </div>
                                <div class="mb-3 row">
                                    <label class="col-sm-3 col-form-label text-end">Is Admin</label>
                                    <div class="col-sm-9">
                                        <select name="isadmin" class="form-select">
                                            <option value="0" <?= !$edit_user_entry->getIsadmin() ? 'selected' : '' ?>>No</option>
                                            <option value="1" <?= $edit_user_entry->getIsadmin() ? 'selected' : '' ?>>Yes</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button class="btn btn-secondary" data-bs-dismiss="modal" type="button">Cancel</button>
                                <button class="btn btn-primary" type="submit">Update</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <script>
                document.addEventListener("DOMContentLoaded", () => {
                    openModal('user-edit');
                });
            </script>
        <?php endif; ?>

        <!-- Reset Password Modal -->
        <!-- Reset Password Modal -->
        <div class="modal fade" id="reset-password-modal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content">
                    <form method="post" action="/admin/users/reset_password">
                        <input type="hidden" name="id" id="reset-password-user-id">
                        <div class="modal-header">
                            <h5 class="modal-title">Reset User Password</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3 row">
                                <label class="col-sm-3 col-form-label text-end">New Password</label>
                                <div class="col-sm-9">
                                    <input name="new_password" type="password" class="form-control" required>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button class="btn btn-primary" type="submit">Reset</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div>
</div>

<?php require_once __DIR__ . "/../common/admin-script.php"; ?>
</body>
</html>
