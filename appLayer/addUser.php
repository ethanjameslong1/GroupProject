<?php
require_once '../dbLayer/UserModel.php';


if (
    isset($_POST['username'], $_POST['email'], $_POST['access_level']) &&
        filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)
) {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $accessLevel = intval($_POST['access_level']);

    $model = new UserModel();
    try {
        $model->addUser($username, $email, $accessLevel); // You must add this method to your model
        // Redirect to viewUsers.php, assuming it will also be in appLayer or accessible from there.
        // Adjust this path if viewUsers.php is located elsewhere.
        header("Location: viewUsers.php?msg=added");
        exit();
    } catch (Exception $e) {
        header("Location: viewUsers.php?msg=error");
        exit();
    }
} else {
    header("Location: viewUsers.php?msg=invalid_input");
    exit();
}
