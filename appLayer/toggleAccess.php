<?php
require_once '../dbLayer/UserModel.php';
$model = new UserModel();
$user = $model->getUserById($_GET['id']);
$newLevel = $user['access_level'] == 3 ? 1 : 3;
$model->toggleAccess($user['user_id'], $newLevel);
header("Location: viewUsers.php");
exit();
