<?php
include("../config/db.php");
include("../includes/layout.php");

// When Register button is clicked, save doctor to database
if (isset($_POST["btnadd"])) {
    $name = $_POST["name"];
    $email = $_POST["email"];
    $password = $_POST["password"];
    $dept = $_POST["dept"];
    $spec = $_POST["spec"];
    $role = 'doctor';

    // Insert into users table
    $insertUserQuery = "INSERT INTO users (name, email, password, role) VALUES ('$name', '$email', '$password', '$role')";
    $userInserted = mysqli_query($conn, $insertUserQuery);

    if ($userInserted) {
        $uid = mysqli_insert_id($conn);

        // Insert into doctors table
        $insertDoctorQuery = "INSERT INTO doctors (user_id, department_id, specialization) VALUES ($uid, $dept, '$spec')";
        $doctorInserted = mysqli_query($conn, $insertDoctorQuery);

        if ($doctorInserted) {
            echo "<script>alert('Doctor added successfully!'); window.location.href = 'doctors.php';</script>";
        } else {
            echo "<script>alert('Error creating doctor record');</script>";
        }
    } else {
        echo "<script>alert('Doctor not added');</script>";
    }
}

// Delete doctor
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $deleteQuery = "DELETE FROM doctors WHERE id = $id";
    $deleteResult = mysqli_query($conn, $deleteQuery);

    if ($deleteResult) {
        echo "<script>alert('Doctor deleted'); window.location.href = 'doctors.php';</script>";
    }
}

// Edit doctor
$edit_id = null;
$doctor = null;
if (isset($_GET['edit'])) {
    $edit_id = $_GET['edit'];
    $editQuery = "SELECT d.*, u.name, u.email FROM doctors d JOIN users u ON d.user_id = u.id WHERE d.id = $edit_id";
    $editResult = mysqli_query($conn, $editQuery);
    $doctor = mysqli_fetch_assoc($editResult);
}

// Update doctor
if (isset($_POST["btnupdate"])) {
    $id = $_POST["id"];
    $name = $_POST["name"];
    $email = $_POST["email"];
    $password = $_POST["password"];
    $dept = $_POST["dept"];
    $spec = $_POST["spec"];

    // Get user_id first
    $getUserQuery = "SELECT user_id FROM doctors WHERE id = $id";
    $getUserResult = mysqli_query($conn, $getUserQuery);
    $docRecord = mysqli_fetch_assoc($getUserResult);
    $uid = $docRecord['user_id'];

    // Update user table
    $updateUserQuery = "UPDATE users SET name = '$name', email = '$email'";
    if (!empty($password)) {
        $updateUserQuery .= ", password = '$password'";
    }
    $updateUserQuery .= " WHERE id = $uid";

    // Update doctor table
    $updateDoctorQuery = "UPDATE doctors SET department_id = $dept, specialization = '$spec' WHERE id = $id";

    if (mysqli_query($conn, $updateUserQuery) && mysqli_query($conn, $updateDoctorQuery)) {
        echo "<script>alert('Doctor updated successfully!'); window.location.href = 'doctors.php';</script>";
    } else {
        echo "<script>alert('Error updating doctor');</script>";
    }
}

// Fetch all doctors
$doctorsQuery = "SELECT d.id, u.name, u.email, d.specialization, d.department_id FROM doctors d JOIN users u ON d.user_id = u.id ORDER BY u.name";
$doctorsResult = mysqli_query($conn, $doctorsQuery);
$doctors_list = [];
while ($row = mysqli_fetch_assoc($doctorsResult)) {
    $doctors_list[] = $row;
}
?>

<div class="card">
    <h2><?php echo isset($doctor) ? 'Edit Doctor' : 'Add New Doctor'; ?></h2>

    <form method="POST" class="validate">
        <?php if (isset($doctor)): ?>
            <input type="hidden" name="id" value="<?php echo $doctor['id']; ?>">
        <?php endif; ?>

        <label>Doctor Name</label>
        <input name="name" placeholder="Doctor Name"
            value="<?php echo isset($doctor) ? htmlspecialchars($doctor['name']) : ''; ?>" required>

        <label>Email</label>
        <input name="email" placeholder="Email" type="email"
            value="<?php echo isset($doctor) ? htmlspecialchars($doctor['email']) : ''; ?>" required>

        <label><?php echo isset($doctor) ? 'Password (optional)' : 'Password'; ?></label>
        <input name="password" type="password" placeholder="Password" <?php echo !isset($doctor) ? 'required' : ''; ?>>

        <label>Department ID</label>
        <input name="dept" type="number" placeholder="Department ID"
            value="<?php echo isset($doctor) ? $doctor['department_id'] : ''; ?>">

        <label>Specialization</label>
        <input name="spec" placeholder="Specialization"
            value="<?php echo isset($doctor) ? htmlspecialchars($doctor['specialization']) : ''; ?>">

        <button type="submit" name="<?php echo isset($doctor) ? 'btnupdate' : 'btnadd'; ?>" class="button">
            <?php echo isset($doctor) ? 'Update Doctor' : 'Add Doctor'; ?>
        </button>
        <?php if (isset($doctor)): ?>
            <a href="doctors.php" class="button" style="background: #6c757d; margin-left: 10px;">Cancel</a>
        <?php endif; ?>
    </form>
</div>

<div class="card">
    <h2>Doctors List</h2>
    <table>
        <thead>
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Specialization</th>
                <th>Department ID</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($doctors_list as $d): ?>
                <tr>
                    <td><?php echo htmlspecialchars($d['name']); ?></td>
                    <td><?php echo htmlspecialchars($d['email']); ?></td>
                    <td><?php echo htmlspecialchars($d['specialization'] ?? 'N/A'); ?></td>
                    <td><?php echo intval($d['department_id']); ?></td>
                    <td>
                        <a href="?edit=<?php echo $d['id']; ?>" class="button"
                            style="background: #007bff; padding: 6px 12px; font-size: 12px;">Edit</a>
                        <a href="?delete=<?php echo $d['id']; ?>" class="button"
                            style="background: #dc3545; padding: 6px 12px; font-size: 12px;"
                            onclick="return confirm('Delete this doctor?');">Delete</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php include("../includes/footer.php"); ?>