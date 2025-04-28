<?php
global $MESSAGE_DURATION;

require_once __DIR__ . "/../common/head.php";
require_once __DIR__ . "/../common/header.php";
?>

<!DOCTYPE html>
<html lang="en" data-theme="valentine">
<head>
    <title>CakeZone Register</title>
</head>
<body>

<div id="body" class="m-6 md:w-3/4 md:mx-auto">
    <div class="card bg-base-100 max-w-2xl w-full shadow-2xl mx-auto p-4">
        <div class="rounded-full bg-base-200 p-2 flex gap-2 w-1/2 mx-auto">
            <a href="/account/login" class="btn flex-1">LOGIN</a>
            <a href="/account/register" class="btn flex-1 bg-base-100 border border-primary">REGISTER</a>
        </div>

        <div class="card-body">
            <fieldset class="fieldset">
                <form method="post" action="#">
                    <label class="input validator rounded-md w-full">
                        <i class="fa-solid fa-user"></i>
                        <input name="username" type="text" required placeholder="Username"
                               value="<?= htmlspecialchars($formData['username'] ?? '') ?>"/>
                    </label>

                    <label class="input validator rounded-md w-full">
                        <i class="fa-solid fa-envelope"></i>
                        <input name="email" type="email" placeholder="mail@site.com" required
                               value="<?= htmlspecialchars($formData['email'] ?? '') ?>"/>
                    </label>
                    <div class="validator-hint hidden">Enter valid email address</div>

                    <label class="input validator rounded-md w-full">
                        <i class="fa-solid fa-key"></i>
                        <input name="password" type="password" required placeholder="Password" minlength="8"
                               pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}"
                               title="Must be more than 8 characters, including number, lowercase letter, uppercase letter"/>
                    </label>
                    <p class="validator-hint hidden">
                        Must be more than 8 characters, including
                        <br />At least one number
                        <br />At least one lowercase letter
                        <br />At least one uppercase letter
                    </p>

                    <label class="input rounded-md w-full">
                        <i class="fa-solid fa-key"></i>
                        <input name="password_confirm" type="password" required placeholder="Password confirm" minlength="8"
                               title="Type your password again"/>
                    </label>

                    <div id="agree-term" class="flex items-center gap-2 mt-4">
                        <label class="label text-gray-500" for="">
                            I agree to eat cakes every day until I die
                            <input name="accept_tos" type="checkbox" class="checkbox"
                                <?= isset($formData['accept_tos']) ? 'checked' : '' ?>/>
                        </label>
                    </div>

                    <button name="submit" value="submit" type="submit" class="btn btn-primary mt-6 w-1/2 mx-auto">Register</button>
                </form>
            </fieldset>
        </div>
    </div>
</div>

<?php require_once __DIR__ . "/../common/footer.php"; ?>

</body>
</html>
