<!DOCTYPE html>
<html>
<head>
    <title>Hospital Management System</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <meta name="viewport" content="width=device-width,initial-scale=1">
</head>
<body>
<?php if (session_status() == PHP_SESSION_NONE) session_start(); ?>
<?php include __DIR__ . '/nav.php'; ?>

<div class="container">

    <?php if (!empty($_SESSION['flash'])): ?>
        <div class="card">
            <div class="success"><?php echo htmlspecialchars($_SESSION['flash']); ?></div>
        </div>
        <?php unset($_SESSION['flash']); ?>
    <?php endif; ?>

    <script src="../assets/js/script.js"></script>
