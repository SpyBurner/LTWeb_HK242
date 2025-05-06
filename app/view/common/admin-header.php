<!-- put at the top of #main  -->
<header class="mb-3">
    <a href="#" class="burger-btn d-block d-xl-none">
        <i class="bi bi-justify fs-3"></i>
    </a>
</header>

<?php
global $MESSAGE_DURATION;

//Include alert box
require_once __DIR__ . "/../../component/AdminAlertBox.php";

use core\SessionHelper;

// Retrieve session data and clear it after displaying
$formData = SessionHelper::getFlash('form_data');
$error = SessionHelper::getFlash('error');
$success = SessionHelper::getFlash('success');

// Show alerts if any
if ($error) {
    showAlert($error, "error", MESSAGE_DURATION);
}
if ($success) {
    showAlert($success, "success", MESSAGE_DURATION);
}
?>