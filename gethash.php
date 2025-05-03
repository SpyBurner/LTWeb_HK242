<?php

$str = 'Password';

    for ($i = 1; $i <= 10; $i++) {
        echo password_hash($str . (string)$i, PASSWORD_BCRYPT);
        echo '<br>';
    }
?>