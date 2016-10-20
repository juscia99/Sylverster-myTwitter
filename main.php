<?php
$location = 'Location: login.php';
$location2 = 'Location: main.php';
require 'src/init.php'; 
require 'src/Meow.php';
require 'src/User.php';

if (!isset($_SESSION['logged_user_id'])) {
    header($location);
}

$date = date('Y-m-d H:i:s');
$loggedUserId = intval($_SESSION['logged_user_id']);
$postsArray = Meow::loadAllMeows($conn); //zwróci $returnArray, zaś ta tablica zawiera w sobie obiekty, które są postami;

if ($_SERVER['REQUEST_METHOD'] == 'POST' &&
        isset($_POST['note'])
) {
    $creationDate = $date;
    $note = $_POST['note'];

    $post = new Meow();
    $post->setCreationDate($creationDate);
    $post->setUserId($loggedUserId);
    $post->setNote($note);
    $post->saveToDB($conn); 
    header($location2);
}
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Main</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
        <link href="css/main.css" rel="stylesheet">
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
                <div class="col-md-8 col-md-offset-1">
                    <form action="#" method="POST">
                        <div class="form-group">
                            <label for="meow">New Meow:</label>
                            <textarea class="form-control" rows="5" name="note" ></textarea>
                            <button type="submit" class="btn btn-default">Meow!</button>
                        </div>
                    </form>
                </div> 
            </div>
            <div class="row">     
                <div class="col-md-8 col-md-offset-1">
<?php

foreach ($postsArray as $meow) {
    $meowAuthorId = $meow->getUserId();
    $meowAuthorName = User::loadUserById($conn, $meowAuthorId)->getUsername();
    echo "<div class='panel panel-default'>";
    echo "<div class='panel-heading'>" . $meowAuthorName . " " . $meow->getCreationDate() . "</div>";
    echo "<div class='panel-body'>" . $meow->getNote() . "<br/><a href='post_details.php?post_id=" . $meow->getId() . "'>More details</a></div>";
    echo "</div>";
}
$conn->close();
$conn = null;
?>
               </div>
            </div>
    </body>
</html>


                