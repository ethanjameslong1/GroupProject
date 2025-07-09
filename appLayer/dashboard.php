<?php
require_once '../dbLayer/DB.php';
session_start();
// Your daily calorie goal (hard-coded for now)

if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > 60)) 
{
    session_unset();
    session_destroy();
    header("Location: ../index.html?msg=session_expired");
    exit;
}

if (empty($_SESSION['user_id']) || empty($_SESSION['username']) || empty($_SESSION['access_level'])) 
{
    header("Location: ../index.html?msg=unauthorized_access");
    exit;
}


$dailyGoal = 1800;


$totalConsumed = 0;
$remaining = $dailyGoal;
$mealsToday = [];


$sql = "SELECT name, mealType, calories, timeEaten FROM meal WHERE user_ID = ? AND DATE(timeEaten) = CURDATE()";

$mealsToday = $_DB->select($sql, [$_SESSION['user_id']]);

// Calculate totals
foreach ($mealsToday as $meal) {
    $totalConsumed += $meal['calories'];
}

$remaining = $dailyGoal - $totalConsumed;
if ($remaining < 0) {
    $remaining = 0;
}

?>
<html>

    <head>
        <meta charset="UTF-8">
        <link rel="stylesheet" href="../styling/userdashboard.css">
        <title>Dashboard</title>
    </head>
    <body>
        <div class="dashboard-card">
            <h2>Dashboard</h2>

            <p class="goal">Daily Goal: <strong>1800 Calories</strong></p>

            <div class="calorie-row">
                <div>
                    <p class="label">Consumed:</p>
                    <p class="number">950</p>
                </div>
                <div>
                    <p class="label">Remaining:</p>
                    <p class="number green">850</p>
                </div>
            </div>

            <hr>

            <h3>Today's Meals</h3>

            <?php if (empty($mealsToday)): ?>
                <p style="color: #777; text-align: center;">No meals logged today.</p>
            <?php else: ?>
                <ul class="meal-list">
                    <?php foreach ($mealsToday as $Meal): ?>
                        <li>
                            <?= htmlspecialchars(ucfirst($Meal['mealType'])) ?>:
                            <?= htmlspecialchars($Meal['name']) ?> –
                            <?= $Meal['calories'] ?> calories
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>

            <a href="logMealForm.php">
                <button class="primary-btn">+ Log New Meal</button>
            </a>
            <button class="secondary-btn">View Progress Graph</button>
            <button class="secondary-btn">Update Weight</button>
        </div>

    </body>
</html>
