<?php
include("../config/db.php");
include("../includes/auth_check.php");
include("../includes/layout.php");

// When button is clicked, save to database
if (isset($_POST["btnadd"])) {
    $name = $_POST["name"];
    $email = $_POST["email"];
    $password = $_POST["password"];
    $role = $_POST["role"];

    $insertQuery = "INSERT INTO users (name, email, password, role) VALUES ('$name', '$email', '$password', '$role')";
    $insertResult = mysqli_query($conn, $insertQuery);

    if ($insertResult) {
        echo "<script>alert('User added!'); window.location.href = 'users.php';</script>";
    } else {
        echo "<script>alert('Error adding user');</script>";
    }
}

// Delete user
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $deleteQuery = "DELETE FROM users WHERE id = $id";
    $deleteResult = mysqli_query($conn, $deleteQuery);
    
    if ($deleteResult) {
        echo "<script>alert('User deleted'); window.location.href = 'users.php';</script>";
    }
}

// Edit user
$edit_id = null;
$user = null;
if (isset($_GET['edit'])) {
    $edit_id = $_GET['edit'];
    $editQuery = "SELECT * FROM users WHERE id = $edit_id";
    $editResult = mysqli_query($conn, $editQuery);
    $user = mysqli_fetch_assoc($editResult);
}

// Update user
if (isset($_POST["btnupdate"])) {
    $id = $_POST["id"];
    $name = $_POST["name"];
    $email = $_POST["email"];
    $role = $_POST["role"];
    
    if (!empty($_POST["password"])) {
        $password = $_POST["password"];
        $updateQuery = "UPDATE users SET name = '$name', email = '$email', password = '$password', role = '$role' WHERE id = $id";
    } else {
        $updateQuery = "UPDATE users SET name = '$name', email = '$email', role = '$role' WHERE id = $id";
    }

    if (mysqli_query($conn, $updateQuery)) {
        echo "<script>alert('User updated!'); window.location.href = 'users.php';</script>";
    } else {
        echo "<script>alert('Error updating user');</script>";
    }
}

// Fetch all users
$usersQuery = "SELECT id, name, email, role FROM users ORDER BY id DESC";
$usersResult = mysqli_query($conn, $usersQuery);
$users_list = [];
while($row = mysqli_fetch_assoc($usersResult)) {
    $users_list[] = $row;
}
?>

<div class="card">
    <h2><?php echo isset($user) ? 'Edit User' : 'Add User'; ?></h2>

    <form method="POST" class="validate">
        <?php if (isset($user)): ?>
            <input type="hidden" name="id" value="<?php echo $user['id']; ?>">
        <?php endif; ?>

        <label>Name</label>
        <input name="name" placeholder="Name" value="<?php echo isset($user) ? htmlspecialchars($user['name']) : ''; ?>" required>

        <label>Email</label>
        <input name="email" type="email" placeholder="Email" value="<?php echo isset($user) ? htmlspecialchars($user['email']) : ''; ?>" required>

        <label>Password <?php echo isset($user) ? '(leave blank to keep current)' : ''; ?></label>
        <input name="password" type="password" placeholder="Password" <?php echo !isset($user) ? 'required' : ''; ?>>

        <label>Role</label>
        <select name="role" required>
            <option value="admin" <?php echo (isset($user) && $user['role'] === 'admin') ? 'selected' : ''; ?>>Admin</option>
            <option value="doctor" <?php echo (isset($user) && $user['role'] === 'doctor') ? 'selected' : ''; ?>>Doctor</option>
            <option value="receptionist" <?php echo (isset($user) && $user['role'] === 'receptionist') ? 'selected' : ''; ?>>Receptionist</option>
            <option value="patient" <?php echo (isset($user) && $user['role'] === 'patient') ? 'selected' : ''; ?>>Patient</option>
        </select>

        <button type="submit" name="<?php echo isset($user) ? 'btnupdate' : 'btnadd'; ?>" class="button">
            <?php echo isset($user) ? 'Update User' : 'Add User'; ?>
        </button>
        <?php if (isset($user)): ?>
            <a href="users.php" class="button" style="background: #6c757d; margin-left: 10px;">Cancel</a>
        <?php endif; ?>
    </form>
</div>

<div class="card">
    <h2>Users List</h2>
    <table>
        <thead>
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Role</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($users_list as $u): ?>
            <tr>
                <td><?php echo htmlspecialchars($u['name']); ?></td>
                <td><?php echo htmlspecialchars($u['email']); ?></td>
                <td><?php echo htmlspecialchars($u['role']); ?></td>
                <td>
                    <a href="?edit=<?php echo $u['id']; ?>" class="button" style="background: #007bff; color: #fff; padding: 6px 12px; font-size: 12px;">Edit</a>
                    <a href="?delete=<?php echo $u['id']; ?>" class="button" style="background: #dc3545; padding: 6px 12px; font-size: 12px;" onclick="return confirm('Delete this user?');">Delete</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php include("../includes/footer.php"); ?>
