<!DOCTYPE html>
<html lang="en" data-theme="valentine">

<head>
    <title>Reset Password | CakeZone</title>
    <?php require_once __DIR__ . "/../common/head.php"; ?>
</head>

<body>
<?php require_once __DIR__ . "/../common/header.php"; ?>

<div id="body" class="m-6 md:w-3/4 md:mx-auto">

    <div class="card bg-base-100 max-w-2xl w-full shadow-2xl mx-auto p-4">
        <h2 class="text-2xl font-bold text-center mb-4">Reset Your Password</h2>

        <div class="card-body">
            <form action="#" method="post">
                <fieldset class="fieldset">

                    <label class="block font-medium mb-1">New Password
                        <input name="new_password" type="password" required placeholder="Enter new password" minlength="8"
                               class="input input-bordered w-full mb-3"
                               pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}"
                               title="Must be more than 8 characters, including number, lowercase letter, uppercase letter" />
                    </label>
                    <p class="text-sm text-gray-500 mb-2">
                        Must be more than 8 characters, including:
                        <br>• At least one number
                        <br>• At least one lowercase letter
                        <br>• At least one uppercase letter
                    </p>

                    <label class="block font-medium mb-1">Confirm New Password
                        <input type="password" name="confirm_password" class="input input-bordered w-full mb-3"
                               placeholder="Confirm new password" required />
                    </label>

                    <button class="btn btn-primary mt-6 w-1/2 mx-auto" type="submit">Reset Password</button>
                </fieldset>
            </form>

            <div class="text-center mt-4">
                <a class="link link-hover" href="/account/login">Back to Login</a>
            </div>
        </div>
    </div>

</div>

<?php require_once __DIR__ . "/../common/footer.php"; ?>
</body>

</html>
