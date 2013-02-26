<?php
ob_start();
$redirect = 'login.php';
session_start();
if (!isset($_SESSION['authenticated'])) {
  header("Location: $redirect");
  exit;
}

// include this file with php functionality
include_once('inc/connection.inc.php');
include_once('inc/tasks.inc.php');

// call our tasks object, output a list of tasks with ->listDisplay();, see tasks.inc.php for list of functions
// $_SESSION['user_id'] was queried from the db on login.php, used mainly to prevent cross-site forgeries
$tasks = new Tsks($_SESSION['user_id']);

// this will handle all of the POSTs made with jquery $.post...
$tasks->postHandler();
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>Your Tasks</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Welcome to the ALPHA version of Tasks! Your task companion!">
    <meta name="author" content="Daniel Ochoa">

    <!-- Le styles -->
    <link href="assets/css/bootstrap.css" rel="stylesheet">
    <style>
      body {
        padding-top: 60px; /* 60px to make the container go all the way to the bottom of the topbar */
      }
    </style>
    <!--<link href="assets/css/bootstrap-responsive.css" rel="stylesheet">-->
    <link href="assets/css/custom.css" rel="stylesheet">
    <!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
      <script src="../assets/js/html5shiv.js"></script>
    <![endif]-->

  </head>

  <body>

    <div class="navbar navbar-inverse navbar-fixed-top">
      <div class="navbar-inner">
        <div class="container">
          <button type="button" class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="brand" href="tasks.php">Tasks! <span>ALPHA</span</a>
          <div class="nav-collapse collapse">
            <ul class="nav">
              <!--<li class="active"><a href="#">Home</a></li>-->
            </ul>
          </div><!--/.nav-collapse -->
          <?php include_once('inc/logout.inc.php'); ?>
        </div>
      </div>
    </div>

    <div class="container">
      <div class="span8 offset2">
        <div class="text-center">
          <h1>Your Tasks!</h1>
          <p class="lead">Got Tasks? Use this list!</p>
        </div>
        <div id="messages"> <!-- output errors and/or php warnings inside messages -->
          <?php
          $errors = $tasks->errors();
          if ($errors) {
            echo '<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>';
            echo '<ul>';
            foreach ($errors as $error) {
              echo '<li>' . $error . '</li>';
            }
            echo '</ul>';
            echo '</div>';
          }
          ?>
        </div>
        <div class="insert">
          <div class="input-append">
            <form method="post" id="addTask">
              <input class="span7" type="text" name="newtask" placeholder="New Task!" id="appendedInputButton">
              <button type="submit" name="submit" class="btn">&nbsp;&nbsp;Task!&nbsp;&nbsp;&nbsp;</button>
            </form>
          </div>
        </div>
        <div id="task-results" class="results">
          <!-- DISPLAY THE LIST OF TASKS FOR THE USER -->
          <?php $tasks->listDisplay(); ?>
        </div>
      </div>
    </div> <!-- /container -->

    <!-- Le javascript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
    <script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.10.1/jquery-ui.min.js"></script>
    <script src="assets/js/bootstrap.min.js"></script>
    <script src="assets/js/main.js"></script>

  </body>
</html>
