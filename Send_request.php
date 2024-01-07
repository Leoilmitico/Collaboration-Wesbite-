<?php

// Include the necessary files
require_once('Model/UserDataSet1.php');
require_once('Model/Database1.php');
require_once('loginController.php');

// Check if the user is logged in
if(!isset($_SESSION['login'])) {
    header("Location: login.php");
    exit;
}

// Get the sender ID (logged-in user)
$sender_id = $_SESSION['login'];

// Get the post ID and receiver ID from the URL or another method
$post_id = $_GET['post_id'];
$receiver_id = $_GET['receiver_id'];

// Send the post request
$dataSet = new UserDataSet1();

// Check if a request has already been sent
if ($dataSet->hasRequestBeenSent($sender_id, $post_id)) {
    // Redirect back to the post page with an error message
    header("Location: home.php?post_id=$post_id&request_error=2");
} else {
    $result = $dataSet->sendPostRequest($sender_id, $receiver_id, $post_id);

    if ($result) {
        // Redirect back to the post page with a success message
        header("Location: home.php?post_id=$post_id&request_sent=1");
    } else {
        // Redirect back to the post page with an error message
        header("Location: home.php?post_id=$post_id&request_error=1");
    }
}