<?php
include("../config/db.php");
include("../includes/auth_check.php");
include("../includes/layout.php");

$uid = $_SESSION['user_id'];

// When update button is clicked
if (isset($_POST["btnupdate"])) {
    $name = $_POST["name"];
    $email = $_POST["email"];

    $updateQuery = "UPDATE users SET name = '$name', email = '$email' WHERE id = $uid";

    if (mysqli_query($conn, $updateQuery)) {
        echo "<script>alert('Profile updated!'); window.location.href = 'profile.php';</script>";
    } else {
        echo "<script>alert('Error updating profile');</script>";
    }
}

// Fetch user profile
$profileQuery = "SELECT id, name, email FROM users WHERE id = $uid LIMIT 1";
$profileResult = mysqli_query($conn, $profileQuery);
$user = mysqli_fetch_assoc($profileResult);
?>

<div class="card">
    <h2>Update Profile</h2>

    <form method="POST" class="validate">
        <label>Name</label>
        <input name="name" value="<?php echo htmlspecialchars($user['name']); ?>" required>

        <label>Email</label>
        <input name="email" value="<?php echo htmlspecialchars($user['email']); ?>" type="email" required>

        <button type="submit" name="btnupdate" class="button">Update</button>
    </form>
</div>

<?php include("../includes/footer.php"); ?>
