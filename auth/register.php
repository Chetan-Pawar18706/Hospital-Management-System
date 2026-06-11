<?php
include("../config/db.php");

// When Register button is clicked, save patient to database
if (isset($_POST["btnreg"])) {
    $name = $_POST["name"];
    $email = $_POST["email"];
    $password = $_POST["password"];
    $age = $_POST["age"];
    $gender = $_POST["gender"];
    $contact = $_POST["contact"];
    $role = 'patient';

    // Insert into users table
    $insertUserQuery = "INSERT INTO users (name, email, password, role) VALUES ('$name', '$email', '$password', '$role')";
    $userInserted = mysqli_query($conn, $insertUserQuery);

    if ($userInserted) {
        $uid = mysqli_insert_id($conn);
        
        // Insert into patients table
        $insertPatientQuery = "INSERT INTO patients (user_id, age, gender, contact) VALUES ($uid, '$age', '$gender', '$contact')";
        $patientInserted = mysqli_query($conn, $insertPatientQuery);

        if ($patientInserted) {
            echo "<script>alert('Registration successful! Please login.'); window.location.href = 'login.php';</script>";
        } else {
            echo "<script>alert('Error creating patient record');</script>";
        }
    } else {
        echo "<script>alert('Registration failed');</script>";
    }
}
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Register - Hospital</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>

<body>
    <?php include __DIR__ . '/../includes/nav.php'; ?>
    <div class="auth-box">
        <div class="card">
            <h2>Patient Registration</h2>
            <form method="post" novalidate class="validate">
                <label>Name</label>
                <input name="name" type="text" required>

                <label>Email</label>
                <input name="email" type="email" required>

                <label>Password</label>
                <input name="password" type="password" required>

                <label>Age</label>
                <input name="age" type="number">

                <label>Gender</label>
                <select name="gender">
                    <option value="">Select</option>
                    <option value="Male">Male</option>
                    <option value="Female">Female</option>
                    <option value="Other">Other</option>
                </select>

                <label>Contact</label>
                <input name="contact" type="text">

                <button type="submit" name="btnreg" class="button">Register</button>
            </form>
            <p style="margin-top:12px">Already registered? <a href="login.php">Login</a></p>
        </div>
    </div>
</body>

</html>