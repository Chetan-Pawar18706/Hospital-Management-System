<?php
include("../config/db.php");

// When Login button is clicked
if (isset($_POST["btnlogin"])) {
    $email = $_POST["email"];
    $password = $_POST["password"];

    $loginQuery = "SELECT id, name, password, role FROM users WHERE email = '$email' AND password = '$password'";
    $loginResult = mysqli_query($conn, $loginQuery);

    if (mysqli_num_rows($loginResult) > 0) {
        $user = mysqli_fetch_assoc($loginResult);
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['role'] = $user['role'];
        echo "<script>window.location.href = '../index.php';</script>";
    } else {
        echo "<script>alert('Invalid email or password');</script>";
    }
}
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Login - Hospital</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>

<body>
    <?php include __DIR__ . '/../includes/nav.php'; ?>
    <div class="auth-box">
        <div class="card">
            <h2>Login</h2>
            <form method="post" novalidate class="validate">
                <label>Email</label>
                <input name="email" type="email" required>

                <label>Password</label>
                <input name="password" type="password" required>

                <button type="submit" name="btnlogin" class="button">Login</button>
            </form>
            <p style="margin-top:12px"><a href="register.php">New Patient? Register</a></p>
        </div>
    </div>
</body>

</html>