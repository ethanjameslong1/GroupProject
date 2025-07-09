<?php
    require_once '../dbLayer/DB.php';
    
    session_start();
    
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
    
    $sql = "SELECT COUNT(*) AS total_meals FROM meal";
    
    $result =$_DB->select($sql);
    
    $totalMeals = $result[0]['total_meals'];
    
    $sql = "SELECT COUNT(mealType) as common_type, mealType FROM meal GROUP BY mealType ORDER BY common_type DESC";
    
    $breakdown =$_DB->select($sql);
    
    $commonType = $breakdown[0]['mealType'];
    
?>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Activity Overview</title>
        <link rel="stylesheet" href="../styling/add-user-form.css">
    </head>
    <body>
        <div class="form-container">
            <h2>Activity Overview</h2>
            <br>
            <h4>Total Number of Meals Entered: <?= $totalMeals ?> </h4><br>
            <h4>The Most Common Meal Type is: <?= $commonType ?> </h4><br>
            <h4>Breakdown of Meal Types:</h4>
            <ul>
                <?php foreach ($breakdown as $row): ?>
                <li>
                    <?= htmlspecialchars(ucfirst($row['mealType'])) ?>: <?= $row['common_type'] ?>
                </li>
                <?php endforeach; ?>
            </ul>
        </div>
    </body>
</html>
