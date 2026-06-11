<?php
session_start();

/* If already logged in, redirect by role */
if (isset($_SESSION['user_id']) && isset($_SESSION['role'])) {

    switch ($_SESSION['role']) {
        case 'admin':
            header("Location: admin/dashboard.php");
            break;
        case 'doctor':
            header("Location: doctor/dashboard.php");
            break;
        case 'receptionist':
            header("Location: receptionist/dashboard.php");
            break;
        case 'patient':
            header("Location: patient/dashboard.php");
            break;
        default:
            header("Location: auth/login.php");
    }
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Hospital Management System</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

<?php include __DIR__ . '/includes/nav.php'; ?>

<div class="container">

    <!-- HERO SECTION -->
    <div class="card" style="text-align:center;">
        <h1>Welcome to Hospital Management System</h1>
        <p style="margin:15px 0;font-size:15px;">
            A complete solution for managing patients, doctors, appointments,
            prescriptions, and billing in a hospital environment.
        </p>

        <a href="auth/login.php" class="button">Login</a>
        <a href="auth/register.php" class="button" style="background:#007bff;">Patient Register</a>
    </div>

    <!-- FEATURES -->
    <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(220px,1fr));gap:15px;margin-top:25px;">

        <div class="card">
            <h3>👨‍⚕️ Doctor Panel</h3>
            <p>Manage appointments, view patient history, add prescriptions.</p>
        </div>

        <div class="card">
            <h3>🧾 Receptionist Panel</h3>
            <p>Register patients, book appointments, generate bills.</p>
        </div>

        <div class="card">
            <h3>🧑‍🤝‍🧑 Patient Panel</h3>
            <p>Book appointments, view prescriptions and reports.</p>
        </div>

        <div class="card">
            <h3>🛠 Admin Panel</h3>
            <p>Manage users, doctors, departments, reports & activity logs.</p>
        </div>

    </div>

    <!-- FOOTER -->
    <div class="footer">
        <p>© <?php echo date("Y"); ?> Hospital Management System | BCA / MCA Final Year Project</p>
    </div>

</div>

</body>
</html>
