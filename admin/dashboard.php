<?php
include("../config/db.php");
include("../includes/auth_check.php");
include("../includes/layout.php");

$res = mysqli_query($conn, "SELECT COUNT(*) AS c FROM patients");
$p = intval(mysqli_fetch_assoc($res)['c'] ?? 0);

$res = mysqli_query($conn, "SELECT COUNT(*) AS c FROM doctors");
$d = intval(mysqli_fetch_assoc($res)['c'] ?? 0);
?>

<div class="card">
    <h2>Admin Dashboard</h2>
    <p>Total Patients: <?= $p ?></p>
    <p>Total Doctors: <?= $d ?></p>
</div>

<?php include("../includes/footer.php"); ?>
