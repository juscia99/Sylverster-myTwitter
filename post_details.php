<?php
$location = 'Location: login.php';
require 'src/init.php';
require 'src/User.php';
require 'src/Meow.php';
require 'src/Comment.php';

if (!isset($_SESSION['logged_user_id'])) {
    header($location);
}

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    
    $meowId = intval($_GET['post_id']); 
}

$date = date('Y-m-d H:i:s');
$loggedUserId = intval($_SESSION['logged_user_id']);

if ($_SERVER['REQUEST_METHOD'] == 'POST' &&
        isset($_POST['comment'])
) {
    $meowId = intval($_POST['post_id']);
    $newCommentText = $_POST['comment'];
    $newComment = new Comment();
    $newComment->setCommentText($newCommentText);
    $newComment->setCreationDate($date);
    $newComment->setMeowId($meowId);
    $newComment->setUserId($loggedUserId);
    $newComment->saveToDB($conn);
    
} 
//dane postu
$post = Meow::loadMeowById($conn, $meowId);
$postDate = $post->getCreationDate();
$text = $post->getNote();
$postAuthorId = $post->getUserId();
$postAuthorName = User::loadUserById($conn, $postAuthorId);

?>

<!DOCTYPE html>
<html>
    <head>
        <title>Post details</title>
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
                    <div class="panel panel-info">
                        <div class='panel-heading'>
                            <?php echo "<a href='user_info.php?user_id=" . $postAuthorName->getId() . "'>" . $postAuthorName->getUsername() . "</a>" . "  " . $postDate ?>
                        </div>
                        <div class='panel-body'>
                            <?php echo $text ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="container">
            <div class="row">
                <div class="col-md-8 col-md-offset-1">
                    <form action="#" method="POST">
                        <div class="form-group">
                            <label for="comment">Your Comment:</label>
                            <textarea class="form-control" rows="5" name="comment" ></textarea>
                            <input type="hidden" name="post_id" value="<?php echo $meowId ?>">
                            <button type="submit" class="btn btn-default">Add</button>
                        </div>
                    </form>
                </div> 
            </div>
        <div class="row">
            <div class="col-md-8 col-md-offset-1">
<?php
//dane komentarzy:
$commentsArray = Comment::loadAllCommentsByMeowId($conn, $meowId);
foreach ($commentsArray as $comment) {
    //ustalanie username dla każdego komentarza na podstawie commentId
    $commentId = $comment->getId();
    $commentAuthorId = Comment::loadCommentById($conn, $commentId)->getUserId();
    $commentAuthor = User::loadUserById($conn, $commentAuthorId);

    echo "<div class='panel panel-default'>";
    echo "<div class='panel-heading'>";
    echo "<a href='user_info.php?user_id=" . $commentAuthor->getId() . "'>" . $commentAuthor->getUsername() . "</a><br/>";
    echo $comment->getCreationDate() . "</div>";
    echo "<div class='panel-body'>" . $comment->getCommentText() . "</div>";
    echo "</div>";
    
}

$conn->close();
$conn = null;
?>
                
            </div>
    </body>
</html>

