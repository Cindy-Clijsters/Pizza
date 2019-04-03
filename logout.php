<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

//session_destroy();
unset($_SESSION['userId']);

header("location: menu.php");
exit(0);