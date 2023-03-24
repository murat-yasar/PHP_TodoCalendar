<?php
require_once('db-connect.php');
session_start();
?>

<html lang="en de tr">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet"  href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/css/bootstrap.min.css" 
  integrity="sha384-F3w7mX95PdgyTmZZMECAngseQB83DfGTowi0iMjiWaeVhAn4FJkqJByhZMI3AhiU" crossorigin="anonymous">
  <title>Sign Up</title>
</head>

<body class="bg-light">
  <div class="d-flex align-items-center justify-content-center p-4"></div>
  <div class="container d-flex align-items-center justify-content-center">
    <div class="card bg-light" style="width: 18rem;">
      <div class="card-header bg-primary"> Sign up </div>
      <div class="card-body">

        <!-- ERROR -->
        <?php if($_SESSION['error']): ?>
          <div class="alert alert-danger"> <?= $_SESSION['error'] ?> </div>
        <?php endif; ?> 

        <!-- SIGN UP -->
        <form action="<?php echo $_SERVER['PHP_SELF'];?>" method="post">
          <label for="username" class="text-success">User Name</label>
          <input type="text" name="username" value="" class="form-control" required>
          <label for="password" class="text-success">Password</label>
          <input type="text" name="password" value="" class="form-control" required>
          <label for="email" class="text-success">E-mail</label>
          <input type="email" name="email" value="" class="form-control" required>
          <button class="btn btn-primary mt-4 mb-2 w-100">Sign Up</button>
        </form>

        <center>or</center>

        <!-- LOGIN -->
        <form action="login.php">
          <button class="btn btn-secondary mt-4 mb-2 w-100">Back to Login</button>
        </form>

      </div>
    </div>
  </div>

<?php
echo "<pre>";

if ($_SERVER["REQUEST_METHOD"] == "POST"){
  $_SESSION['username'] = $_POST['username'];
  $_SESSION['email'] = $_POST['email'];
  $_SESSION['password'] = $_POST['password'];

  $users = $conn->query("SELECT * FROM `user_list`");
  foreach ($users->fetch_all(MYSQLI_ASSOC) as $user){
    if($_SESSION['username'] == $user['username'] || $_SESSION['email'] == $user['email']){
      $_SESSION['error'] = "The username or Email is already registered!";
      echo "<script> alert('$_SESSION[error]'); location.replace('./') </script>";
    }
  }
  
  $sql = "INSERT INTO `user_list` (`username`,`email`,`password`) VALUES ('$_POST[username]','$_SESSION[email]','$_SESSION[password]')";
  $save = $conn->query($sql);
  if($save) echo "<script> alert('You have been successfully registered!'); location.replace('./') </script>";
  else{  // Error - DB Connection
    echo "<pre>";
    echo "An Error occured.<br>";
    echo "Error: ".$conn->error."<br>";
    echo "SQL: ".$sql."<br>";
    echo "</pre>";
  }
}

// Delete data by the end of session
// $_SESSION['username'] = null;
// $_SESSION['password'] = null;
// $_SESSION['email'] = null;
// $_SESSION['error'] = null;

// session_destroy();
?>

</body>

</html>