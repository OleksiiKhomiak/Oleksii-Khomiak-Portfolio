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

    $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

    if (empty($name) || empty($email) || empty($password)) {
        $_SESSION['error'] = "All fields must be filled!";
        header("Location: register.php"); // 2) Редирект -> при F5 не будет повторного POST
        exit;
    }

    // ✅ Тут если всё ок: сохранить в БД и т.д.
    // $_SESSION['success'] = "Account created!";
    header("Location: register.php"); // или на home.php
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
    <form action="register.php" method="POST">
        <input type="text" placeholder="Enter your username" name="name">
        <input type="text" placeholder="Enter your email" name="email">
        <input type="password" placeholder="Enter your password" name="password">

        <?php if (!empty($error)) : ?>
            <p class="error"><?= $error ?></p>
        <?php endif; ?>

        <button type="submit" name="btn">Create account</button>
    </form>
</body>
</html>