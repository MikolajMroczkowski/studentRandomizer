<!doctype html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>random student</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="css/reset.css">
    <link rel="stylesheet" href="css/main.css">
    <script src="js/panelManagement.js"></script>
    <script src="js/randomAlgorytm.js"></script>
    <script src="js/main.js"></script>
</head>
<body onload="checkClass(); init();">
<nav class="sidebar">
    <a href="index.php" class="active"><i class="bi bi-shuffle"></i>
        <p>Losowanie</p></a><br>
    <a href="statistic.php"><i class="bi bi-clipboard-data"></i>
        <p>Statystyki Losowania</p></a><br>
    <a href="students.php"><i class="bi bi-person"></i>
        <p>Uczniowie</p></a><br>
    <a href="classes.php"><i class="bi bi-people"></i>
        <p>Klasy</p></a><br>
    <a href="settings.php"><i class="bi bi-gear"></i>
        <p>Ustawienia</p></a><br>
</nav>
<main>
    <div class="formEmulation">
        <select id="classSelector" onchange="loadClass(this.value)">
            <?php
            require_once "config.php";
            $conn = new mysqli($servername, $username, $password, $dbname);
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }
            $sql = "SELECT * FROM classes";
            $result = $conn->query($sql);
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    if ($_GET['class'] == $row['id']) {
                        echo "<option selected value='" . $row['id'] . "'>" . $row['name'] . "</option>";
                    } else {
                        echo "<option value='" . $row['id'] . "'>" . $row['name'] . "</option>";
                    }
                }
            } else {
                echo "<option>ERROR</option>";
            }
            $conn->close();
            ?>
        </select>
        <button class="resetbtn" onclick="resetRandomer(true)">Reset</button>
        <button onclick="initRandom()">Get Random</button>
    </div>

    <div class="randomList">
        <?php
        require_once "config.php";
        $conn = new mysqli($servername, $username, $password, $dbname);
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }
        $sql = "SELECT * FROM students WHERE class=" . $_GET['class'] . " ORDER BY number";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<p id='" . $row['id'] . "' onclick='toogleDisable(this.id)' >" . $row['number'] . ":  " . $row['name'] . " " . $row['surname'] . "</p>";
            }
        } else {
            echo "<p>Class Empty</p>";
        }
        $conn->close();
        ?>
    </div>
</main>
</body>
</html>
