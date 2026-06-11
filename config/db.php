<?php
$conn = mysqli_connect("localhost", "root", "", "hospital_db");
if (!$conn) {
    die("Database Connection Failed");
}
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// CSRF helpers
if (!function_exists('csrf_token')) {
    function csrf_token()
    {
        if (empty($_SESSION['csrf_token'])) {
            if (function_exists('random_bytes')) {
                $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
            } else {
                $_SESSION['csrf_token'] = bin2hex(openssl_random_pseudo_bytes(32));
            }
        }
        return $_SESSION['csrf_token'];
    }

    function csrf_input()
    {
        $t = htmlspecialchars(csrf_token());
        echo "<input type=\"hidden\" name=\"csrf_token\" value=\"{$t}\">";
    }

    function verify_csrf($token)
    {
        return isset($_SESSION['csrf_token']) && is_string($token) && hash_equals($_SESSION['csrf_token'], $token);
    }
}
?>
