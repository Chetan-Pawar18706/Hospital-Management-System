<?php
// Reuse header.php for consistent header/navbar and flash messages.
if (session_status() == PHP_SESSION_NONE) session_start();
include __DIR__ . '/header.php';

// Content wrapper (header.php already opened .container)
echo '<div style="display:flex;gap:20px;">';
echo '<div style="flex:1;">';