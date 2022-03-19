<?php
require_once "config.php";
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
$sql = "SELECT *, (SELECT COUNT(*) FROM probability WHERE student = s.id) as wylosowan FROM students s WHERE class=".$_GET['class']." ORDER BY number";
$result = $conn->query($sql);
$students = array('students'=>array(),'isEmpty'=>$result->num_rows == 0, 'maxNum'=>0);
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $students['maxNum'] += $row['wylosowan'];
        $students['students'][] = array('id'=>$row['id'],'number'=>$row['number'],'name'=>$row['name'],'surname'=>$row['surname'],'val'=> $row['wylosowan'], 'isEnabled'=>true);
    }
}
echo json_encode($students);
$conn->close();
?>