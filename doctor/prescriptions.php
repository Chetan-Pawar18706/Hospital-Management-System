<?php
include("../config/db.php");
include("../includes/auth_check.php");
include("../includes/layout.php");

// When button is clicked, save to database
if (isset($_POST["btnadd"])) {
    $appointment_id = $_POST["appointment_id"];
    $diagnosis = $_POST["diagnosis"];
    $medicines = $_POST["medicines"];
    $notes = $_POST["notes"];

    $insertQuery = "INSERT INTO prescriptions (appointment_id, diagnosis, medicines, notes) VALUES ($appointment_id, '$diagnosis', '$medicines', '$notes')";
    $insertResult = mysqli_query($conn, $insertQuery);

    if ($insertResult) {
        echo "<script>alert('Prescription added!'); window.location.href = 'prescriptions.php';</script>";
    } else {
        echo "<script>alert('Error adding prescription');</script>";
    }
}

// Delete prescription
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $deleteQuery = "DELETE FROM prescriptions WHERE id = $id";
    $deleteResult = mysqli_query($conn, $deleteQuery);
    
    if ($deleteResult) {
        echo "<script>alert('Prescription deleted'); window.location.href = 'prescriptions.php';</script>";
    }
}

// Edit prescription
$edit_id = null;
$presc = null;
if (isset($_GET['edit'])) {
    $edit_id = $_GET['edit'];
    $editQuery = "SELECT * FROM prescriptions WHERE id = $edit_id";
    $editResult = mysqli_query($conn, $editQuery);
    $presc = mysqli_fetch_assoc($editResult);
}

// Update prescription
if (isset($_POST["btnupdate"])) {
    $id = $_POST["id"];
    $appointment_id = $_POST["appointment_id"];
    $diagnosis = $_POST["diagnosis"];
    $medicines = $_POST["medicines"];
    $notes = $_POST["notes"];

    $updateQuery = "UPDATE prescriptions SET appointment_id = $appointment_id, diagnosis = '$diagnosis', medicines = '$medicines', notes = '$notes' WHERE id = $id";

    if (mysqli_query($conn, $updateQuery)) {
        echo "<script>alert('Prescription updated!'); window.location.href = 'prescriptions.php';</script>";
    } else {
        echo "<script>alert('Error updating prescription');</script>";
    }
}

// Fetch all prescriptions for this doctor only
$user_id = $_SESSION['user_id'];

// Get doctor_id for current logged in doctor
$doctorQuery = "SELECT id FROM doctors WHERE user_id = $user_id LIMIT 1";
$doctorResult = mysqli_query($conn, $doctorQuery);
$doctor = mysqli_fetch_assoc($doctorResult);
$doctor_id = isset($doctor['id']) ? $doctor['id'] : 0;

$prescs_list = [];
if ($doctor_id > 0) {
    $prescsQuery = "SELECT pr.id, pr.appointment_id, pr.diagnosis, pr.medicines, pr.notes FROM prescriptions pr 
                    JOIN appointments a ON pr.appointment_id = a.id 
                    WHERE a.doctor_id = $doctor_id 
                    ORDER BY pr.appointment_id DESC";
    $prescsResult = mysqli_query($conn, $prescsQuery);
    
    while($row = mysqli_fetch_assoc($prescsResult)) {
        $prescs_list[] = $row;
    }
}
?>

<?php if (!empty($errors)): ?><div class="error"><ul><?php foreach ($errors as $e) echo '<li>'.htmlspecialchars($e).'</li>'; ?></ul></div><?php endif; ?>

<div class="card">
    <h2><?php echo isset($presc) ? 'Edit Prescription' : 'Add Prescription'; ?></h2>
    
    <form method="POST" class="validate">
        <?php if (isset($presc)): ?>
            <input type="hidden" name="id" value="<?php echo $presc['id']; ?>">
        <?php endif; ?>

        <label>Appointment ID</label>
        <input name="appointment_id" placeholder="Appointment ID" value="<?php echo isset($presc) ? $presc['appointment_id'] : ''; ?>" required>

        <label>Diagnosis</label>
        <textarea name="diagnosis" placeholder="Diagnosis" required><?php echo isset($presc) ? htmlspecialchars($presc['diagnosis']) : ''; ?></textarea>

        <label>Medicines</label>
        <textarea name="medicines" placeholder="Medicines"><?php echo isset($presc) ? htmlspecialchars($presc['medicines']) : ''; ?></textarea>

        <label>Notes</label>
        <textarea name="notes" placeholder="Notes"><?php echo isset($presc) ? htmlspecialchars($presc['notes']) : ''; ?></textarea>

        <button type="submit" name="<?php echo isset($presc) ? 'btnupdate' : 'btnadd'; ?>" class="button"><?php echo isset($presc) ? 'Update Prescription' : 'Save'; ?></button>
        <?php if (isset($presc)): ?>
            <a href="prescriptions.php" class="button" style="background: #6c757d; margin-left: 10px;">Cancel</a>
        <?php endif; ?>
    </form>
</div>

<div class="card">
    <h2>Prescriptions List</h2>
    <table>
        <thead>
            <tr>
                <th>Appointment ID</th>
                <th>Diagnosis</th>
                <th>Medicines</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($prescs_list as $p): ?>
            <tr>
                <td><?php echo intval($p['appointment_id']); ?></td>
                <td><?php echo htmlspecialchars(substr($p['diagnosis'], 0, 50)); ?></td>
                <td><?php echo htmlspecialchars(substr($p['medicines'] ?? 'N/A', 0, 40)); ?></td>
                <td>
                    <a href="?edit=<?php echo $p['id']; ?>" class="button" style="background: #007bff; padding: 6px 12px; font-size: 12px;">Edit</a>
                    <a href="?delete=<?php echo $p['id']; ?>" class="button" style="background: #dc3545; padding: 6px 12px; font-size: 12px;" onclick="return confirm('Delete this prescription?');">Delete</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php include("../includes/footer.php"); ?>
