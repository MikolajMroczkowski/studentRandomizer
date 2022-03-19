<!doctype html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Students list</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="css/reset.css">
    <link rel="stylesheet" href="css/main.css">
    <script src="js/panelManagement.js"></script>
    <script src="js/main.js"></script>
</head>
<body onload="checkClass()">
<nav class="sidebar">
    <a href="index.php"><i class="bi bi-shuffle"></i><p>Losowanie</p></a><br>
    <a href="statistic.php"><i class="bi bi-clipboard-data"></i>
        <p>Statystyki Losowania</p></a><br>
    <a href="students.php" class="active"><i class="bi bi-person"></i><p>Uczniowie</p></a><br>
    <a href="classes.php"><i class="bi bi-people"></i><p>Klasy</p></a><br>
    <a href="settings.php"><i class="bi bi-gear"></i>
        <p>Ustawienia</p></a><br>
</nav>
<main>
    <form>
        <select name="class" id="classSelector" onchange="loadClass(this.value)">
            <?php
            require_once "config.php";
            $conn = new mysqli($servername, $username, $password, $dbname);
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }
            $sql = "SELECT * FROM classes";
            $result = $conn->query($sql);
            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    if($_GET['class']==$row['id']) {
                        echo "<option selected value='" . $row['id'] . "'>" . $row['name'] . "</option>";
                    }
                    else{
                        echo "<option value='" . $row['id'] . "'>" . $row['name'] . "</option>";
                    }
                }
            } else {
                echo "<option>ERROR</option>";
            }
            $conn->close();
            ?>
        </select>
        <br>
        <input name="name" placeholder="Imię...">
        <input name="surname" placeholder="Nazwisko...">
        <input type="number" name="number" placeholder="Numer w dzienniku">
        <input type="hidden" value="ADD" name="action">
        <input type="submit" value="Dodaj" >
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
            $sql = "INSERT INTO students (name,surname,number,class) VALUES ('".$_GET['name']."','".$_GET['surname']."',".$_GET['number'].",".$_GET['class'].")";
            if ($conn->query($sql) === TRUE) {
                echo "Dodano osobę!";
            } else {
                echo "Error: " . $sql . "<br>" . $conn->error;
            }
            $conn->close();
        }
        else if ($_GET['action'] == "REMOVE") {
            require_once "config.php";
            $conn = new mysqli($servername, $username, $password, $dbname);
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }
            $sql = "DELETE FROM students WHERE id=".$_GET['id'];
            if ($conn->query($sql) === TRUE) {
                echo "Usunięto Osobę!";
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
        $sql = "SELECT * FROM students WHERE class=".$_GET['class']." ORDER BY number";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<p onclick='deleteStudent(" . $row['id'] . ")'>".$row['number'].":  " . $row['name'] ." ".$row['surname']. "</p>";
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
<?php
