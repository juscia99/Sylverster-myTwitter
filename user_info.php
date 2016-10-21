<?php
$location = 'Location: login.php';
require 'src/init.php'; 
require 'src/Message.php';
require 'src/Meow.php';
require 'src/Comment.php';
require 'src/User.php';

if (!isset($_SESSION['logged_user_id'])) {
    header($location);
}

$loggedUserId = intval($_SESSION['logged_user_id']);
$date = date('Y-m-d H:i:s');

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    $userId = intval($_GET['user_id']);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $userId = intval($_POST['user_id']);
    $message = $_POST['message'];

    $newMessage = new Message();
    $newMessage->setCreationDate($date);
    $newMessage->setMessage($message);
    $newMessage->setRecipientId($userId);
    $newMessage->setSenderId($loggedUserId);
    $newMessage->saveToDB($conn);
    echo "Message sent.<br>";
}
?>

<!DOCTYPE html>
<html>
    <head>
        <title>User Info</title>
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
                <div class="col-md-6">
                    <p>
                        Send message to:<br/>
                        <?php
                        $user = User::loadUserById($conn, $userId);
                        echo "<strong>" . $user->getUsername() . "</strong><br>";
                        echo "<strong>" . $user->getEmail() . "</strong><br>";
                        ?>
                    </p>
                    <form action="#" method="POST">
                        <div class="form-group">
                            <label for="message">Your message:</label>
                            <textarea class="form-control" rows="5" name="message" ></textarea>
                            <input type="hidden" name="user_id" value="<?php echo $userId ?>">
                            <input type="hidden" name="logged_user_id" value="<?php echo $loggedUserId ?>">
                            <button type="submit" class="btn btn-default">Send</button>
                        </div>
                    </form>
                </div>
            </div>
            <div class="row">
                <?php
                $userMeowsArray = Meow::loadAllMeowsByUserId($conn, $userId);
                foreach ($userMeowsArray as $meow) {
                    echo "<div class='panel panel-success'>";
                    echo "<div class='panel-heading'>" . $meow->getCreationDate() . "</div>";
                    echo "<div class='panel-body'>" . $meow->getNote() . "</div>";

                    $meowId = $meow->getId();
                    $commentsArray = Comment::loadAllCommentsByMeowId($conn, $meowId);
                    echo "<div class='panel-footer'>Number of comments: " . sizeof($commentsArray) ."</div>";
                    echo "</div>";
                }
                $conn->close();
                $conn = null;
                ?>
            </div>
        </div>
    </body>
</html>




