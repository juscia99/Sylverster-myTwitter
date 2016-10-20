<?php
require 'src/User.php';
require 'src/db_connection.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' &&
        isset($_POST['username']) &&
        isset($_POST['email']) &&
        isset($_POST['password'])
) {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $newPassword = $_POST['password'];

//weryfikacja czy podany email istnieje w bazie
    $sql = "SELECT `email` FROM `User`";
    $result = $conn->query($sql);
    if ($result) {
        foreach ($result as $row) {
            if ($row['email'] == $email) {
                echo "Email is already registered.<br>";
                echo '<a href= "registration.php">Back</a>';
                return false; 
            }
        }

        $user = new User();
        $user->setUsername($username);
        $user->setEmail($email);
        $user->setPassword($newPassword);
        $user->saveToDB($conn);
        
        //funkcja save to DB nadaje nowe id (zgodne z id w tabeli, więc za popmocą getId uzyskujemy aktualne id)
        $id = $user->getId();
        $_SESSION['logged_user_id'] = $id;
        
        echo "<p>Registration succeed.</p>";
        echo '<p><a href= "main.php">Go to Home.</a></p>';
    }
} else {
    ?>
    <!DOCTYPE html>
    <html>
        <head>
            <title>Registration</title>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
        </head>
        <body>
            <div class="container">
            <div class="row">
            <div class="col-md-4 col-md-offset-3">
  <h2>Registration:</h2>
  <form action="#" method="POST">
      <div class="form-group">
      <label for="usrname">Username:</label>
      <input type="text" class="form-control" name="username" placeholder="Enter Username">
    </div>
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
    <button type="submit" class="btn btn-default">Submit</button>
  </form>
        </div>
            </div>
            </div>
            
        </body>
    </html>
    <?php
}
$conn->close();
$conn = null;
