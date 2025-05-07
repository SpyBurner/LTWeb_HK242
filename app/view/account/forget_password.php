<!DOCTYPE html>
<html lang="en" data-theme="valentine">

<head>
    <title>Forgot Password | CakeZone</title>
    <?php require_once __DIR__ . "/../common/head.php"; ?>
</head>

<body>
<?php require_once __DIR__ . "/../common/header.php"; ?>

<div id="body" class="m-6 md:w-3/4 md:mx-auto">

    <div class="card bg-base-100 max-w-2xl w-full shadow-2xl mx-auto p-4">
        <h2 class="text-2xl font-bold text-center mb-4">Forgot Password</h2>

        <div class="card-body">
            <form action="#" method="post">
                <fieldset class="fieldset">
                    <label class="input rounded-md w-full">
                        <i class="fa-solid fa-envelope"></i>
                        <input name="email" type="email" placeholder="Your email address" required />
                    </label>

                    <button class="btn btn-primary mt-6 w-1/2 mx-auto" type="submit">Send Reset Link</button>
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
