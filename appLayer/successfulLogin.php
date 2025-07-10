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
$displayUsername = $_SESSION['username'] ?? 'Guest';
$displayEmail = $_SESSION['email'] ?? 'N/A';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Successful</title>
</head>

<body>
    <h1>Successful Login!</h1>
    <p>Welcome, <span id="username-display"><?php echo htmlspecialchars($displayUsername); ?></span>!</p>
    <p>Email: <span id="email-display"><?php echo htmlspecialchars($displayEmail); ?></span></p>

    <p>You will be redirected shortly...</p>
    <meta http-equiv="refresh" content="3; url = dashboard.php" />

</body>

</html>
