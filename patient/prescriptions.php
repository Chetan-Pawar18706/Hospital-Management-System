<?php
include("../config/db.php");
include("../includes/auth_check.php");
include("../includes/layout.php");

$uid = $_SESSION['user_id'];

$stmt = mysqli_prepare($conn, "SELECT pr.* FROM prescriptions pr JOIN appointments a ON pr.appointment_id = a.id JOIN patients p ON a.patient_id = p.id WHERE p.user_id = ?");
mysqli_stmt_bind_param($stmt, 'i', $uid);
mysqli_stmt_execute($stmt);
$res = mysqli_stmt_get_result($stmt);

echo "<h2>My Prescriptions</h2>";

while ($p = mysqli_fetch_assoc($res)) {
    echo "<b>Diagnosis:</b> ".htmlspecialchars($p['diagnosis'])."<br>";
    echo "<b>Medicines:</b> ".htmlspecialchars($p['medicines'])."<br><hr>";
}
mysqli_stmt_close($stmt);
?>

<?php include("../includes/footer.php"); ?>
