<?php
require_once '../dbLayer/UserModel.php';

$model = new UserModel();

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: viewUsers.php?msg=no_user_id");
    exit();
}

$userId = intval($_GET['id']);
$user = $model->getUserById($userId);

if (!$user) {
    header("Location: viewUsers.php?msg=user_not_found");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Basic validation for POST data
    if (isset($_POST['user_id'], $_POST['username'], $_POST['email'], $_POST['access_level']) &&
        filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {

        $updatedUserId = intval($_POST['user_id']);
        $username = trim($_POST['username']);
        $email = trim($_POST['email']);
        $accessLevel = intval($_POST['access_level']);

        try {
            $model->updateUser($updatedUserId, $username, $email, $accessLevel);
            header("Location: viewUsers.php?msg=updated");
            exit();
        } catch (Exception $e) {
            header("Location: viewUsers.php?msg=update_error");
            exit();
        }
    } else {
        header("Location: viewUsers.php?msg=invalid_input");
        exit();
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Edit User</title>
    <link rel="stylesheet" href="../styling/edit-user.css">
</head>
<body>
    <div class="edit-container">
        <h2>Edit User</h2>
        <form method="POST">
            <input type="hidden" name="user_id" value="<?= htmlspecialchars($user['user_id']) ?>">

            <label for="username">Username:</label>
            <input type="text" name="username" id="username" value="<?= htmlspecialchars($user['username']) ?>" required>

            <label for="email">Email:</label>
            <input type="email" name="email" id="email" value="<?= htmlspecialchars($user['email']) ?>" required>

            <label for="access_level">Access Level:</label>
            <select name="access_level" id="access_level">
                <option value="1" <?= $user['access_level'] == 1 ? 'selected' : '' ?>>User</option>
                <option value="3" <?= $user['access_level'] == 3 ? 'selected' : '' ?>>Admin</option>
                <option value="0" <?= $user['access_level'] == 0 ? 'selected' : '' ?>>Inactive</option>
            </select>

            <button type="submit" class="submit-btn">Save</button>
        </form>
    </div>
</body>
</html>/html>
