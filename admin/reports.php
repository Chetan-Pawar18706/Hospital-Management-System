<?php
include("../config/db.php");
include("../includes/auth_check.php");
include("../includes/layout.php");

$res = mysqli_query($conn, "SELECT COUNT(*) AS c FROM patients");
$p = intval(mysqli_fetch_assoc($res)['c'] ?? 0);

$res = mysqli_query($conn, "SELECT SUM(amount) AS total FROM bills");
$r = mysqli_fetch_assoc($res)['total'] ?? 0;
?>

<div class="card">
    <h2>Reports</h2>
    <p><strong>Total Patients:</strong> <?php echo $p; ?></p>
    <p><strong>Total Revenue:</strong> ₹<?php echo number_format($r, 2); ?></p>
</div>

<?php include("../includes/footer.php"); ?>
