<?php
require_once('loginController.php');
require_once('Model/Database1.php');
require_once('Model/UserDataSet1.php');

// Start the session to access session variables

$msg = '';
$userDataSet = new UserDataSet1();

if (isset($_POST['submit'])) {
    // Check if the user is logged in and their ID is set in the session

    // Get the user ID from the session
    $userId = $_SESSION['login'];

    // Get form data
    $title = $_POST['title'];
    $content = $_POST['content'];
    $category = $_POST['category'];

    // Validate form data
    if (empty($title) || empty($content) || empty($category)) {
        $msg = 'Please fill in all fields';
    } else {
        // Insert post into database
        $sql = "INSERT INTO post (title, content, user_id, category) VALUES (?, ?, ?, ?)";
        $params = array($title, $content, $userId, $category);
        try {
            $userDataSet->addPost($sql, $params);
            // Redirect to index or other page
            header("Location: index.php");
            exit();
        } catch (Exception $e) {
            $msg = "Error adding post: " . $e->getMessage();
        }
    }
}
// Include the view file
require_once('Views/Post.phtml');