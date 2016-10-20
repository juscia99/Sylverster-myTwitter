<?php
$location = 'Location: main.php';
require 'src/init.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM User WHERE email = '$email'";
    $result = $conn->query($sql);

    if ($result == true && $result->num_rows == 1) {
        $row = $result->fetch_assoc();
        $hashedPassword = $row['hashed_password'];
        $id = $row['id'];
    }

    if (!password_verify($password, $hashedPassword)) {
        echo "Wrong email or password.";
    } else {
        //jeśli hasło i email są ok następuje zalogowanie, czyli wpisanie do sesji id użytkownika 
        $_SESSION['logged_user_id'] = $id;
        header($location);
    }
}
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Log in</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
        <link href="css/login.css" rel="stylesheet" >
    </head>
    <body>
        <div class="container">
            <div class="row">
                <div class="col-md-4 col-md-offset-3">
                    <h2>Log in:</h2>
                    <form action="#" method="POST">
                        <div class="form-group">
                            <label for="email">Email:</label>
                            <input type="email" class="form-control" name="email" placeholder="Enter email">
                        </div>
                        <div class="form-group">
                            <label for="pwd">Password:</label>
                            <input type="password" class="form-control" name="password" placeholder="Enter password">
                        </div>
                        <div class="checkbox">
                            <label><input type="checkbox"> Remember me</label>
                        </div>
                        <button type="submit" class="btn btn-default">Log in</button>
                    </form>
                </div>
            </div>

            <div class="row">
                <div class="col-md-3 col-md-offset-3">
                    <button type="button" class="btn btn-primary">
                        <a id="button" href="registration.php">Registration</a>
                    </button>
                </div>
            </div>
        </div>
    </body>
</html>

<?php
$conn->close();
$conn = null;
?>


