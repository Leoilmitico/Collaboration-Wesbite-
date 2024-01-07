<?php
require_once('Model/UserDataSet1.php');
require_once('Model/Database1.php');

$msg = '';
$userDataSet = new UserDataSet1();

if (isset($_POST['submit'])) {

    // Get form data and remove any potentially dangerous characters
    $name = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $password = filter_var($_POST['password'], FILTER_SANITIZE_STRING);
    $confirm_password = filter_var($_POST['confirm-password'], FILTER_SANITIZE_STRING);

    // Validate form data(email,password)
    if (empty($name) || empty($email) || empty($password) || empty($confirm_passwsord)) {
        $msg = 'Please fill in all fields';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $msg = 'Invalid email address';
    } elseif ($password != $confirm_password) {
        $msg = 'Passwords do not match';
    } elseif (strlen($password) < 8 || !preg_match("#[0-9]+#", $password) || !preg_match("#[A-Z]+#", $password) || !preg_match("#[a-z]+#", $password)) {
        $msg = 'Password must be at least 8 characters long and contain at least one uppercase letter, one lowercase letter, and one number';
    } else {
        // Hash password using bcrypt algorithm
        $hashed_password = password_hash($password, PASSWORD_BCRYPT);
        $sql = "INSERT INTO users4 (name, email, password) VALUES (?, ?, ?)";
        $params = array($name, $email, $hashed_password);
        $userDataSet->insertUsers($sql, $params);
        $msg = 'Registration successful';
    }
}

require_once('Views/Register.phtml');