<?php
session_start();

require_once '../dbLayer/DB.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $usernameOrEmail = trim($_POST['username']);
    $password = trim($_POST['password']);

    $sql = "SELECT user_id, username, email, hashed_password, access_level FROM users WHERE username = ? OR email = ?";
    $userResult = $_DB->select($sql, [$usernameOrEmail, $usernameOrEmail]);

    if (!empty($userResult) && count($userResult) === 1) {
        $user = $userResult[0];

        if (password_verify($password, $user['hashed_password'])) {
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['access_level'] = $user['access_level']; // Store the user's access level
            $_SESSION['last_activity'] = time();

            if ($user['access_level'] == 3) {
                header("Location: viewUsers.php");
                exit;
            } else {
                header("Location: successfulLogin.php");
                exit;
            }
        } else {
            header("Location: ../index.html?msg=invalid_credentials");
            exit;
        }
    } else {
        header("Location: ../index.html?msg=invalid_credentials");
        exit;
    }
} else {
    header("Location: ../index.html");
    exit;
}
?>

