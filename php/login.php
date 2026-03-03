<?php
session_start();
// 1) Если есть ошибка в сессии — забираем и сразу очищаем (flash message)
$error = $_SESSION['error'] ?? '';
unset($_SESSION['error']);

function printError(String $err){
    echo "<h1>The following error occured</h1>
          <p>{$err}</p>";
}
$dbHandler = null; //Create an empty variable that will contain the handler
try{
    $dbHandler = new PDO("mysql:host=mysql;dbname=Portfolio;charset=utf8", "root", "qwerty"); //Connect to the database with the provided connectstring
}catch(Exception $ex){//If something goes wrong, catch the error and print it
    printError($ex);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $password = filter_input(INPUT_POST, 'password');

    if (empty($email) || empty($password)) {
        $_SESSION['error'] = "All fields must be filled!";
        header("Location: login.php");
        exit;
    }

    if ($dbHandler) {

        $stmt = $dbHandler->prepare("
            SELECT user_name, user_password 
            FROM Users 
            WHERE user_email = :em
        ");

        $stmt->bindParam("em", $email, PDO::PARAM_STR);
        $stmt->execute();

        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['user_password'])) {

            // Успешный вход
            $_SESSION['userName'] = $user['user_name'];
            header("Location: home.php");
            exit;

        } else {

            $_SESSION['error'] = "Email or password incorrect";
            header("Location: login.php");
            exit;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/register.css">
    <title>Login</title>
</head>
<body>
    <form action="login.php" method="POST">
        <input type="email" placeholder="Enter your email" name="email">
        <input type="password" placeholder="Enter your password" name="password">

        <?php if (!empty($error)) : ?>
            <p class="error"><?= $error ?></p>
        <?php endif; ?>
        <a href="register.php">Create account</a>
        <button type="submit" name="btn">Login</button>
    </form>
</body>
</html>