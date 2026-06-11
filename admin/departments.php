<?php
include("../config/db.php");
include("../includes/layout.php");

// When Add button is clicked
if (isset($_POST["btnadd"])) {
    $name = $_POST["name"];
    
    $insertQuery = "INSERT INTO departments (name) VALUES ('$name')";
    $insertResult = mysqli_query($conn, $insertQuery);

    if ($insertResult) {
        echo "<script>alert('Department added!'); window.location.href = 'departments.php';</script>";
    } else {
        echo "<script>alert('Error adding department');</script>";
    }
}

// Delete department
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $deleteQuery = "DELETE FROM departments WHERE id = $id";
    $deleteResult = mysqli_query($conn, $deleteQuery);
    
    if ($deleteResult) {
        echo "<script>alert('Department deleted'); window.location.href = 'departments.php';</script>";
    }
}

// Edit department
$edit_id = null;
$dept = null;
if (isset($_GET['edit'])) {
    $edit_id = $_GET['edit'];
    $editQuery = "SELECT * FROM departments WHERE id = $edit_id";
    $editResult = mysqli_query($conn, $editQuery);
    $dept = mysqli_fetch_assoc($editResult);
}

// Update department
if (isset($_POST["btnupdate"])) {
    $id = $_POST["id"];
    $name = $_POST["name"];

    $updateQuery = "UPDATE departments SET name = '$name' WHERE id = $id";

    if (mysqli_query($conn, $updateQuery)) {
        echo "<script>alert('Department updated!'); window.location.href = 'departments.php';</script>";
    } else {
        echo "<script>alert('Error updating department');</script>";
    }
}

// Fetch all departments
$deptsQuery = "SELECT id, name FROM departments ORDER BY name";
$deptsResult = mysqli_query($conn, $deptsQuery);
$depts_list = [];
while($row = mysqli_fetch_assoc($deptsResult)) {
    $depts_list[] = $row;
}
?>

<div class="card">
    <h2><?php echo isset($dept) ? 'Edit Department' : 'Add New Department'; ?></h2>
    
    <form method="POST" class="validate">
        <?php if (isset($dept)): ?>
            <input type="hidden" name="id" value="<?php echo $dept['id']; ?>">
        <?php endif; ?>

        <label>Department Name</label>
        <input name="name" placeholder="Department Name" value="<?php echo isset($dept) ? htmlspecialchars($dept['name']) : ''; ?>" required>
        
        <button type="submit" name="<?php echo isset($dept) ? 'btnupdate' : 'btnadd'; ?>" class="button">
            <?php echo isset($dept) ? 'Update Department' : 'Add Department'; ?>
        </button>
        <?php if (isset($dept)): ?>
            <a href="departments.php" class="button" style="background: #6c757d; margin-left: 10px;">Cancel</a>
        <?php endif; ?>
    </form>
</div>

<div class="card">
    <h2>Departments List</h2>
    <table>
        <thead>
            <tr>
                <th>Department Name</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($depts_list as $d): ?>
            <tr>
                <td><?php echo htmlspecialchars($d['name']); ?></td>
                <td>
                    <a href="?edit=<?php echo $d['id']; ?>" class="button" style="background: #007bff; padding: 6px 12px; font-size: 12px;">Edit</a>
                    <a href="?delete=<?php echo $d['id']; ?>" class="button" style="background: #dc3545; padding: 6px 12px; font-size: 12px;" onclick="return confirm('Delete this department?');">Delete</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php include("../includes/footer.php"); ?>
