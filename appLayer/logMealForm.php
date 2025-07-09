<?php

session_start();

if (empty($_SESSION['user_id']) || empty($_SESSION['username']) || empty($_SESSION['access_level'])) 
{
    header("Location: ../index.html?msg=unauthorized_access");
    exit;
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>Log Meal</title>
    <link rel="stylesheet" href="../styling/logMealForm.css" />
</head>
<body>
    <div class="form-card">
        <h2>Log New Meal</h2>

        <?php if (isset($_GET['msg']) && $_GET['msg'] === 'error'): ?>
            <p style="color: red; text-align: center;">
                Something went wrong. Please try again.
            </p>
        <?php endif; ?>

        <form action="addMeal.php" method="POST">
            <label for="name">Meal Name</label>
            <input type="text" name="name" id="name" required />

            <label for="mealType">Meal Type</label>
            <select name="mealType" id="mealType" required>
                <option value="">-- Select --</option>
                <option value="breakfast">Breakfast</option>
                <option value="lunch">Lunch</option>
                <option value="dinner">Dinner</option>
            </select>

            <label for="calories">Calories</label>
            <input type="number" name="calories" id="calories" required min="0" />

            <label for="timeEaten">Date Eaten</label>
            <input
                type="date"
                name="timeEaten"
                id="timeEaten"
                required
                value="<?= date('Y-m-d') ?>"
            />

            <button type="submit">Save Meal</button>
        </form>
    </div>
</body>
</html>
