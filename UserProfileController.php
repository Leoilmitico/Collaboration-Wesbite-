<?php
require_once('loginController.php');
require_once('Model/Database1.php');
require_once('Model/UserDataSet1.php');

$userDataSet = new UserDataSet1();

if (isset($_GET['user_id'])) {
    // Fetch user profile
    $userId = $_GET['user_id'];
    $userProfile = $userDataSet->getUserProfile($userId);
    // Fetch user's posts
    $userPosts = $userDataSet->fetchPostsByUserId($userId);
} else {
    // Fetch logged in user's profile
    $userId = $_SESSION["login"];
    $userProfile = $userDataSet->getUserProfile($userId);
    // Fetch logged in user's own posts
    $userPosts = $userDataSet->fetchPostsByUserId($userId);

    // Delete user account
    if (isset($_POST['deleteAccount'])) {
        $userDataSet->deleteUserAccount($userId);
    }
    //delete post
    if (isset($_POST['deletePost'])) {
        $postId = $_POST['postId'];
        $userDataSet->deletePostById($postId, $userId);
        $userPosts = $userDataSet->fetchPostsByUserId($userId); // Refresh user's posts
    }

    // Update profile information and picture
    if (isset($_POST['updateProfile'])) {
        $name = filter_var(trim($_POST['name']), FILTER_SANITIZE_STRING);
        $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
        $password = trim($_POST['password']);

        if (!empty($name) && !empty($email) && !empty($password)) {
            // Validate email
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $msg = "Invalid email format";
            } else {
                // Hash password
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                $userDataSet->updateUserProfile($userId, $name, $email, $hashed_password);

                if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
                    $targetDir = "uploads/";
                    $fileName = basename($_FILES['photo']['name']);
                    $targetFilePath = $targetDir . $fileName;

                    if (move_uploaded_file($_FILES['photo']['tmp_name'], $targetFilePath)) {
                        $userDataSet->updateProfilePicture($userId, $targetFilePath);
                        $userProfile['photo'] = $targetFilePath; // Update the user profile with new photo
                        $msg = "Profile and picture updated successfully";
                    } else {
                        $msg = "Error uploading your picture";
                    }
                } else {
                    $msg = "Profile updated successfully";
                }

                $userProfile = $userDataSet->getUserProfile($userId); // Fetch updated profile
            }
        } else {
            $msg = "Please fill in all fields";
        }
    }
}


require_once('Views/UserProfile.phtml');