<?php 
require_once('db-connect.php');
session_start();

// Direct to login.php
(!isset($_SESSION['login']) || $_SESSION['login'] == false) ? header('Location:login.php') : false;
?>
<!DOCTYPE html>
<html lang="en de tr">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <!-- Refresh page every minute -->
  <meta http-equiv="refresh" content="60">
  <!-- Styles -->
  <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" 
  integrity="sha384-AYmEC3Yw5cVb3ZcuHtOA93w35dYTsvhLPVnYs9eStHfGJvOvKxVfELGroGkvsg+p" crossorigin="anonymous" />
  <link rel="stylesheet" href="./fullcalendar/lib/main.min.css">
  <link rel="stylesheet" href="./css/bootstrap.min.css">
  <link rel="stylesheet" href="./css/styles.css">
  <!-- Scripts -->
  <script src="./fullcalendar/lib/main.min.js"></script>
  <script src="./js/jquery-3.6.0.min.js"></script>
  <script src="./js/bootstrap.min.js"></script>
  <title>Event Calendar</title>
</head>

<body class="bg-light">
  <div class="container py-5" id="page-container">
    <div class="row">
      <div class="col-md-9">
        <div id="calendar"></div>
      </div>
      <div class="col-md-3">

        <!-- SAVE FORM -->
        <div class="cardt rounded-0 shadow">
          <div class="card-header bg-gradient bg-primary text-light">
            <h5 class="card-title">Add New Event</h5>
          </div>
          <div class="card-body">
            <div class="container-fluid">
              <form action="save_schedule.php" method="post" id="schedule-form">
                <input type="hidden" name="id" value="">
                <div class="form-group mb-2">
                  <label for="title" class="control-label">Title</label>
                  <input type="text" class="form-control form-control-sm rounded-0" name="title" id="title" required>
                </div>
                <div class="form-group mb-2">
                  <label for="description" class="control-label">Details</label>
                  <textarea rows="3" class="form-control form-control-sm rounded-0" name="description" id="description" ></textarea>
                </div>
                <div class="form-group mb-2">
                  <label for="start_datetime" class="control-label">Start</label>
                  <input type="datetime-local" class="form-control form-control-sm rounded-0" name="start_datetime" id="start_datetime" required>
                </div>
                <div class="form-group mb-2">
                  <label for="end_datetime" class="control-label">End</label>
                  <input type="datetime-local" class="form-control form-control-sm rounded-0" name="end_datetime" id="end_datetime" required>
                </div>
              </form>
            </div>
          </div>
          <div class="card-footer">
            <div class="text-center">
              <button class="btn btn-primary btn-sm rounded-0" type="submit" form="schedule-form"><i class="fa fa-save"></i> Save</button>
              <button class="btn btn-default border btn-sm rounded-0" type="reset" form="schedule-form"><i class="fa fa-reset"></i> Cancel</button>
            </div>
          </div>
        </div>

        <br><br>

        <!-- SEARCH FORM -->
        <div class="cardt rounded-0 shadow">
          <div class="card-header bg-gradient bg-primary text-light">
            <h5 class="card-title">Search Event</h5>
          </div>
          <div class="card-body">
            <div class="container-fluid">
              <form action="search.php" method="get" id="search-form">
                <div class="form-group mb-2">
                  <label for="title" class="control-label">Search Event</label>
                  <input type="text" class="form-control form-control-sm rounded-0" name="term" id="term" required>
                </div>
              </form>
            </div>
          </div>
          <div class="card-footer">
            <div class="text-center">
              <button class="btn btn-primary btn-sm rounded-0" type="submit" form="search-form"><i class="fa fa-search"></i> Search</button>
              <button class="btn btn-default border btn-sm rounded-0" type="reset" form="search-form"><i class="fa fa-reset"></i> Cancel</button>
            </div>
          </div>
        </div>

        <br>
        
        <center>
          <?php echo "<h3>$_SESSION[username]</h3>"; ?>
          <a href="operations.php?operation=logout" class="btn btn-primary w-100">Logout</a>
        </center>

      </div>
    </div>
  </div>

  <br><center><?php echo "<h3>".date("H:i")."</h3>"; ?></center><br>

  

  <!-- Event Details Modal -->
  <div class="modal fade" tabindex="-1" data-bs-backdrop="static" id="event-details-modal">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content rounded-0">
        <div class="modal-header rounded-0">
          <h5 class="modal-title">Event Details</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body rounded-0">
          <div class="container-fluid">
            <dl>
              <dt class="text-muted">Title</dt>
              <dd id="title" class="fw-bold fs-4"></dd>
              <dt class="text-muted">Description</dt>
              <dd id="description" class=""></dd>
              <dt class="text-muted">Start</dt>
              <dd id="start" class=""></dd>
              <dt class="text-muted">End</dt>
              <dd id="end" class=""></dd>
            </dl>
          </div>
        </div>
        <div class="modal-footer rounded-0">
          <div class="text-end">
            <button type="button" class="btn btn-primary btn-sm rounded-0" id="edit" data-id="">Edit</button>
            <button type="button" class="btn btn-danger btn-sm rounded-0" id="delete" data-id="">Delete</button>
            <button type="button" class="btn btn-secondary btn-sm rounded-0" data-bs-dismiss="modal">Close</button>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- Event Details Modal -->

  <?php
  $events = $conn->query("SELECT * FROM `event_list`");
  $result = [];

  foreach ($events->fetch_all(MYSQLI_ASSOC) as $event){
    if($event['user_id'] == $_SESSION['user_id']){
      $event['sdate'] = date("F d, Y h:i A", strtotime($event['start_datetime']));
      $event['edate'] = date("F d, Y h:i A", strtotime($event['end_datetime']));
      $result[$event['id']] = $event;
    }
  }
  ?>
  <?php
  if (isset($conn)) $conn->close();
  ?>
</body>
<script>
  var scheds = $.parseJSON('<?= json_encode($result) ?>')
</script>
<script src="./js/script.js"></script>

</html>