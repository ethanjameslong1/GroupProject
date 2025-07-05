<?php
require_once '../dbLayer/DB.php';


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    $_DB = new DB();

    $checkemail = $_DB->select(
        "select count(user_id) as count from users where email = ?", [$email]
    );

    $emailrow = $checkemail[0];

    if ($emailrow["count"] > 0) {
        header("Location: registerAccount.html?msg=email_exists");
        exit;
    }

    $checkuser = $_DB->select(
        "select count(user_id) as count from users where username = ?", [$username]
    );

    $userrow = $checkuser[0];

    if ($userrow["count"] > 0) {
        header("Location: registerAccount.html?msg=username_exists");
        exit;
    }

    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    try {
        echo "trying to access db pdo";
        $pdo = $_DB->getPDO(); // Access your PDO instance from the DB class
        $stmt = $pdo->prepare("INSERT INTO users (username, email, hashed_password) VALUES (?,?, ?)");
        $stmt->execute([$username,$email, $hashedPassword]);
        echo "Trying to go to index";
        header("Location: ../index.html");
        exit;
    } catch (PDOException $e) {
        header("Location: registerAccount.html?msg=registration_failed");
        exit;
    }
}

