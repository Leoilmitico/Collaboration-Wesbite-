<?php

require_once('Model/Database1.php');
require_once('Model/UserDataSet1.php');

// Get user ID from URL parameter
$user_id = $_GET['user_id'];

// Fetch user profile
$userDataSet = new UserDataSet1();
$userProfile = $userDataSet->getUserProfile($user_id);

// Fetch user's posts
$userPosts = $userDataSet->fetchPostsByUserId($user_id);

// Pass user's profile and posts to the view
require('Views/Otherusers.phtml');
