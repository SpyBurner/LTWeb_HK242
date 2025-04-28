<?php
assert(isset($user));
assert(isset($contacts));
assert(isset($avatar));

?>

<!DOCTYPE html>
<html lang="en" data-theme="valentine">
<head>
    <?php require_once __DIR__ . "/../common/head.php"; ?>
    <title>CakeZone Register</title>

</head>
<body>

    <?php require_once __DIR__ . "/../common/header.php"; ?>

    <script>
        function openModal(modalId) {
            console.log("Opening modal: " + modalId);
            const modal = document.getElementById(modalId);

            if (modal) console.log("Modal found: " + modalId);
            else console.log("Modal not found: " + modalId);

            if (modal) {
                modal.showModal();
            }
        }

        function openEditModal(contactId, name, phone, address) {
            // Set the contact ID in the hidden input field
            document.getElementById("edit-contact-id").value = contactId;

            // Set the values in the input fields
            document.getElementById("edit-contact-name").value = name;
            document.getElementById("edit-contact-phone").value = phone;
            document.getElementById("edit-contact-address").value = address;

            // Open the modal
            const modal = document.getElementById('contact-edit');
            if (modal) modal.showModal();
        }


        function closeModal(modalId) {
            const modal = document.getElementById(modalId);
            if (modal) {
                modal.close();
            }
        }

        function deleteContact(contactId) {
            if (confirm("Are you sure you want to delete this contact?")) {
                window.location.href = "profile/delete_contact?contactid=" + contactId;
            }
        }

        // Automatically detect changes
        function uploadAvatar(event) {
            const file = event.target.files[0];

            console.log("Selected file:", file);

            if (file) {
                const formData = new FormData();
                formData.append('file', file);

                fetch(BASE_URL + '/profile/update_avatar', {
                    method: 'POST',
                    body: formData, // FormData automatically handles content type
                })
                .then(response => {
                    if (!response.ok) throw new Error("Upload failed");
                    return response.text();
                })
                .then(data => {
                    console.log("Upload success:", data);
                    window.location.reload();
                })
                    .catch(error => {
                        console.error("Error uploading avatar:", error);
                    });
            }
        }



    </script>

    <main class="container mx-auto p-6">
        <section class="bg-white shadow-lg rounded-lg p-10 md:p-16">
            <div class="flex flex-col md:flex-row items-center gap-8">
                <!-- Profile Picture with Upload Overlay -->
                <div class="relative w-40 h-40 md:w-48 md:h-48 rounded-full overflow-hidden border-4 border-pink-500">
                    <!-- Image -->
                    <img class="avatar" src="/<?php echo $avatar?>" alt="Profile Picture" class="w-full h-full object-cover">
                    <!-- Overlay for click -->
                    <div class="absolute inset-0 bg-black bg-opacity-50 flex items-center justify-center opacity-0 hover:opacity-70 transition-opacity cursor-pointer z-10"
                         onclick="document.getElementById('avatar-upload').click()">
                        <span class="text-white font-bold">Click to change</span>
                    </div>

                    <!-- Hidden File Input -->
                    <input type="file" id="avatar-upload" class="absolute inset-0 opacity-0 cursor-pointer" accept="image/*" onchange="uploadAvatar(event)">
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
                        <div class="btn btn-primary" onclick="openModal('change-password')">Change password</div>
                    </div>
                </div>

            </div>
        </section>

        <section class="max-w-5xl mx-auto p-6">
            <h2 class="text-3xl font-bold text-center mb-6">Contacts</h2>
            <div id="admin-mode">
                <section class="max-w-5xl mx-auto p-6">
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
                                if (!$contacts) {
                                    echo "<tr><td colspan='4' class='text-center'>No contacts found</td></tr>";
                                }
                                else
                                foreach ($contacts as $contact){
                            ?>
                                <tr>
                                    <td class="p-3 border"><?php echo htmlspecialchars($contact->getName()); ?></td>
                                    <td class="p-3 border"><?php echo htmlspecialchars($contact->getPhone() ?: 'N/A'); ?></td>
                                    <td class="p-3 border"><?php echo htmlspecialchars($contact->getAddress() ?: 'N/A'); ?></td>
                                    <td class="p-3 border text-center">
                                        <button class="btn btn-sm btn-secondary" onclick="openEditModal(<?php echo $contact->getContactId(); ?>, '<?php echo htmlspecialchars($contact->getName()); ?>', '<?php echo htmlspecialchars($contact->getPhone() ?: ''); ?>', '<?php echo htmlspecialchars($contact->getAddress() ?: ''); ?>')">Edit</button>
                                        <button class="btn btn-sm btn-error" onclick="deleteContact(<?php echo $contact->getContactId(); ?>)">Delete</button>
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

                        <form action="/profile/add_contact" method="post">
                            <label class="block font-medium mb-1">Name
                                <input type="text" name="name" class="input input-bordered w-full mb-3" placeholder="Enter name" required />
                            </label>

                            <label class="block font-medium mb-1">Phone
                                <input type="text" name="phone" class="input input-bordered w-full mb-3" placeholder="Enter phone (optional)" />
                            </label>

                            <label class="block font-medium mb-1">Address
                                <textarea name="address" class="textarea textarea-bordered w-full mb-3" placeholder="Enter address (optional)"></textarea>
                            </label>

                            <label class="block font-medium mb-1">Customer ID
                            <input type="number" name="customerid" class="input input-bordered w-full mb-3 readonly:text-gray-500 readonly:bg-gray-100 readonly:border-gray-300" value="<?php echo $user->getUserid();?>" required readonly />
                            </label>


                            <div class="modal-action">
                                <button type="submit" class="btn btn-primary">Add</button>
                                <button type="button" class="btn" onclick="closeModal('contact-add')">Close</button>
                            </div>
                        </form>
                    </div>
                </dialog>

                <!-- Admin Edit Contact Modal -->
                <dialog id="contact-edit" class="modal">
                    <div class="modal-box">
                        <h3 class="text-lg font-bold mb-4">Edit Contact</h3>

                        <form action="/profile/edit_contact" method="post">
                            <input type="hidden" name="contactid" id="edit-contact-id" />

                            <label class="block font-medium mb-1">Name</label>
                            <input type="text" name="name" id="edit-contact-name" class="input input-bordered w-full mb-3" required />

                            <label class="block font-medium mb-1">Phone</label>
                            <input type="text" name="phone" id="edit-contact-phone" class="input input-bordered w-full mb-3" />

                            <label class="block font-medium mb-1">Address</label>
                            <textarea name="address" id="edit-contact-address" class="textarea textarea-bordered w-full mb-3"></textarea>

                            <label class="block font-medium mb-1">Customer ID
                                <input type="number" name="customerid" class="input input-bordered w-full mb-3 readonly:text-gray-500 readonly:bg-gray-100 readonly:border-gray-300" value="<?php echo $user->getUserid();?>" required readonly />
                            </label>

                            <div class="modal-action">
                                <button type="submit" class="btn btn-primary">Save</button>
                                <button type="button" class="btn" onclick="closeModal('contact-edit')">Close</button>
                            </div>
                        </form>
                    </div>
                </dialog>

                <!-- Change Password Modal -->
                <dialog id="change-password" class="modal">
                    <div class="modal-box">
                        <h3 class="text-lg font-bold mb-4">Change Password</h3>

                        <form action="/profile/change_password" method="post">
                            <label class="block font-medium mb-1">Old Password
                                <input type="password" name="old_password" class="input input-bordered w-full mb-3" placeholder="Enter old password" required />
                            </label>

                            <label class="block font-medium mb-1 validator">New Password
                                <input name="new_password" type="password" required placeholder="Enter new password" minlength="8" class="input input-bordered w-full mb-3"
                                       pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}"
                                       title="Must be more than 8 characters, including number, lowercase letter, uppercase letter"/>
                            </label>
                            <p class="validator-hint hidden">
                                Must be more than 8 characters, including
                                <br />At least one number
                                <br />At least one lowercase letter
                                <br />At least one uppercase letter
                            </p>

                            <label class="block font-medium mb-1">Confirm New Password
                                <input type="password" name="confirm_password" class="input input-bordered w-full mb-3" placeholder="Confirm new password" required />
                            </label>

                            <div class="modal-action">
                                <button type="submit" class="btn btn-primary">Change</button>
                                <button type="button" class="btn" onclick="closeModal('change-password')">Close</button>
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
