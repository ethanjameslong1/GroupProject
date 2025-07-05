<?php
echo "✅ login.php runs!";

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

            if ($user['access_level'] == 3) {
                header("Location: viewUsers.php");
                exit;
            } else {
                header("Location: successfulLogin.html");
                exit;
            }
        } else {

            /*checks unhashed admin password*/
            $validatelogin = $_DB-> select( 
            "select * from users where (username = ? or email =?) and hashed_password = ?", [$usernameOrEmail,$usernameOrEmail,$password]);
            
            if($validatelogin != null)
                {
                     $row = $validatelogin[0];
                    $_SESSION['user_id'] = $row['user_id'];
                    $_SESSION['username'] = $row['username'];
                    $_SESSION['email'] = $row['email'];
                    $_SESSION['access_level'] = $row['access_level'];
                     if ($user['access_level'] == 3) {
                            header("Location: viewUsers.php");
                            exit;
                         } else {
                             header("Location: successfulLogin.html");
                            exit;
            }
       
                }
        }
    } else {
        header("Location: ../index.html?msg=invalid_credentials");
        exit;
    }
} else {
    header("Location: ../index.html");
    exit;
}



