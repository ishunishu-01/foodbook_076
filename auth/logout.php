<?php
// ============================================================
// FoodBook - Logout
// Student: Ishara Nishshanka | ITT/2021/076
// ============================================================
session_start();
session_unset();
session_destroy();
header('Location: ../index.php');
exit();
?>
