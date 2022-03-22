<?php
session_start();
if($_SESSION['zalogowany']==true){
    header("Location: index.php");
    exit;
}
?>
    <!doctype html>
    <html lang="pl">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport"
              content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>Zaloguj się</title>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css">
        <link rel="stylesheet" href="css/reset.css">
        <link rel="stylesheet" href="css/main.css">
    </head>
    <body>
    <main>
        <div class="loginBox">
            <h1>Zarejstruj się</h1>
            <form method="post">
                <input name="login" placeholder="login">
                <input name="password" type="password" placeholder="Hasło">
                <input name="rePassword" type="password" placeholder="Powtórz hasło">
                <?php
                if($_POST){
                    if($_POST['password']==$_POST['rePassword']) {
                        $uName = bin2hex($_POST['login']);
                        $pass = password_hash($_POST['password'], PASSWORD_DEFAULT);
                        require_once "config.php";
                        $conn = new mysqli($servername, $username, $password, $dbname);
                        if ($conn->connect_error) {
                            die("Connection failed: " . $conn->connect_error);
                        }
                        $sql = "SELECT * FROM users WHERE username=unhex('".$uName."')";
                        $result = $conn->query($sql);
                        $willWork = $result->num_rows == 0;
                        if ($willWork) {
                            $insertSQL = "INSERT INTO users (username,password) value (unhex('".$uName."'),'".$pass."')";
                            $conn->query($insertSQL);
                            echo "Użytkownik utworzony";
                        } else {
                            echo "Ta nazwa użytkownika jest zajęta";
                        }
                        $conn->close();
                    }
                    else{
                        echo "Hasła są niezgodne";
                    }
                }?>
                <a href="login.php">Zaloguj się</a>
                <input type="submit" value="Register">
            </form>
        </div>
    </main>
    </body>
    </html>

