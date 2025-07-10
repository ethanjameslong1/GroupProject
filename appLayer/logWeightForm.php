<?php
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
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Log Weight</title>
    <link rel="stylesheet" href="../styling/userdashboard.css">
</head>

<body>
    <div class="dashboard-card">
        <h2>Log Your Weight</h2>
        <form action="log_weight_action.php" method="POST">
            <div class="form-group">
                <label for="weight">Current Weight (lbs):</label>
                <input type="number" id="weight" name="weight" step="0.1" required>
            </div>
            <button type="submit" class="primary-btn">Save Weight</button>
            <a href="dashboard.php" class="secondary-btn-link">
                <button type="button" class="secondary-btn">Cancel</button>
            </a>
        </form>
    </div>
</body>

</html>
