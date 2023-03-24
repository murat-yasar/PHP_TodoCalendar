<?php 
require_once('db-connect.php');

session_start();
$user_id = $_SESSION['user_id'];
$username = $_SESSION['username'];

if($_SERVER['REQUEST_METHOD'] !='POST'){
    echo "<script> alert('Error: No data to save.'); location.replace('./') </script>";
    $conn->close();
    exit;
}

extract($_POST);
$allday = isset($allday);

if(empty($id)){
    $sql = "INSERT INTO `event_list` (`title`,`user_id`,`username`,`description`,`start_datetime`,`end_datetime`) VALUES ('$title','$user_id','$username','$description','$start_datetime','$end_datetime')";
}else{
    $sql = "UPDATE `event_list` set `title` = '{$title}', `description` = '{$description}', `start_datetime` = '{$start_datetime}', `end_datetime` = '{$end_datetime}' WHERE `id` = '{$id}'";
}
$save = $conn->query($sql);
if($save){
    // echo "<script> alert('Event Successfully Saved.'); location.replace('./') </script>";
    header('Location:index.php');
}else{
    echo "<pre>";
    echo "An Error occured.<br>";
    echo "Error: ".$conn->error."<br>";
    echo "SQL: ".$sql."<br>";
    echo "</pre>";
}
$conn->close();
?>