<?php

require_once('Model/UserDataSet1.php');
require_once('Model/Database1.php');
require_once('loginController.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $request_id = $_POST['request_id'];
    $action = $_POST['action'];
    $status = '';
    $post = new UserDataSet1();

    if ($action == 'accept') {
        $status = 'accepted';

        $sender_id = $_POST['sender_id'];
        $post_id = $_POST['post_id'];

        // Add the user as a post participant for a project
        $post->addPostParticipant($sender_id, $post_id);
    } elseif ($action == 'reject') {
        $status = 'rejected';
    }

    // Update the post request status
    $post->updatePostRequestStatus($status, $request_id);
    header("Location: UserProfileController.php");
}