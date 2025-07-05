<!DOCTYPE html>
<html lang="en">

  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Successful</title>
  </head>

  <body>
    <h1>Successful Login!</h1>
    <p>Welcome, <span id="username-display"></span>!</p>
    <p>Email: <span id="email-display"></span></p>

    <p>You will be redirected shortly...</p>

    <script>
    <? php
      session_start();

    ini_set('session.gc_maxlifetime', 60);
    session_set_cookie_params(60);
    if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > 60)) {
      session_unset();
      session_destroy();
      header("Location: login.html?msg=session_expired"); // Redirect to login with a message
      exit;
    }
    $_SESSION['last_activity'] = time();

    $displayUsername = $_SESSION['username'];
    $displayEmail = isset$_SESSION['email'];
    ?>

    document.getElementById('username-display').textContent = '<?php echo $displayUsername; ?>';
    document.getElementById('email-display').textContent = '<?php echo $displayEmail; ?>';

    </script>
  </body>

</html>
