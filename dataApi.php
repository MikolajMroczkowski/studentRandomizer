<?php
session_start();
if ($_SESSION['zalogowany'] != true) {
    header("Location: login.php");
    exit;
}
require_once "config.php";
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
$sql = "SELECT s.id as id, s.number as number, s.name as name, s.surname as surname, (SELECT COUNT(*) FROM probability WHERE student = s.id) as wylosowan FROM students s INNER JOIN classes c ON s.class=c.id WHERE c.owner=".$_SESSION['id']." AND s.class=".$_GET['class']." ORDER BY s.number";
$result = $conn->query($sql);
$students = array('students'=>array(),'isEmpty'=>$result->num_rows == 0, 'maxNum'=>0);
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $students['maxNum'] += $row['wylosowan'];
        $students['students'][] = array('id'=>$row['id'],'number'=>$row['number'],'name'=>$row['name'],'surname'=>$row['surname'],'val'=> $row['wylosowan']);
    }
}
echo json_encode($students);
$conn->close();
?>