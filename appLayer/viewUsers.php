<?php
session_start();

if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > 60)) {
    session_unset();
    session_destroy();
    header("Location: ../index.html?msg=session_expired");
    exit;
}

$_SESSION['last_activity'] = time();
if (!isset($_SESSION['user_id']) || !isset($_SESSION['access_level'])) {
    header("Location: ../index.html?msg=unauthorized_access");
    exit;
}

if ($_SESSION['access_level'] != 3) {
    header("Location: successfulLogin.html?msg=access_denied");
    exit;
}

require_once '../dbLayer/UserModel.php';

$model = new UserModel();
$users = $model->getAllUsers();

$message = '';
if (isset($_GET['msg'])) {
    switch ($_GET['msg']) {
        case 'added':
            $message = "New user successfully added.";
            break;
        case 'invalid_input':
            $message = "Invalid input data.";
            break;
        case 'error':
            $message = "Something went wrong while adding the user.";
            break;
        case 'deleted':
            $message = "User deleted successfully.";
            break;
        case 'updated':
            $message = "User updated successfully.";
            break;
        case 'invalid_id':
            $message = "Invalid user ID provided.";
            break;
        case 'user_not_found':
            $message = "User not found.";
            break;
        case 'access_toggled':
            $message = "User access level toggled successfully.";
            break;
        case 'toggle_error':
            $message = "Failed to toggle user access.";
            break;
        case 'no_user_id':
            $message = "No user ID specified for editing.";
            break;
        case 'update_error':
            $message = "Failed to update user.";
            break;
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>User Management</title>
    <link rel="stylesheet" href="../styling/view-users.css">
</head>
<body>
    <div id="messageBox" class="custom-message-box-overlay">
        <div class="custom-message-box-content">
            <p id="messageText"></p>
            <div class="custom-message-box-actions">
                <button id="confirmBtn" onclick="confirmAction()">Yes</button>
                <button id="cancelBtn" onclick="cancelAction()">No</button>
                <button id="okBtn" onclick="closeMessageBox()">OK</button>
            </div>
        </div>
    </div>

    <h1>User Management</h1>

    <?php if (!empty($message)): ?>
        <div class="message"><?= htmlspecialchars($message) ?></div>
    <?php endif; ?>

    <div class="table-container">
        <div class="top-bar">
            <!-- Link to addUserForm.php, assuming it's in the same 'appLayer' directory -->
            <a href="addUserForm.php" class="add-user-btn">+ Add User</a>
        </div>

        <table>
            <tr>
                <th>Username</th>
                <th>Email</th>
                <th>Access Level</th>
                <th>Status</th>
                <th>Created At</th>
                <th>Actions</th>
            </tr>
            <?php foreach ($users as $user): ?>
            <tr>
                <td><?= htmlspecialchars($user['username']) ?></td>
                <td><?= htmlspecialchars($user['email']) ?></td>
                <td><?= $user['access_level'] == 3 ? 'Admin' : 'User' ?></td>
                <td><?= $user['access_level'] == 0 ? 'Inactive' : 'Active' ?></td>
                <td><?= htmlspecialchars($user['created_at']) ?></td>
                <td>
                    <div class="action-group">
                        <a href="editUser.php?id=<?= $user['user_id'] ?>" class="action-btn">Edit</a>
                        <a href="#" class="action-btn delete-btn" data-id="<?= $user['user_id'] ?>">Delete</a>
                        <a href="toggleAccess.php?id=<?= $user['user_id'] ?>" class="action-btn">
                            <?= $user['access_level'] == 0 ? 'Activate' : 'Deactivate' ?>
                        </a>
                    </div>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
    </div>

    <script>
        let currentDeleteUrl = '';

        function showMessageBox(message, type = 'alert', confirmUrl = '') {
            document.getElementById('messageText').innerText = message;
            const confirmBtn = document.getElementById('confirmBtn');
            const cancelBtn = document.getElementById('cancelBtn');
            const okBtn = document.getElementById('okBtn');

            if (type === 'confirm') {
                confirmBtn.style.display = 'inline-block';
                cancelBtn.style.display = 'inline-block';
                okBtn.style.display = 'none';
                currentDeleteUrl = confirmUrl;
            } else { // type === 'alert'
                confirmBtn.style.display = 'none';
                cancelBtn.style.display = 'none';
                okBtn.style.display = 'inline-block';
            }
            document.getElementById('messageBox').style.display = 'flex';
        }

        function closeMessageBox() {
            document.getElementById('messageBox').style.display = 'none';
            currentDeleteUrl = '';
        }

        function confirmAction() {
            if (currentDeleteUrl) {
                window.location.href = currentDeleteUrl;
            }
            closeMessageBox();
        }

        function cancelAction() {
            closeMessageBox();
        }

        document.addEventListener('DOMContentLoaded', () => {
            const deleteButtons = document.querySelectorAll('.delete-btn');
            deleteButtons.forEach(button => {
                button.addEventListener('click', (event) => {
                    event.preventDefault(); // Prevent default link behavior
                    const userId = event.target.dataset.id;
                    const deleteUrl = `deleteUser.php?id=${userId}`;
                    showMessageBox('Are you sure you want to delete this user?', 'confirm', deleteUrl);
                });
            });
        });

        const urlParams = new URLSearchParams(window.location.search);
        const msg = urlParams.get('msg');
        if (msg) {
            let displayMessage = '';
            switch (msg) {
                case 'added': displayMessage = "New user successfully added."; break;
                case 'invalid_input': displayMessage = "Invalid input data."; break;
                case 'error': displayMessage = "Something went wrong while adding the user."; break;
                case 'deleted': displayMessage = "User deleted successfully."; break;
                case 'updated': displayMessage = "User updated successfully."; break;
                case 'invalid_id': displayMessage = "Invalid user ID provided."; break;
                case 'user_not_found': displayMessage = "User not found."; break;
                case 'access_toggled': displayMessage = "User access level toggled successfully."; break;
                case 'toggle_error': displayMessage = "Failed to toggle user access."; break;
                case 'no_user_id': displayMessage = "No user ID specified for editing."; break;
                case 'update_error': displayMessage = "Failed to update user."; break;
                default: displayMessage = "An unknown action occurred."; break;
            }
            if (displayMessage) {
                showMessageBox(displayMessage, 'alert');
            }
        }
    </script>
</body>
</html>
