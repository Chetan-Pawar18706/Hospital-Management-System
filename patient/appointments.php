<?php
include("../config/db.php");
include("../includes/auth_check.php");
include("../includes/layout.php");

$uid = $_SESSION['user_id'];

$stmt = mysqli_prepare($conn, "SELECT a.* FROM appointments a JOIN patients p ON a.patient_id = p.id WHERE p.user_id = ?");
mysqli_stmt_bind_param($stmt, 'i', $uid);
mysqli_stmt_execute($stmt);
$res = mysqli_stmt_get_result($stmt);

echo "<h2>My Appointments</h2>";

while ($a = mysqli_fetch_assoc($res)) {
    echo "Date: ".htmlspecialchars($a['appointment_date'])." | Status: ".htmlspecialchars($a['status'])."<br>";
}
mysqli_stmt_close($stmt);
?>

<?php include("../includes/footer.php"); ?>
