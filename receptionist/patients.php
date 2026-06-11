<?php
include("../config/db.php");
include("../includes/layout.php");

// When Register button is clicked
if (isset($_POST["btnadd"])) {
    $name = $_POST["name"];
    $email = $_POST["email"];
    $password = $_POST["password"];
    $age = $_POST["age"];
    $gender = $_POST["gender"];
    $contact = $_POST["contact"];
    $role = 'patient';

    // Insert into users table
    $insertUserQuery = "INSERT INTO users (name, email, password, role) VALUES ('$name', '$email', '$password', '$role')";
    $userInserted = mysqli_query($conn, $insertUserQuery);

    if ($userInserted) {
        $uid = mysqli_insert_id($conn);
        
        // Insert into patients table
        $insertPatientQuery = "INSERT INTO patients (user_id, age, gender, contact) VALUES ($uid, '$age', '$gender', '$contact')";
        $patientInserted = mysqli_query($conn, $insertPatientQuery);

        if ($patientInserted) {
            echo "<script>alert('Patient added successfully!'); window.location.href = 'patients.php';</script>";
        }
    } else {
        echo "<script>alert('Error adding patient');</script>";
    }
}

// Delete patient
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $deleteQuery = "DELETE FROM patients WHERE id = $id";
    $deleteResult = mysqli_query($conn, $deleteQuery);
    
    if ($deleteResult) {
        echo "<script>alert('Patient deleted'); window.location.href = 'patients.php';</script>";
    }
}

// Edit patient
$edit_id = null;
$patient = null;
if (isset($_GET['edit'])) {
    $edit_id = $_GET['edit'];
    $editQuery = "SELECT p.*, u.name, u.email FROM patients p JOIN users u ON p.user_id = u.id WHERE p.id = $edit_id";
    $editResult = mysqli_query($conn, $editQuery);
    $patient = mysqli_fetch_assoc($editResult);
}

// Update patient
if (isset($_POST["btnupdate"])) {
    $id = $_POST["id"];
    $name = $_POST["name"];
    $email = $_POST["email"];
    $password = $_POST["password"];
    $age = $_POST["age"];
    $gender = $_POST["gender"];
    $contact = $_POST["contact"];

    // Get user_id first
    $getUserQuery = "SELECT user_id FROM patients WHERE id = $id";
    $getUserResult = mysqli_query($conn, $getUserQuery);
    $patRecord = mysqli_fetch_assoc($getUserResult);
    $uid = $patRecord['user_id'];

    // Update user table
    $updateUserQuery = "UPDATE users SET name = '$name', email = '$email'";
    if (!empty($password)) {
        $updateUserQuery .= ", password = '$password'";
    }
    $updateUserQuery .= " WHERE id = $uid";
    
    // Update patient table
    $updatePatientQuery = "UPDATE patients SET age = '$age', gender = '$gender', contact = '$contact' WHERE id = $id";

    if (mysqli_query($conn, $updateUserQuery) && mysqli_query($conn, $updatePatientQuery)) {
        echo "<script>alert('Patient updated successfully!'); window.location.href = 'patients.php';</script>";
    } else {
        echo "<script>alert('Error updating patient');</script>";
    }
}

// Fetch all patients
$patientsQuery = "SELECT p.id, u.name, u.email, p.age, p.gender, p.contact FROM patients p JOIN users u ON p.user_id = u.id ORDER BY u.name";
$patientsResult = mysqli_query($conn, $patientsQuery);
$patients_list = [];
while($row = mysqli_fetch_assoc($patientsResult)) {
    $patients_list[] = $row;
}
?>

<div class="card">
    <h2><?php echo isset($patient) ? 'Edit Patient' : 'Register New Patient'; ?></h2>
    
    <form method="POST" class="validate">
        <?php if (isset($patient)): ?>
            <input type="hidden" name="id" value="<?php echo $patient['id']; ?>">
        <?php endif; ?>

        <label>Name</label>
        <input name="name" placeholder="Name" value="<?php echo isset($patient) ? htmlspecialchars($patient['name']) : ''; ?>" required>

        <label>Email</label>
        <input name="email" placeholder="Email" type="email" value="<?php echo isset($patient) ? htmlspecialchars($patient['email']) : ''; ?>" required>

        <label><?php echo isset($patient) ? 'Password (optional)' : 'Password'; ?></label>
        <input name="password" type="password" placeholder="Password" <?php echo !isset($patient) ? 'required' : ''; ?>>

        <label>Age</label>
        <input name="age" placeholder="Age" value="<?php echo isset($patient) ? htmlspecialchars($patient['age']) : ''; ?>">

        <label>Gender</label>
        <input name="gender" placeholder="Gender" value="<?php echo isset($patient) ? htmlspecialchars($patient['gender']) : ''; ?>">

        <label>Contact</label>
        <input name="contact" placeholder="Contact" value="<?php echo isset($patient) ? htmlspecialchars($patient['contact']) : ''; ?>">

        <button type="submit" name="<?php echo isset($patient) ? 'btnupdate' : 'btnadd'; ?>" class="button">
            <?php echo isset($patient) ? 'Update Patient' : 'Register'; ?>
        </button>
        <?php if (isset($patient)): ?>
            <a href="patients.php" class="button" style="background: #6c757d; margin-left: 10px;">Cancel</a>
        <?php endif; ?>
    </form>
</div>

<div class="card">
    <h2>Patients List</h2>
    <table>
        <thead>
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Age</th>
                <th>Gender</th>
                <th>Contact</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($patients_list as $p): ?>
            <tr>
                <td><?php echo htmlspecialchars($p['name']); ?></td>
                <td><?php echo htmlspecialchars($p['email']); ?></td>
                <td><?php echo htmlspecialchars($p['age'] ?? 'N/A'); ?></td>
                <td><?php echo htmlspecialchars($p['gender'] ?? 'N/A'); ?></td>
                <td><?php echo htmlspecialchars($p['contact'] ?? 'N/A'); ?></td>
                <td>
                    <a href="?edit=<?php echo $p['id']; ?>" class="button" style="background: #007bff; padding: 6px 12px; font-size: 12px;">Edit</a>
                    <a href="?delete=<?php echo $p['id']; ?>" class="button" style="background: #dc3545; padding: 6px 12px; font-size: 12px;" onclick="return confirm('Delete this patient?');">Delete</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php include("../includes/footer.php"); ?>
