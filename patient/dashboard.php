<?php
include("../config/db.php");
include("../includes/auth_check.php");
include("../includes/layout.php");

 $uid = $_SESSION['user_id'];
 $stmt = mysqli_prepare($conn, "SELECT * FROM appointments WHERE patient_id = (SELECT id FROM patients WHERE user_id = ?)");
 mysqli_stmt_bind_param($stmt, 'i', $uid);
 mysqli_stmt_execute($stmt);
 $res = mysqli_stmt_get_result($stmt);
?>

<div class="card">
    <h2>Patient Dashboard</h2>

    <?php while($a = mysqli_fetch_assoc($res)) { ?>
        <p><?php echo htmlspecialchars($a['appointment_date']); ?> - <?php echo htmlspecialchars($a['status']); ?></p>
    <?php } ?>
</div>

<?php include("../includes/footer.php"); ?>

<?php mysqli_stmt_close($stmt); ?>
