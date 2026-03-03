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

    if($dbHandler){
        try{
            $stmt = $dbHandler->prepare("SELECT *
                                        FROM `Users`"
                                    );
            $stmt->execute(); //Note: We only execute the statement, the gathering of data is done in thee second segment           
        }catch(Exception $ex) {
            printError($ex);
        }
    }

    if(isset($stmt)){
        $stmt->bindColumn("user_name", $user_name); //We bind the column "id" to the variable $id
        $stmt->bindColumn("user_email", $user_email); //We bind the column "name" to the variable $name
        //We can also bind by column number. Note, this starts counting at 1
        //$stmt->bindColumn(1, $id);
        //$stmt->bindColumn(2, $name);
        $stmt->execute();
        
        while($result = $stmt->fetch()){//We can again fetch every record one by one
            if($user_email == $email || $user_name == $name){
                
            } //But this time we print by using the "nicely bound" variables instead of an array
        }
        $stmt->closeCursor();
    }
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