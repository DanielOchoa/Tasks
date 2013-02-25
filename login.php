<?php
// redirect if already logged in
session_start();
if (isset($_SESSION['authenticated'])) {
  header('Location: tasks.php');
}
// li item to display if error
$messages = array();

if (isset($_POST['submit'])) {
  include_once('inc/connection.inc.php');

  ini_set('session.cookie_lifetime', '600');
  $username = trim($_POST['user']);
  $password = trim($_POST['pwd']);
  $redirect = 'tasks.php';

  // db stuff
  $conn = dbConnect();
  $query = 'SELECT * FROM users WHERE username = :username AND password = :password';
  $result = $conn->prepare($query);
  $result->execute(array(':username' => $username, ':password' => $password));
  $result = $result->fetchAll();

  if (count($result) == 0) {
    $messages[] = 'Incorrect user and password combination.';
  } else {
    $_SESSION['authenticated'] = 'godzilla';
    $_SESSION['user_id'] = $result[0]['user_id'];
  }

  // in case some wird db error
  $error = $conn->errorInfo();
  if (isset($error[2])) {
    echo $error[2];
    die();
  }

  // if all is set take you to tasks page.
  if (isset($_SESSION) && $_SESSION['authenticated'] == 'godzilla') {
    header("Location: $redirect");
    exit;
  }
}
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>Sign in to Tasks!</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">

    <!-- Le styles -->
    <link href="assets/css/bootstrap.css" rel="stylesheet">
    <style type="text/css">
      body {
        padding-top: 40px;
        padding-bottom: 40px;
        background-color: #f5f5f5;
      }

      .form-signin {
        max-width: 300px;
        padding: 19px 29px 29px;
        margin: 0 auto 20px;
        background-color: #fff;
        border: 1px solid #e5e5e5;
        -webkit-border-radius: 5px;
           -moz-border-radius: 5px;
                border-radius: 5px;
        -webkit-box-shadow: 0 1px 2px rgba(0,0,0,.05);
           -moz-box-shadow: 0 1px 2px rgba(0,0,0,.05);
                box-shadow: 0 1px 2px rgba(0,0,0,.05);
      }
      .form-signin .form-signin-heading,
      .form-signin .checkbox {
        margin-bottom: 10px;
      }
      .form-signin input[type="text"],
      .form-signin input[type="password"] {
        font-size: 16px;
        height: auto;
        margin-bottom: 15px;
        padding: 7px 9px;
      }
      .welcome {
        text-align: center;
      }
    </style>
    <link href="assets/css/bootstrap-responsive.css" rel="stylesheet">

    <!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
      <script src="assets/js/html5shiv.js"></script>
    <![endif]-->
  </head>

  <body>

    <div class="container">

      <form class="form-signin" method="post">
        <label class="welcome">Welcome to the Alpha Version of <b>Tasks!</b> User/pass : guest/guest</label>
        <h2 class="form-signin-heading">Please sign in</h2>
        <!-- warning displays -->
        <?php
        if (isset($messages) && isset($_POST['submit'])) { ?>
          <div class="alert">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            <ul>
              <?php foreach ($messages as $message) {
                echo '<li>' . $message . '</li>';
              } ?>
            </ul>
          </div>
        <?php } ?>
        <!-- end warnings -->
        <input type="text" name="user" class="input-block-level" placeholder="User">

        <input type="password" name="pwd" class="input-block-level" placeholder="Password">

        <button class="btn btn-large btn-primary btn-block" type="submit" name="submit">Sign in</button>
      </form>

    </div> <!-- /container -->

    <!-- Le javascript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
    <script src="assets/js/bootstrap.min.js"></script>

  </body>
</html>
