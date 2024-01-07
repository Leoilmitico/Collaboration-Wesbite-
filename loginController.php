<?php session_start();

require_once('Model/Database1.php');
require_once('Model/UserDataSet1.php');

if (isset($_POST['login_submit'])) {
// Check if the email and password fields are set in the $_POST array
    $email = isset($_POST['email']) ? $_POST['email'] : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';

    if (!empty($email) && !empty($password)) {
        $con = Database1::getInstance();
        $con = $con->getdbConnection();

        $DataSet = new UserDataSet1();

        $sql = "SELECT * FROM age558.users4 WHERE email='$email'";
        $userObject = $DataSet->checkLogin($sql);
// Check if the entered email and password match the user object retrieved from the database
        if ($email == $userObject[0]->getEmail() && password_verify($password, $userObject[0]->getPassword())) {
            // If the login credentials are correct, set the user's ID in the session
            $_SESSION["login"] = $userObject[0]->getID();
            $sql = null;
// Redirect to dashboard or other page
            header("location:index.php");
            exit();
        } else {
            echo "Error in username and password.";
            $sql = null;
        }
    } else {
        echo "Please enter username or password.";
    }
} elseif (isset($_POST['logout'])) {
    unset($_SESSION['login']);
    session_destroy();
    header("location:home.php");
    exit();
}