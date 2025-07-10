<?php
require_once '../../dbLayer/DB.php';
session_start();
ini_set('session.gc_maxlifetime', 60);
session_set_cookie_params(60);
if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > 60)) {
    session_unset();
    session_destroy();
    header("Location: ../index.html?msg=session_expired");
    exit;
}
$_SESSION['last_activity'] = time();

// Redirect if user is not logged in
if (empty($_SESSION['user_id'])) {
    header("Location: /../index.html?msg=unauthorized_access");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate weight input
    if (isset($_POST['weight']) && is_numeric($_POST['weight']) && $_POST['weight'] > 0) {
        $weight = (float)$_POST['weight'];
        $userId = $_SESSION['user_id'];

        // Use a generic query function if you have one, or get the PDO object
        $pdo = $_DB->getPDO();
        $sql = "INSERT INTO weight_log (user_ID, weight) VALUES (?, ?)";

        try {
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$userId, $weight]);

            header("Location: /dashboard.php?msg=weight_logged");
            exit;
        } catch (PDOException $e) {
            header("Location: /logWeightForm.php?msg=error");
            exit;
        }
    } else {
        header("Location: /logWeightForm.php?msg=invalid_input");
        exit;
    }
} else {
    header("Location: /dashboard.php");
    exit;
}
