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
    </div>
</div>

<?php require_once __DIR__ . "/../common/admin-script.php"; ?>
</body>

</html>