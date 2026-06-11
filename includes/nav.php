<?php 
if (session_status() == PHP_SESSION_NONE)
    session_start();
include(__DIR__ . '/../config/db.php');

$name = '';
if (isset($_SESSION['user_id'])) {
    $q = "SELECT * FROM users WHERE id = " . $_SESSION['user_id'];
    $result = mysqli_query($conn, $q);
    if ($result) {
        $row = mysqli_fetch_assoc($result);
        $name = $row['name'];
    }
}
   
?>
<header class="navbar">
    <div class="brand">🏥 HMS</div>

    <button class="nav-toggle" aria-label="Toggle navigation">☰</button>

    <nav class="nav-links">
        <ul>
            <?php if (!empty($_SESSION['role']) && $_SESSION['role'] == 'admin') { ?>
                <li><a href="/hospital-management/admin/dashboard.php">Dashboard</a></li>
                <li><a href="/hospital-management/admin/doctors.php">Doctors</a></li>
                <li><a href="/hospital-management/admin/departments.php">Departments</a></li>
                <li><a href="/hospital-management/admin/users.php">Users</a></li>
                <li><a href="/hospital-management/admin/reports.php">Reports</a></li>
                <li><b><?php echo htmlspecialchars($_SESSION['role']); ?></b></li>
                <li><b><?php echo htmlspecialchars($name); ?></b></li>
            <?php } ?>

            <?php if (!empty($_SESSION['role']) && $_SESSION['role'] == 'doctor') { ?>
                <li><a href="/hospital-management/doctor/dashboard.php">Dashboard</a></li>
                <li><a href="/hospital-management/doctor/appointments.php">Appointments</a></li>
                <li><a href="/hospital-management/doctor/prescriptions.php">Prescriptions</a></li>
                <li><b><?php echo htmlspecialchars($_SESSION['role']); ?></b></li>
                <li><b><?php echo htmlspecialchars($name); ?></b></li>

            <?php } ?>

            <?php if (!empty($_SESSION['role']) && $_SESSION['role'] == 'receptionist') { ?>
                <li><a href="/hospital-management/receptionist/dashboard.php">Dashboard</a></li>
                <li><a href="/hospital-management/receptionist/patients.php">Patients</a></li>
                <li><a href="/hospital-management/receptionist/appointments.php">Appointments</a></li>
                <li><a href="/hospital-management/receptionist/billing.php">Billing</a></li>
                <li><b><?php echo htmlspecialchars($_SESSION['role']); ?></b></li>
                <li><b><?php echo htmlspecialchars($name); ?></b></li>
            <?php } ?>

            <?php if (!empty($_SESSION['role']) && $_SESSION['role'] == 'patient') { ?>
                <li><a href="/hospital-management/patient/dashboard.php">Dashboard</a></li>
                <li><a href="/hospital-management/patient/appointments.php">Appointments</a></li>
                <li><a href="/hospital-management/patient/prescriptions.php">Prescriptions</a></li>
                <li><a href="/hospital-management/patient/profile.php">Profile</a></li>
                <li><b><?php echo htmlspecialchars($_SESSION['role']); ?></b></li>
                <li><b><?php echo htmlspecialchars($name); ?></b></li>
            <?php } ?>
        </ul>
    </nav>

    <div class="nav-actions">
        <?php if (!empty($_SESSION['user_id'])): ?>
            <a class="button" href="/hospital-management/auth/logout.php">Logout</a>
        <?php else: ?>
            <a class="button" href="/hospital-management/auth/login.php">Login</a>
            <a class="button" href="/hospital-management/auth/register.php"
                style="background:#007bff;margin-left:8px">Register</a>
        <?php endif; ?>
    </div>
</header>