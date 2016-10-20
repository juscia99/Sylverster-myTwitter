<?php
$location = 'Location: login.php';
require 'src/init.php'; 
require 'src/Message.php';
require 'src/User.php';

if (!isset($_SESSION['logged_user_id'])) {
    header($location);
}
$loggedUserId = intval($_SESSION['logged_user_id']);

$messagesArray = Message::loadAllMeessagesByRecipientId($conn, $loggedUserId);
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
        <link href="css/main.css" rel="stylesheet" >
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
             <h3>Your Messages:</h3>
                <div class="col-md-8 col-md-offset-1">
                    
<?php
foreach ($messagesArray as $object) {
    if ($object->getIsRead() == 0) {
        $status = 'unread';
    } else {
        $status = 'read';
    }
    $message = substr($object->getMessage(), 0, 30);
    $senderName = User::loadUserById($conn, $object->getSenderId())->getUsername();
    echo "<div class='panel panel-info'>";
    echo "<div class='panel-heading'>" . $object->getCreationDate() . "  " . $senderName . "</div>";
    echo "<div class='panel-body'>";
    echo "Status: " . $status . "<br>";
    echo "Preview: " . $message . "</div>";
    echo "<div class='panel-footer'>";
    echo '<a href = "message_details.php?message_id=' . $object->getId() . '">Read this message</a></div>';
    echo "</div>";
}

$conn->close();
$conn = null;
?>
                </div>
         </div>
    </body>
</html>


