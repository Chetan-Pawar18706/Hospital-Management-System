<?php
include("../config/db.php");
include("../includes/auth_check.php");
include("../includes/layout.php");

$user_id = $_SESSION['user_id'];

// Get doctor_id for current logged in doctor
$doctorQuery = "SELECT id FROM doctors WHERE user_id = $user_id LIMIT 1";
$doctorResult = mysqli_query($conn, $doctorQuery);

if (!$doctorResult) {
    echo "<div class='card'><h2>Error</h2><p>Query Error: " . mysqli_error($conn) . "</p></div>";
}

$doctor = mysqli_fetch_assoc($doctorResult);
$doctor_id = isset($doctor['id']) ? $doctor['id'] : 0;

// DEBUG: Show doctor info
echo "<div style='background: #f0f0f0; padding: 10px; margin: 10px 0; border-radius: 5px;'>";
echo "User ID: $user_id | Doctor ID: $doctor_id";
echo "</div>";

if ($doctor_id == 0) {
    echo "<div class='card'><h2>My Appointments</h2><p>You are not registered as a doctor in the system.</p></div>";
} else {
    // Fetch only this doctor's appointments
    $appointmentsQuery = "SELECT * FROM appointments WHERE doctor_id = $doctor_id ORDER BY appointment_date DESC";
    $appointmentsResult = mysqli_query($conn, $appointmentsQuery);

    if (!$appointmentsResult) {
        echo "<div class='card'><h2>Error</h2><p>Query Error: " . mysqli_error($conn) . "</p></div>";
    }

    $appointments_list = [];
    while ($row = mysqli_fetch_assoc($appointmentsResult)) {
        $appointments_list[] = $row;
    }
    ?>

    <div class="card">
        <h2>My Appointments (Total: <?php echo count($appointments_list); ?>)</h2>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Patient ID</th>
                    <th>Date</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($appointments_list) > 0): ?>
                    <?php foreach ($appointments_list as $a): ?>
                        <tr>
                            <td><?php echo intval($a['id']); ?></td>
                            <td><?php echo intval($a['patient_id']); ?></td>
                            <td><?php echo htmlspecialchars($a['appointment_date']); ?></td>
                            <td><?php echo htmlspecialchars($a['status']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="4">No appointments found</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

<?php } ?>

<?php include("../includes/footer.php"); ?>