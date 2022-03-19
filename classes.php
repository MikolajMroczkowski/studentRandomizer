<!doctype html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Klasy</title>
    <script src="js/panelManagement.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="css/reset.css">
    <link rel="stylesheet" href="css/main.css">
    <script src="js/main.js"></script>
</head>
<body>
<nav class="sidebar">
    <a href="index.php"><i class="bi bi-shuffle"></i>
        <p>Losowanie</p></a><br>
    <a href="statistic.php"><i class="bi bi-clipboard-data"></i>
        <p>Statystyki Losowania</p></a><br>
    <a href="students.php"><i class="bi bi-person"></i>
        <p>Uczniowie</p></a><br>
    <a href="classes.php" class="active"><i class="bi bi-people"></i>
        <p>Klasy</p></a><br>
    <a href="settings.php" ><i class="bi bi-gear"></i>
        <p>Ustawienia</p></a><br>
</nav>
<main>
    <form>
        <input name="name" placeholder="Nazwa...">
        <input type="hidden" value="ADD" name="action">
        <input type="submit" value="Utwórz">
    </form>
    <hr>
    <?php
    if ($_GET) {
        if ($_GET['action'] == "ADD") {
            require_once "config.php";
            $conn = new mysqli($servername, $username, $password, $dbname);
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }
            $sql = "INSERT INTO classes (name) VALUES ('" . $_GET['name'] . "')";
            if ($conn->query($sql) === TRUE) {
                echo "Utworzono Klasę!";
            } else {
                echo "Error: " . $sql . "<br>" . $conn->error;
            }
            $conn->close();
        } else if ($_GET['action'] == "REMOVE") {
            require_once "config.php";
            $conn = new mysqli($servername, $username, $password, $dbname);
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }
            $sql = "DELETE FROM classes WHERE id=" . $_GET['id'];
            if ($conn->query($sql) === TRUE) {
                echo "Usunięto Klasę!";
            } else {
                echo "Error: " . $sql . "<br>" . $conn->error;
            }
            $conn->close();
        }
    }
    ?>
    <hr>
    <div class="listBox">
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
                echo "<p onclick='deleteClass(" . $row['id'] . ")'>" . $row['name'] . "</p>";
            }
        } else {
            echo "<p>Error While Reading From DB</p>";
        }
        $conn->close();
        ?>
    </div>

</main>
</body>
</html>
<?php
