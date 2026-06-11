<?php
include("../config/db.php");
include("../includes/layout.php");

// When Book button is clicked
if (isset($_POST["btnadd"])) {
    $patient = $_POST["patient"];
    $doctor = $_POST["doctor"];
    $date = $_POST["date"];
    $status = 'Pending';

    $insertQuery = "INSERT INTO appointments (patient_id, doctor_id, appointment_date, status) VALUES ($patient, $doctor, '$date', '$status')";
    $insertResult = mysqli_query($conn, $insertQuery);

    if ($insertResult) {
        echo "<script>alert('Appointment booked!'); window.location.href = 'appointments.php';</script>";
    } else {
        echo "<script>alert('Error booking appointment');</script>";
    }
}

// Delete appointment
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $deleteQuery = "DELETE FROM appointments WHERE id = $id";
    $deleteResult = mysqli_query($conn, $deleteQuery);
    
    if ($deleteResult) {
        echo "<script>alert('Appointment deleted'); window.location.href = 'appointments.php';</script>";
    }
}

// Edit appointment
$edit_id = null;
$apt = null;
if (isset($_GET['edit'])) {
    $edit_id = $_GET['edit'];
    $editQuery = "SELECT * FROM appointments WHERE id = $edit_id";
    $editResult = mysqli_query($conn, $editQuery);
    $apt = mysqli_fetch_assoc($editResult);
}

// Update appointment
if (isset($_POST["btnupdate"])) {
    $id = $_POST["id"];
    $patient = $_POST["patient"];
    $doctor = $_POST["doctor"];
    $date = $_POST["date"];
    $status = $_POST["status"];

    $updateQuery = "UPDATE appointments SET patient_id = $patient, doctor_id = $doctor, appointment_date = '$date', status = '$status' WHERE id = $id";

    if (mysqli_query($conn, $updateQuery)) {
        echo "<script>alert('Appointment updated!'); window.location.href = 'appointments.php';</script>";
    } else {
        echo "<script>alert('Error updating appointment');</script>";
    }
}

// Fetch all appointments
$aptsQuery = "SELECT id, patient_id, doctor_id, appointment_date, status FROM appointments ORDER BY appointment_date DESC";
$aptsResult = mysqli_query($conn, $aptsQuery);
$apts_list = [];
while($row = mysqli_fetch_assoc($aptsResult)) {
    $apts_list[] = $row;
}

// Fetch all doctors for dropdown
$doctorsQuery = "SELECT d.id, u.name FROM doctors d JOIN users u ON d.user_id = u.id ORDER BY u.name";
$doctorsResult = mysqli_query($conn, $doctorsQuery);
$doctors_list = [];
while($row = mysqli_fetch_assoc($doctorsResult)) {
    $doctors_list[] = $row;
}
?>

<div class="card">
    <h2><?php echo isset($apt) ? 'Edit Appointment' : 'Book New Appointment'; ?></h2>
    
    <form method="POST" class="validate">
        <?php if (isset($apt)): ?>
            <input type="hidden" name="id" value="<?php echo $apt['id']; ?>">
        <?php endif; ?>

        <label>Patient ID</label>
        <input name="patient" placeholder="Patient ID" value="<?php echo isset($apt) ? $apt['patient_id'] : ''; ?>" required>

        <label>Doctor</label>
        <select name="doctor" required>
            <option value="">-- Select Doctor --</option>
            <?php foreach($doctors_list as $d): ?>
                <option value="<?php echo $d['id']; ?>" <?php echo (isset($apt) && $apt['doctor_id'] == $d['id']) ? 'selected' : ''; ?>>
                    <?php echo htmlspecialchars($d['name']); ?> (ID: <?php echo $d['id']; ?>)
                </option>
            <?php endforeach; ?>
        </select>

        <label>Appointment Date</label>
        <input type="date" name="date" value="<?php echo isset($apt) ? $apt['appointment_date'] : ''; ?>" required>

        <?php if (isset($apt)): ?>
            <label>Status</label>
            <select name="status">
                <option value="Pending" <?php echo ($apt['status'] === 'Pending' ? 'selected' : ''); ?>>Pending</option>
                <option value="Confirmed" <?php echo ($apt['status'] === 'Confirmed' ? 'selected' : ''); ?>>Confirmed</option>
                <option value="Cancelled" <?php echo ($apt['status'] === 'Cancelled' ? 'selected' : ''); ?>>Cancelled</option>
                <option value="Completed" <?php echo ($apt['status'] === 'Completed' ? 'selected' : ''); ?>>Completed</option>
            </select>
        <?php endif; ?>

        <button type="submit" name="<?php echo isset($apt) ? 'btnupdate' : 'btnadd'; ?>" class="button">
            <?php echo isset($apt) ? 'Update Appointment' : 'Book Appointment'; ?>
        </button>
        <?php if (isset($apt)): ?>
            <a href="appointments.php" class="button" style="background: #6c757d; margin-left: 10px;">Cancel</a>
        <?php endif; ?>
    </form>
</div>

<div class="card">
    <h2>Appointments List</h2>
    <table>
        <thead>
            <tr>
                <th>Patient ID</th>
                <th>Doctor ID</th>
                <th>Date</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($apts_list as $a): ?>
            <tr>
                <td><?php echo intval($a['patient_id']); ?></td>
                <td><?php echo intval($a['doctor_id']); ?></td>
                <td><?php echo htmlspecialchars($a['appointment_date']); ?></td>
                <td><?php echo htmlspecialchars($a['status']); ?></td>
                <td>
                    <a href="?edit=<?php echo $a['id']; ?>" class="button" style="background: #007bff; padding: 6px 12px; font-size: 12px;">Edit</a>
                    <a href="?delete=<?php echo $a['id']; ?>" class="button" style="background: #dc3545; padding: 6px 12px; font-size: 12px;" onclick="return confirm('Delete this appointment?');">Delete</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php include("../includes/footer.php"); ?>
