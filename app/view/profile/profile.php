<?php
require_once __DIR__ . "/../common/head.php";
require_once __DIR__ . "/../common/header.php";

assert(isset($user));
?>

<!DOCTYPE html>
<html lang="en" data-theme="valentine">
<head>
    <title>CakeZone Register</title>
</head>
<body>

<script>
    function openModal(modalId, contactId = null) {
        const modal = document.getElementById(modalId);
        if (modal) {
            modal.showModal();
            if (contactId) {
                // Fetch contact details and populate the edit modal
                fetch(`get_contact.php?id=${contactId}`)
                    .then(response => response.json())
                    .then(data => {
                        document.getElementById("edit-contact-id").value = data.contactid;
                        document.getElementById("edit-contact-name").value = data.name;
                        document.getElementById("edit-contact-phone").value = data.phone || "";
                        document.getElementById("edit-contact-address").value = data.address || "";
                    });
            }
        }
    }

    function closeModal(modalId) {
        const modal = document.getElementById(modalId);
        if (modal) {
            modal.close();
        }
    }

    function deleteContact(contactId) {
        if (confirm("Are you sure you want to delete this contact?")) {
            fetch(`contact_delete.php?id=${contactId}`, { method: "POST" })
                .then(() => location.reload());
        }
    }

</script>

<main class="container mx-auto p-6">
    <section class="bg-white shadow-lg rounded-lg p-10 md:p-16">
        <div class="flex flex-col md:flex-row items-center gap-8">

            <!-- Profile Picture -->
            <div class="w-40 h-40 md:w-48 md:h-48 rounded-full overflow-hidden border-4 border-pink-500">
                <img src="/assets/img/avatar_male.png" alt="Profile Picture" class="w-full h-full object-cover">
            </div>

            <!-- Profile Info -->
            <div class="flex-1">
                <h1 class="text-3xl font-bold mb-6">
                    <i class="fa-solid fa-user"></i> Profile
                </h1>
                <div class="space-y-4 text-lg">
                    <p><strong>Username:</strong> <?php echo htmlspecialchars($user->getUsername()); ?></p>
                    <p><strong>Email:</strong> <?php echo htmlspecialchars($user->getEmail()); ?></p>
                    <p><strong>Joined:</strong> <?php echo date("F j, Y, g:i a", strtotime($user->getJoindate())); ?></p>
                    <p><strong>Role:</strong>
                        <span class="px-3 py-1 rounded-md text-white text-lg
                            <?php echo $user->getIsadmin() ? 'bg-red-500' : 'bg-blue-500'; ?>">
                            <?php echo $user->getIsadmin() ? 'Admin' : 'User'; ?>
                        </span>
                    </p>
                </div>
            </div>

        </div>
    </section>

    <section id="qna" class="max-w-5xl mx-auto p-6">
        <h2 class="text-3xl font-bold text-center mb-6">Contacts</h2>
        <div id="admin-mode">
            <section class="max-w-5xl mx-auto p-6">
                <h2 class="text-3xl font-bold text-center mb-6">Manage Contacts</h2>

                <div class="flex flex-row justify-end">
                    <button class="btn btn-primary" onclick="openModal('contact-add')">Add Contact</button>
                </div>

                <div class="overflow-x-hidden py-4">
                    <table class="table w-full bg-base-100 shadow-md border border-gray-300">
                        <thead>
                        <tr class="bg-gray-200">
                            <th class="p-3 border">Name</th>
                            <th class="p-3 border">Phone</th>
                            <th class="p-3 border">Address</th>
                            <th class="p-3 border">Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                            global $contacts;
                            foreach ($contacts as $contact){
                        ?>
                            <tr>
                                <td class="p-3 border"><?php echo htmlspecialchars($contact->getName()); ?></td>
                                <td class="p-3 border"><?php echo htmlspecialchars($contact->getPhone() ?: 'N/A'); ?></td>
                                <td class="p-3 border"><?php echo htmlspecialchars($contact->getPhone() ?: 'N/A'); ?></td>
                                <td class="p-3 border text-center">
                                    <button class="btn btn-sm btn-secondary" onclick="openModal('contact-edit', <?php echo $contact->contactId(); ?>)">Edit</button>
                                    <button class="btn btn-sm btn-error" onclick="deleteContact(<?php echo $contact->contactId(); ?>)">Delete</button>
                                </td>
                            </tr>
                        <?php } ?>
                        </tbody>
                    </table>
                </div>
            </section>

            <!-- Admin Add Contact Modal -->
            <dialog id="contact-add" class="modal">
                <div class="modal-box">
                    <h3 class="text-lg font-bold mb-4">Add Contact</h3>

                    <form action="contact_save.php" method="post">
                        <label class="block font-medium mb-1">Name</label>
                        <input type="text" name="name" class="input input-bordered w-full mb-3" placeholder="Enter name" required />

                        <label class="block font-medium mb-1">Phone</label>
                        <input type="text" name="phone" class="input input-bordered w-full mb-3" placeholder="Enter phone (optional)" />

                        <label class="block font-medium mb-1">Address</label>
                        <textarea name="address" class="textarea textarea-bordered w-full mb-3" placeholder="Enter address (optional)"></textarea>

                        <label class="block font-medium mb-1">Customer ID</label>
                        <input type="number" name="customerid" class="input input-bordered w-full mb-3" placeholder="Enter customer ID" required />

                        <div class="modal-action">
                            <button type="submit" class="btn btn-primary">Save</button>
                            <button type="button" class="btn" onclick="closeModal('contact-add')">Close</button>
                        </div>
                    </form>
                </div>
            </dialog>

            <!-- Admin Edit Contact Modal -->
            <dialog id="contact-edit" class="modal">
                <div class="modal-box">
                    <h3 class="text-lg font-bold mb-4">Edit Contact</h3>

                    <form action="contact_update.php" method="post">
                        <input type="hidden" name="contactid" id="edit-contact-id" />

                        <label class="block font-medium mb-1">Name</label>
                        <input type="text" name="name" id="edit-contact-name" class="input input-bordered w-full mb-3" required />

                        <label class="block font-medium mb-1">Phone</label>
                        <input type="text" name="phone" id="edit-contact-phone" class="input input-bordered w-full mb-3" />

                        <label class="block font-medium mb-1">Address</label>
                        <textarea name="address" id="edit-contact-address" class="textarea textarea-bordered w-full mb-3"></textarea>

                        <div class="modal-action">
                            <button type="submit" class="btn btn-primary">Save</button>
                            <button type="button" class="btn" onclick="closeModal('contact-edit')">Close</button>
                        </div>
                    </form>
                </div>
            </dialog>
        </div>

    </section>
</main>


<?php require_once __DIR__ . "/../common/footer.php"; ?>

</body>
</html>
