<?php
require_once '../dbLayer/UserModel.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: viewUsers.php?msg=invalid_id");
    exit();
}

$userId = intval($_GET['id']);
$model = new UserModel();

try {
    $model->deleteUser($userId);
    // Redirect with success message.
    // Assuming viewUsers.php will also be in the 'appLayer' directory.
    header("Location: viewUsers.php?msg=deleted");
    exit();
} catch (Exception $e) {
    // Redirect with error message.
    // Assuming viewUsers.php will also be in the 'appLayer' directory.
    header("Location: viewUsers.php?msg=error");
    exit();
}
