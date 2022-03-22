<?php
session_start();
if($_SESSION['zalogowany']!=true){
    header("Location: login.php");
    exit;
}
?>
<?php
if(is_numeric($_GET['saveWon'])){
    require_once "config.php";
    $conn = new mysqli($servername, $username, $password, $dbname);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    $sql = "SELECT * FROM students s INNER JOIN classes c ON s.class=c.id WHERE c.owner=".$_SESSION['id'];
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        $sqlInsert = "INSERT INTO probability (student) VALUES (" . $_GET['saveWon'] . ")";
        $conn->query($sqlInsert);
    }
    $conn->close();
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
    <title>random student</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="css/reset.css">
    <link rel="stylesheet" href="css/main.css">
    <script src="js/panelManagement.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@3/dist/chart.min.js"></script>
    <script src="js/main.js"></script>
</head>
<body onload="checkClass()">
<nav class="sidebar">
    <a href="index.php"><i class="bi bi-shuffle"></i><p>Losowanie</p></a><br>
    <a href="statistic.php" class="active"><i class="bi bi-clipboard-data"></i>
        <p>Statystyki Losowania</p></a><br>
    <a href="students.php"><i class="bi bi-person"></i><p>Uczniowie</p></a><br>
    <a href="classes.php"><i class="bi bi-people"></i><p>Klasy</p></a><br>
    <a href="settings.php" ><i class="bi bi-gear"></i>
        <p>Ustawienia</p></a><br>
    <a href="logout.php" ><i class="bi bi-box-arrow-left"></i>
        <p>Wyloguj się</p></a><br>
</nav>
<main>
    <form>
        <select id="classSelector" onchange="loadClass(this.value)">
            <?php
            require_once "config.php";
            $conn = new mysqli($servername, $username, $password, $dbname);
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }
            $sql = "SELECT * FROM classes WHERE owner=".$_SESSION['id'];
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
    </form>
    <hr>
    <p>Średni wychodzi po <span id="sredniaVal">-1</span> Wylosowań na ucznia<br>Było już <span id="ilosc">-1</span> Wylosowań<br></p>
    <hr>
    <div class="chart">
    <canvas id="myChart" width="200" height="200"></canvas>
    </div>

    <script>
        <?php
        require_once "config.php";
        $conn = new mysqli($servername, $username, $password, $dbname);
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }
        $sql = "SELECT s.number as number, s.name as name, s.surname as surname, (SELECT COUNT(*) FROM probability WHERE student = s.id) as wylosowan FROM students s INNER JOIN classes c ON c.id=s.class WHERE c.owner=".$_SESSION['id']." AND class=".$_GET['class']." ORDER BY number";
        echo 'console.log("'.$sql.'")'."\n";
        $result = $conn->query($sql);
        $students = array('students'=>array(),'isEmpty'=>$result->num_rows == 0);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $students['students'][] = array('number'=>$row['number'],'name'=>$row['name'],'surname'=>$row['surname'],'val'=> $row['wylosowan']);
            }
        }
        echo "var jsonData='".json_encode($students)."'\n";
        $conn->close();
        ?>
        var dataObjects = JSON.parse(jsonData)
        if(!dataObjects.isEmpty) {
            var srednia = 0;
            var ilosc = 0;
            var datas = [[], [], [],[]];
            for (var x = 0; x < dataObjects.students.length; x -= -1) {
                ilosc -= -1;
                srednia+=parseInt(dataObjects.students[x].val);
                var val = dataObjects.students[x].val
                var r = Math.floor(Math.random() * 220)
                var g = Math.floor(Math.random() * 220)
                var b = Math.floor(Math.random() * 220)
                datas[0].push(val)
                datas[1].push('rgba(' + r + ',' + g + ',' + b + ', 0.2)')
                datas[2].push('rgba(' + r + ',' + g + ',' + b + ', 1)')
                datas[3].push(dataObjects.students[x].name+" "+dataObjects.students[x].surname)
            }
            document.getElementById("ilosc").innerHTML=srednia;
            srednia=srednia/ilosc
            document.getElementById("sredniaVal").innerHTML=srednia;
            const ctx = document.getElementById('myChart').getContext('2d');
            const myChart = new Chart(ctx, {
                type: 'bar',
                scale: {
                    x: 200,
                    y: 200
                },
                data: {
                    labels: datas[3],
                    datasets: [{
                        label: 'Ilość wylosowań ucznia',
                        data: datas[0],
                        backgroundColor: datas[1],
                        borderColor: datas[2],
                        borderWidth: 0.75
                    }]
                },
            });
        }
        else{
            console.log("error")
        }
    </script>

</main>
</body>
</html>
<?php
