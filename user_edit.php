<?php
$location = 'Location: login.php';
require 'src/init.php';
require 'src/User.php';

if (!isset($_SESSION['logged_user_id'])) {
    header($location);
}
$loggedUserId = intval($_SESSION['logged_user_id']);
$loggedUser = User::loadUserById($conn, $loggedUserId);
?>

<!DOCTYPE html>
<html>
    <head>
        <title>User Edit</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    </head>
    <body>
        <nav class="navbar navbar-default">
            <div class="container-fluid">
                <div class="navbar-header">
                    <a class="navbar-brand" href="#">Sylverster</a>
                </div>
                <ul class="nav navbar-nav">
                    <li class="active"><a href="main.php">Home</a></li>
                    <li><a href="user_messages.php">My messages</a></li>
                    <li><a href="user_edit.php">Settings</a></li>
                    <li><a href="logged_user_info.php">My Posts</a></li>
                </ul>
            </div>
        </nav>
        <div class="container">
            <div class="row">
                <div class="col-md-4 col-md-offset-3">
                    <form action="#" method="POST">
                        <div class="form-group">
                            <label for="usrname">Change Username:</label>
                            <input type="text" class="form-control" name="username">
                        </div>
                        <div class="form-group">
                            <label for="email">Change Email:</label>
                            <input type="email" class="form-control" name="email">
                        </div>
                        <div class="form-group">
                            <label for="pwd">Change Password:</label>
                            <input type="password" class="form-control" name="password1">
                        </div>
                        <div class="form-group">
                            <label for="pwd">Repeat New Password:</label>
                            <input type="password" class="form-control" name="password2">
                        </div>
                        <div class="form-group">
                            <label for="pwd">Your Old Password:</label>
                            <input type="password" class="form-control" name="old_password">
                        </div>
                        <button type="submit" class="btn btn-default">Submit</button>
                    </form>
                </div>
            </div>
        </div>
    </body>
</html>

<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    if (isset($_POST['username'])) {
        $username = $_POST['username'];
        $loggedUser->setUsername($username);
    }

    if (isset($_POST['email'])) {
        $email = $_POST['email'];
        $loggedUser->setEmail($email);
    }

    if (isset($_POST['password1']) && isset($_POST['password2']) && isset($_POST['old_password'])) {
        $password1 = $_POST['password1'];
        $password2 = $_POST['password2'];
        $oldPassword = $_POST['old_password'];

        //weryfikacja starego hasła:
        $sql = "SELECT * FROM User WHERE `id` = '$loggedUserId'";
        $result = $conn->query($sql);

        if ($result == true && $result->num_rows == 1) {
            $row = $result->fetch_assoc();
            $hashedPassword = $row['hashed_password'];
        }

        if (!password_verify($oldPassword, $hashedPassword)) {
            echo "Wrong password.";
            return false;
        }

        //weryfikacja nowego hasła:
        if ($password1 !== $password2) {
            echo "Passwords are different.";
            return false;
        }
        $loggedUser->setPassword($password1);
    }

    $loggedUser->saveToDB($conn);
}
$conn->close();
$conn = null;
