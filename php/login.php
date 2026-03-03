<?php 
session_start();
$_SESSION['userName'] = "userName";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
</head>
<body>
    <form action="login.php" enctype="multipart/form-data">
        <input type="text" placeholder="enter your name">
    </form>
</body>
</html>