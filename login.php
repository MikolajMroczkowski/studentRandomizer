<?php
session_start();
if ($_SESSION['zalogowany'] == true) {
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
            <h1>Zaloguj się</h1>
            <form method="post">
                <input name="login" placeholder="login">
                <input name="password" type="password" placeholder="Hasło">
                <?php
                if ($_POST) {
                    $uName = bin2hex($_POST['login']);
                    require_once "config.php";
                    $conn = new mysqli($servername, $username, $password, $dbname);
                    if ($conn->connect_error) {
                        die("Connection failed: " . $conn->connect_error);
                    }
                    $sql = "SELECT * FROM users WHERE username=unhex('" . $uName . "')";
                    $result = $conn->query($sql);
                    $willWork = false;
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                           if(password_verify($_POST['password'],$row['password'])){
                               session_start();
                               $_SESSION['zalogowany'] = true;
                               $_SESSION['id'] = $row['id'];
                               $willWork=true;
                           }
                        }
                    }
                    $conn->close();
                    if($willWork){
                        header("Location: index.php");
                        exit;
                    }
                    else{
                        echo "Błędne dane logowania";
                    }
                } ?>
                <a href="register.php">Register</a>
                <input type="submit" value="Zaloguj się">
            </form>
        </div>
    </main>
    </body>
    </html>
<?php
