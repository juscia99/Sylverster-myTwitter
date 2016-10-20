<?php
$location = 'Location: login.php';
require 'src/init.php';
require 'src/Meow.php';
require 'src/Comment.php';

if (!isset($_SESSION['logged_user_id'])) {
    header($location);
}

$loggedUserId = intval($_SESSION['logged_user_id']);
$userMeowsArray = Meow::loadAllMeowsByUserId($conn, $loggedUserId);
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
             <h3>Your Posts:</h3>
             <div class="col-md-8 col-md-offset-1">
<?php
foreach ($userMeowsArray as $meow) {
    echo  "<div class='panel panel-success'>";
    echo "<div class='panel-heading'>" . $meow->getCreationDate() . "</div>";
    echo "<div class='panel-body'>";
    echo $meow->getNote() . "</div>";
    $meowId = $meow->getId();
    $commentsArray = Comment::loadAllCommentsByMeowId($conn, $meowId);
    echo "<div class='panel-footer'>";
    echo "Number of comments: " . sizeof($commentsArray) . "</div></div>";
}

$conn->close();
$conn = null;
?>                
             </div>
    </body>
</html>
