<?php
session_start();

require_once '../dbLayer/DB.php';


if (
    isset($_SESSION['last_activity']) &&
    (time() - $_SESSION['last_activity'] > 60)
) {
    session_unset();
    session_destroy();
    header("Location: ../index.html?msg=session_expired");
    exit;
}


if (
    empty($_SESSION['user_id']) ||
    empty($_SESSION['username']) ||
    empty($_SESSION['access_level'])
) {
    header("Location: ../index.html?msg=unauthorized_access");
    exit;
}


$mealName = trim($_POST['name']);
$mealType = trim($_POST['mealType']);
$calories = trim($_POST['calories']);
$timeEaten = trim($_POST['timeEaten']);

if (empty($mealName) || empty($mealType) || empty($timeEaten)) {
    header("Location: logMealForm.php?msg=error");
    exit;
}


$allowedTypes = ['breakfast', 'lunch', 'dinner'];
if (!in_array($mealType, $allowedTypes)) {
    header("Location: logMealForm.php?msg=error");
    exit;
}

$userID = $_SESSION['user_id'];

try {
    $sql = "INSERT INTO meal (user_ID, name, mealType, calories, timeEaten) VALUES (?, ?, ?, ?, ?)
    ";

    $_DB->select($sql, [$userID, $mealName,$mealType,$calories,$timeEaten]);

    header("Location: dashboard.php?msg=meal_logged");
    exit;

} catch (Exception $e) {
    error_log("Meal insert error: " . $e->getMessage());
    header("Location: logMealForm.php?msg=error");
    exit;
}
?>
