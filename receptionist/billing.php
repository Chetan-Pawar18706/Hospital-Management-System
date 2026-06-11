<?php
include("../config/db.php");
include("../includes/layout.php");

// When Generate Bill button is clicked
if (isset($_POST["btnadd"])) {
    $patient = $_POST["patient"];
    $amount = $_POST["amount"];

    $insertQuery = "INSERT INTO bills (patient_id, amount, bill_date) VALUES ($patient, $amount, CURDATE())";
    $insertResult = mysqli_query($conn, $insertQuery);

    if ($insertResult) {
        echo "<script>alert('Bill generated!'); window.location.href = 'billing.php';</script>";
    } else {
        echo "<script>alert('Error generating bill');</script>";
    }
}

// Delete bill
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $deleteQuery = "DELETE FROM bills WHERE id = $id";
    $deleteResult = mysqli_query($conn, $deleteQuery);
    
    if ($deleteResult) {
        echo "<script>alert('Bill deleted'); window.location.href = 'billing.php';</script>";
    }
}

// Edit bill
$edit_id = null;
$bill = null;
if (isset($_GET['edit'])) {
    $edit_id = $_GET['edit'];
    $editQuery = "SELECT * FROM bills WHERE id = $edit_id";
    $editResult = mysqli_query($conn, $editQuery);
    $bill = mysqli_fetch_assoc($editResult);
}

// Update bill
if (isset($_POST["btnupdate"])) {
    $id = $_POST["id"];
    $patient = $_POST["patient"];
    $amount = $_POST["amount"];

    $updateQuery = "UPDATE bills SET patient_id = $patient, amount = $amount WHERE id = $id";

    if (mysqli_query($conn, $updateQuery)) {
        echo "<script>alert('Bill updated!'); window.location.href = 'billing.php';</script>";
    } else {
        echo "<script>alert('Error updating bill');</script>";
    }
}

// Fetch all bills
$billsQuery = "SELECT id, patient_id, amount, bill_date FROM bills ORDER BY bill_date DESC";
$billsResult = mysqli_query($conn, $billsQuery);
$bills_list = [];
while($row = mysqli_fetch_assoc($billsResult)) {
    $bills_list[] = $row;
}
?>

<div class="card">
    <h2><?php echo isset($bill) ? 'Edit Bill' : 'Generate Bill'; ?></h2>

    <form method="POST" class="validate">
        <?php if (isset($bill)): ?>
            <input type="hidden" name="id" value="<?php echo $bill['id']; ?>">
        <?php endif; ?>

        <label>Patient ID</label>
        <input name="patient" placeholder="Patient ID" value="<?php echo isset($bill) ? $bill['patient_id'] : ''; ?>" required>

        <label>Amount</label>
        <input name="amount" type="number" step="0.01" placeholder="Amount" value="<?php echo isset($bill) ? $bill['amount'] : ''; ?>" required>

        <button type="submit" name="<?php echo isset($bill) ? 'btnupdate' : 'btnadd'; ?>" class="button">
            <?php echo isset($bill) ? 'Update Bill' : 'Generate Bill'; ?>
        </button>
        <?php if (isset($bill)): ?>
            <a href="billing.php" class="button" style="background: #6c757d; margin-left: 10px;">Cancel</a>
        <?php endif; ?>
    </form>
</div>

<div class="card">
    <h2>Bills List</h2>
    <table>
        <thead>
            <tr>
                <th>Patient ID</th>
                <th>Amount</th>
                <th>Bill Date</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($bills_list as $b): ?>
            <tr>
                <td><?php echo intval($b['patient_id']); ?></td>
                <td>$<?php echo number_format($b['amount'], 2); ?></td>
                <td><?php echo htmlspecialchars($b['bill_date']); ?></td>
                <td>
                    <a href="?edit=<?php echo $b['id']; ?>" class="button" style="background: #007bff; padding: 6px 12px; font-size: 12px;">Edit</a>
                    <a href="?delete=<?php echo $b['id']; ?>" class="button" style="background: #dc3545; padding: 6px 12px; font-size: 12px;" onclick="return confirm('Delete this bill?');">Delete</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php include("../includes/footer.php"); ?>
