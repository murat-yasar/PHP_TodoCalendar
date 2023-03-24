<?php 
echo "<pre>";

require_once('db-connect.php');
session_start();

if(!isset($_GET['term'])){
  echo "<script> alert('Undefined Search Term.'); location.replace('./') </script>";
  $conn->close();
  exit;
} else {
  $term = $_GET['term'];
  $searchTitle = [];
  $searchDescription = [];

  $events = $conn->query("SELECT * FROM `event_list` WHERE user_id = '{$_SESSION['user_id']}'");

  foreach ($events->fetch_all(MYSQLI_ASSOC) as $event){
    if(str_contains($event['title'], $term)) array_push($searchTitle, $event);
    else if(str_contains($event['description'], $term)) array_push($searchDescription, $event);
  }

  if($searchTitle != null){
    foreach ($searchTitle as $event){
      $start = $event['start_datetime'];
      $end = $event['end_datetime'];
      echo "<h3>$event[title]</h3> start: ".$start." end: ".$end."<br>"."$event[description]";
    }
  }
  if($searchDescription != null){
    foreach ($searchDescription as $event){
      $start = $event['start_datetime'];
      $end = $event['end_datetime'];
      echo "<h3>$event[title]</h3> start: ".$start." end: ".$end."<br>"."$event[description]";
    }
  }else{
    echo "SEARCH ERROR";
    echo "<pre>";
    echo "An Error occured.<br>";
    echo "Error: ".$conn->error."<br>";
    echo "SQL: ".$sql."<br>";
    echo "</pre>";
  }
}





$conn->close();
?>
