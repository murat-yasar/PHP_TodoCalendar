<?php
require_once('db-connect.php');
session_start();

if (!isset($_SESSION)) {
  $_SESSION['login']= false;
  $_SESSION['error']= '';
  $_SESSION['username']= $_POST['username'];
  $_SESSION['password']= '';
  $_SESSION['user_id']= '';
}
?>

<html lang="en de tr">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet"  href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/css/bootstrap.min.css" 
  integrity="sha384-F3w7mX95PdgyTmZZMECAngseQB83DfGTowi0iMjiWaeVhAn4FJkqJByhZMI3AhiU" crossorigin="anonymous">
  <title>Login</title>
</head>

<body class="bg-light">
  <div class="d-flex align-items-center justify-content-center p-4"></div>
  <div class="container d-flex align-items-center justify-content-center">
    <div class="card bg-light" style="width: 18rem;">
      <div class="card-header bg-primary"> Login </div>
      <div class="card-body">

        <!-- ERROR -->
        <?php if($_SESSION['error']): ?>
          <div class="alert alert-danger"> <?= $_SESSION['error'] ?> </div>
        <?php endif; ?> 

        <!-- Form -->
        <form action="<?php echo $_SERVER['PHP_SELF'];?>" method="post">
          <label for="username" class="text-success">User Name</label>
          <input type="text" name="username" value="" class="form-control">
          <label for="password" class="text-success">Password</label>
          <input type="text" name="password" value="" class="form-control mb-4">
          <button class="btn btn-success mb-2 w-100">Login</button>
        </form>

        <center>or</center>

        <!-- Form -->
        <form action="signup.php" method="get">
          <button class="btn btn-primary mt-3 mb-2 w-100">Sign up</button>
        </form>

      </div>
    </div>
  </div>
</body>

</html>

<?php
// echo "<pre>";
// print_r($_SESSION);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $users = $conn->query("SELECT * FROM `user_list`");
  
  foreach ($users->fetch_all(MYSQLI_ASSOC) as $user){
    if ($_POST['username'] == $user['username'] && $_POST['password'] == $user['password']){
      $_SESSION['username'] = $user['username'];
      $_SESSION['user_id'] = $user['user_id'];
      $_SESSION['email'] = $user['email'];
      $_SESSION['error'] = '';
      $_SESSION['login'] = true;
      header('Location:index.php');
      break;
    } else {
      $_SESSION['login']= false;
    }
  }

  if(!$_SESSION['login']) {
    $_SESSION['error'] = "The username or password is wrong!";
  }
}
?>