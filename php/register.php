<?php
session_start();
if($_SERVER['REQUEST_METHOD'] == 'POST'){
    header('Location: registe.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/register.css">
    <title>Register</title>
</head>
<body>
    <form action="register.php">
        <input type="text" placeholder="enter your username" name="name">
        <input type="text" placeholder="enter your email" name="email">
        <input type="text" placeholder="enter your password" name="password">
        <button>create account</button>
    </form>
</body>
</html>