<!DOCTYPE html>
<html lang="en" data-theme="valentine">

<head>
    <title>CakeZone Login</title>

    <?php
        require_once __DIR__."/../common/head.php";
    ?>
</head>

<body>
    <?php
        require_once __DIR__."/../common/header.php";
    ?>



    <div id="body" class="m-6 md:w-3/4 md:mx-auto">
        <!-- name of each tab group should be unique -->

        <div class="card bg-base-100 max-w-2xl w-full shadow-2xl mx-auto p-4">
            <div class="rounded-full bg-base-200 p-2 flex gap-2 w-1/2 mx-auto">
                <a href="/account/login" class="btn flex-1 bg-base-100 border border-primary">LOGIN</a>
                <a href="/account/register" class="btn flex-1">REGISTER</a>
            </div>

            <div class="card-body">
                <form action="#" method="post">
                    <fieldset class="fieldset">
                        <label class="input rounded-md w-full">
                            <i class="fa-solid fa-envelope"></i>
                            <input name="email" type="email" placeholder="mail@site.com" required />
                        </label>

                        <label class="input rounded-md w-full">
                            <i class="fa-solid fa-key"></i>
                            <input name="password" type="password" required placeholder="Password" />
                        </label>

<!--                        <div><a class="link link-hover" href="/account/forget_password">Forgot password?</a></div>-->
                        <button class="btn btn-primary mt-6 w-1/2 mx-auto" name="submit" value="submit" type="submit">Login</button>
                    </fieldset>
                </form>
            </div>
        </div>

    </div>

    <?php
        require_once __DIR__ . "/../common/footer.php";
    ?>

</body>

</html>