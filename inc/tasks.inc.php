<?php

// tie our logged in user to it's tasks by userid
class Tsks {
  protected $_queryResults = array();
  protected $_errors = array();
  protected $_query = '';
  protected $_deleteQuery = '';
  protected $_userId = '';
  protected $_conn = Object;

  public function __construct($userId) {
    $this->_userId = $userId;
    $this->makeQuery();
  }

  public function postHandler() {
    // these posts are defined in the main.js file.
    // if we delete a task..
    if (isset($_POST['taskdel']) && is_numeric($_POST['taskdel'])) {
      $this->removeTask($_POST['taskdel']);
      // add error reporting here later
      exit;
    }
    // if we order a task...
    if (isset($_POST['order']) && is_array($_POST['order'])) {
      $this->saveNewOrder();
      // add error reporting here
      exit;
    }
    // if we enable/disable a task
    if (isset($_POST['enabled']) && isset($_POST['task'])) {
      $this->saveEnabled();
      // add error reportin here...
      exit;
    }
    // add tasks...
    if (isset($_POST['newtask'])) {
      if (empty($_POST['newtask'])) {
        $this->warningBox("You must enter a valid task.");
        exit;
      }
      $this->addTask();
      $this->listDisplay();
      exit;
    }
  }

  public function returnResults() {
    return $this->_queryResults;
  }

  public function errors() {
    if (!empty($this->_errors)) {
      // array of errors
      return $this->_errors;
    } else {
      // no errors? then false
      return FALSE;
    }
  }
  // this function echoes our list!
  public function listDisplay() {
    $this->makeQuery();
    if (empty($this->_queryResults)) {
      echo "<p>Your list is empty.</p>";
    } else {
      // populate our list
      echo '<ul id="sortable">';
      foreach ($this->_queryResults as $item) {
        ($item['enabled'])? $enabled = '' : $enabled = 'checked';
        echo
        '<li class="task" id="' . $item['task_id'] . '">
          <div class="alert">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            <label class="checkbox">
            <input type="checkbox" value="on" ' . $enabled . '>' . $item['task'] . '</label>
          </div>
        </li>';
      }
      echo '</ul>';
    }
  }

  private function removeTask($task_id) {
    foreach ($this->_queryResults as $item) {
      if ($item['task_id'] == (int) $_POST['taskdel']) {
        $this->_deleteQuery = 'DELETE FROM `tasks` WHERE `task_id` = :task_id AND `user_id` = :userid';
        if (!$result = $this->_conn->prepare($this->_deleteQuery)) {}
        if (!$result->execute(array(':task_id' => (int) $_POST['taskdel'], ':userid' => (int) $this->_userId))) {
          $this->_errors[] = 'There was an error deleting this task.';
        }
      }
    }
  }

  // save the order when using jqueryUI ordering
  private function saveNewOrder() {
    $newQuery = 'UPDATE `tasks` SET `order` = :newOrder WHERE `task_id` = :thistaskID AND `user_id` = :userid';
    $newOrder = $_POST['order'];
    $result = $this->_conn->prepare($newQuery) or die('Query Error');
    foreach ($newOrder as $index => $item) {
      $result->execute(array(':newOrder' => $index, ':thistaskID' => (int) $item, ':userid' => (int) $this->_userId));
    }
    // add error return?
  }

  private function saveEnabled() {
    $newQuery = 'UPDATE `tasks` SET `enabled` = :enabled WHERE `task_id` = :thistaskID AND `user_id` = :userid';
    $result = $this->_conn->prepare($newQuery) or die('Query Error');
    if (!$result->execute(array(':enabled' => (int) $_POST['enabled'], ':thistaskID' => (int) $_POST['task'], ':userid' => (int) $this->_userId))) {
      $this->_errors[] = 'Saving the enabled/disabled task has failed.';
    }
  }

  private function addTask() {
    $addQuery = "INSERT INTO `tasks` (`task_id`, `user_id`, `task`, `enabled`, `order`) VALUES (NULL, :user_id, :task, '1', :order)";
    // make the query again, in case we removed items
    $this->makeQuery();

    $orderForThis = count($this->_queryResults) + 1;

    $result = $this->_conn->prepare($addQuery) or die('Query Error');
    if (!$result->execute(array(':user_id' => $this->_userId, ':task' => $_POST['newtask'], ':order' => $orderForThis))) {
      $this->_errors[] = 'Could not run query to add new task.';
    }
  }

  private function makeQuery() {
    // if conn is not set.. set it.
    if (!$this->_conn instanceof PDO) {
      $this->_conn = dbConnect();
      $error = $this->_conn->errorInfo();
      if (isset($error[2])) {
        $this->_errors[] = 'There was an error connecting to the database. Try again later.';
      }
    }
    $this->_query = "SELECT * FROM tasks WHERE user_id = :thisUser ORDER BY `order`";
    $result = $this->_conn->prepare($this->_query);
    $result->execute(array(':thisUser' => $this->_userId));
    $result = $result->fetchAll();
    // returns array of results;
    if (!empty($result)) {
      // set results to queryResults
      $this->_queryResults = $result;
    }
  }

  // html struct to display the warning box...
  private function warningBox($error) {
    if ($error) {
      echo '<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>';
      if (is_array($error)) {
        echo '<ul>';
        foreach ($error as $eachError) {
          echo '<li>' . $eachError . '</li>';
        }
        echo '</ul>';
      } else {
        echo $error;
      }
      echo '</div>';
    } else {
      return false;
    }
  }
}
