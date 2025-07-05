<?php
// Path to UserModel.php, assuming it's in the 'dbLayer' directory,
// and this file (addUserForm.php) is in 'appLayer'.
require_once '../dbLayer/UserModel.php';
?>
<!DOCTYPE html>
<html>
<head>
    <title>Add New User</title>
    <!-- Link to the external stylesheet, assuming it's in the 'styling' directory,
         and this file (addUserForm.php) is in 'appLayer'. -->
    <link rel="stylesheet" href="../styling/add-user-form.css">
</head>
<body>
    <div class="form-container">
        <h2>Add New User</h2>
        <!-- The action for the form, assuming addUser.php is in the same 'appLayer' directory. -->
        <form action="addUser.php" method="POST">
            <label for="username">Username:</label>
            <input type="text" name="username" required>

            <label for="email">Email:</label>
            <input type="email" name="email" required>

            <label for="access_level">Access Level:</label>
            <select name="access_level" required>
                <option value="1">User (Active)</option>
                <option value="0">User (Inactive)</option>
                <option value="3">Admin</option>
            </select>

            <button type="submit" class="submit-btn">Add User</button>
        </form>
    </div>
</body>
</html>/html>
