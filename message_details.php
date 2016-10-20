<?php
$location = 'Location: login.php';
require 'src/init.php';
require 'src/Message.php';
require 'src/User.php';

if (!isset($_SESSION['logged_user_id'])) {
    header($location);
}

$messageId = intval($_GET['message_id']);

$message = Message::loadMessageById($conn, $messageId);

$senderId = $message->getSenderId();
$recipientId = $message->getRecipientId();
$messageDate = $message->getCreationDate();

$senderName = User::loadUserById($conn, $senderId);
$recipientName = User::loadUserById($conn, $recipientId);
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
            <div class="col-md-8 col-md-offset-1">
                <h2>Your message:</h2>
                <div class="panel panel-default">
                    <div class="panel-heading">
                        From: <?php echo $senderName->getUsername(); ?><br/>
                        To: <?php echo $recipientName->getUsername(); ?><br/>
                        Date: <?php echo $messageDate; ?>
                    </div>
                    <div class="panel-body">
                        <?php echo $message->getMessage(); ?>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>
<?php
$message->changeMessageStatus($conn, $messageId);

$conn->close();
$conn = null;
?>