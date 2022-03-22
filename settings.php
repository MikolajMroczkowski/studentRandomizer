<?php
session_start();
if($_SESSION['zalogowany']!=true){
    header("Location: login.php");
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
    <title>Ustawienia</title>
    <script src="js/panelManagement.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="css/reset.css">
    <link rel="stylesheet" href="css/main.css">
    <script src="js/main.js"></script>
    <script src="js/settings.js"></script>
</head>
<body onload="init()">
<nav class="sidebar">
    <a href="index.php"><i class="bi bi-shuffle"></i>
        <p>Losowanie</p></a><br>
    <a href="statistic.php"><i class="bi bi-clipboard-data"></i>
        <p>Statystyki Losowania</p></a><br>
    <a href="students.php"><i class="bi bi-person"></i>
        <p>Uczniowie</p></a><br>
    <a href="classes.php" class=""><i class="bi bi-people"></i>
        <p>Klasy</p></a><br>
    <a href="settings.php" class="active"><i class="bi bi-gear"></i>
        <p>Ustawienia</p></a><br>
    <a href="logout.php" ><i class="bi bi-box-arrow-left"></i>
        <p>Wyloguj się</p></a><br>
</nav>
<main>
    <div class="settingsBox">
        <div class="settingsRow">
            <label for="speedOfAlgoritm">Prędkość algorytmu: </label><input min="10" max="10000" onchange="saveAlgoritmSpeed(this)" type="range" id="speedOfAlgoritm">
            <p>Aktualnie <span id="currentAlgoritmSpeed"></span></p>
            <button onclick="resetAlgoSpeed()">Reset</button>
        </div>
        <hr>
        <div class="settingsRow">
            <label for="algo0">Algorytm główny (prawdopodobieństwo)</label><input onchange="algo(this)" name="algo" type="radio" id="algo0"><br>
            <label for="algo1">Algorytm Dodatkowy (połowy)</label><input onchange="algo(this)" name="algo" type="radio" id="algo1"><br>
            <button onclick="setMainAlgo()">Reset</button>
        </div>
        <div class="settingsRow">
            <label for="speedOfAlgoritm">Czas pętli: </label><input min="10" max="10000" onchange="changeRepeatTime(this)" type="range" id="repeatTime">
            <p>Aktualnie <span id="currentRepeatTime"></span></p>
            <label for="repeat">Zapętlaj</label><input onchange="repeat(this.value)" type="checkbox" id="repeat"><br>
            <button onclick="repeat(false)">Reset</button>
        </div>
        <div class="settingsRow">
            <form method="post">
                <input placeholder="Nowe hasło" type="password" name="password">
                <input type="hidden" name="action" value="newpass">
                <input placeholder="Powtórz nowe hasło" type="password" name="rePassword">
                <input type="submit" value="zmień">
                <?php
                    switch ($_POST['action']){
                        case "newpass":
                            if($_POST['password']==$_POST['rePassword']){
                                $pass = password_hash($_POST['password'],PASSWORD_DEFAULT);
                                require_once "config.php";
                                $conn = new mysqli($servername, $username, $password, $dbname);
                                if ($conn->connect_error) {
                                    die("Connection failed: " . $conn->connect_error);
                                }
                                $sql = "UPDATE users SET password='".$pass."' WHERE id=".$_SESSION['id'];
                                $result = $conn->query($sql);
                                $conn->close();
                                echo "Zmieniono";
                            }
                            else{
                                echo "Hasła niezgodne";
                            }
                            break;
                        case "removeaccount":
                            require_once "config.php";
                            $conn = new mysqli($servername, $username, $password, $dbname);
                            if ($conn->connect_error) {
                                die("Connection failed: " . $conn->connect_error);
                            }
                            $sql = "DELETE FROM users WHERE id=".$_SESSION['id'];
                            $result = $conn->query($sql);
                            $conn->close();
                            echo "Usunięto";
                            break;
                    }
                ?>
            </form>
        </div>
        <div class="settingsRow">
            <form method="post">
                <input type="hidden" name="action" value="removeaccount">
                <input type="submit" value="Usuń moje konto">
            </form>
        </div>

    </div>

</main>
</body>
</html>
<?php
