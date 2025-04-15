<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

if ($_SESSION['is_admin']) {
    header('Location: dashboard_admin.php');
} else {
    header('Location: dashboard.php');
}
exit;
