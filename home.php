<?php
require_once('Model/Database1.php');
require_once('Model/UserDataSet1.php');
require_once('loginController.php');

// Start the session to access session variables
$userDataSet = new UserDataSet1();

//This is a PHP script that handles an AJAX request to upvote a post.
// It checks that the request method is POST and that the 'action' parameter in the URL is set to 'upvote'.
// Then, it retrieves the post ID and the user ID from the POST data and the currently logged-in user, respectively.
// It calls the 'upvotePost' method from the '$userDataSet' object to increment the upvotes count for the specified post ID and user ID, and returns the updated upvotes count as a JSON-encoded string if the upvote is successful.
// Otherwise, it returns an error message as a JSON-encoded string and sets the HTTP response code to 400 (Bad Request).
//The 'get_current_user_id' function checks if the 'login' key is set in the $_SESSION superglobal array, which should contain the ID of the currently logged-in user.
// If the 'login' key is set, it returns the corresponding value. Otherwise, it returns null.
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_GET['action']) && $_GET['action'] === 'upvote') {
    // Check if user is logged in
    if (!isset($_SESSION['login'])) {
        http_response_code(401); // Unauthorized
        echo json_encode(['error' => 'Log in,in order to upvote']);
        exit;
    }
    $post_id = $_POST['post_id'];
    $user_id = $_SESSION['login'];

    $result = $userDataSet->upvotePost($post_id, $user_id);
    if ($result) {
        // Upvote successful, return the updated upvotes count
        $post = $userDataSet->fetchPostById($post_id);
        echo json_encode(['upvotes' => $post['upvotes']]);
    } else {
        // User has already upvoted the post
        http_response_code(400);
        echo json_encode(['error' => 'User has already upvoted this post']);
    }
    exit;
}

// Fetch all posts
$sql = "SELECT post.*, users4.name, users4.photo, post.category AS category_name 
FROM post 
INNER JOIN users4 ON post.user_id = users4.id 
ORDER BY post.upvotes DESC, post.created_at DESC";
$posts = $userDataSet->fetchPostsBySql($sql);
require_once('Views/home.phtml');
