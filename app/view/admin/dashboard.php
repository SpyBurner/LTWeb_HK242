<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Base Layout</title>

    <?php require_once __DIR__ . "/../common/admin-link.php"; ?>
</head>

<body>
    <script src="<?php echo MAZER_BASE_URL . '/static/js/initTheme.js' ?>"></script>
    <div id="app">
        <?php require_once __DIR__ . "/../common/admin-sidebar.php"; ?>

        <div id="main">
            <?php require_once __DIR__ . "/../common/admin-header.php"; ?>

            <!-- put the main content here -->
            <div class="page-heading">
                <h3>Dashboard</h3>
            </div>
        </div>
    </div>

    <?php require_once __DIR__ . "/../common/admin-script.php"; ?>
</body>

</html>