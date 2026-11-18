<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// ingat saya
setcookie('ingat_saya', '', time() - 3600, '/');

session_destroy();
header('Location: login.php');
exit;