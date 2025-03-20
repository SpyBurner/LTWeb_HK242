<!DOCTYPE html>
<html lang="en" data-theme="valentine">

<head>
    <title>CakeZone Register</title>

    <?php
        require_once __DIR__."/../common/head.php";
    ?>
</head>

<body>
    <?php
        require_once __DIR__."/../common/header.php";

        //    TEST ZONE
        require_once __DIR__ . '/../../model/UserModel.php';
        $users = User::findAll();

        for ($i = 0; $i < count($users); $i++) {
            echo $users[$i]->getName() . "<br>";
        }

        //
    ?>


    <div id="body" class="m-6 md:w-3/4 md:mx-auto">
        <!-- name of each tab group should be unique -->

        <div class="card bg-base-100 max-w-2xl w-full shadow-2xl mx-auto p-4">
            <div class="rounded-full bg-base-200 p-2 flex gap-2 w-1/2 mx-auto">
                <a href="login" class="btn flex-1">LOGIN</a>
                <a href="register" class="btn flex-1 bg-base-100 border border-primary">REGISTER</a>
            </div>

            <div class="card-body">
                <fieldset class="fieldset">
                    <label class="input validator rounded-md w-full">
                        <i class="fa-solid fa-user"></i>
                        <input type="text" required placeholder="name" />
                    </label>

                    <label class="input validator rounded-md w-full">
                        <i class="fa-solid fa-envelope"></i>
                        <input type="email" placeholder="mail@site.com" required />
                    </label>
                    <div class="validator-hint hidden">Enter valid email address</div>

                    <label class="input validator rounded-md w-full">
                        <i class="fa-solid fa-key"></i>
                        <input type="password" required placeholder="Password" minlength="8"
                            pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}"
                            title="Must be more than 8 characters, including number, lowercase letter, uppercase letter" />
                    </label>
                    <p class="validator-hint hidden">
                        Must be more than 8 characters, including
                        <br />At least one number
                        <br />At least one lowercase letter
                        <br />At least one uppercase letter
                    </p>

                    <label class="input rounded-md w-full">
                        <i class="fa-solid fa-key"></i>
                        <input type="password" required placeholder="Password confirm" minlength="8"
                            title="Type your password again" />
                    </label>

                    <div id="agree-term" class="flex items-center gap-2 mt-4">
                        <input type="checkbox" class="checkbox" />
                        <p class="text-gray-500">I agree to eat cakes every day until I die!</p>
                    </div>
                    
                    <button class="btn btn-primary mt-6 w-1/2 mx-auto">Register</button>
                </fieldset>
            </div>
        </div>

    </div>

    <?php
        require_once __DIR__ . "/../common/footer.php";
    ?>

</body>

</html>