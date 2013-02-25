
var Tasks = {
  // strikethrough selected checkbox, and unstrike unchecked
  strikeThrough : function() {
    $('.checkbox input:checkbox').change(function() {
      if ($(this).is(':checked')) {
        $(this).parent().css('text-decoration', 'line-through');
        var liId = $(this).closest('li').attr('id');
        $.post('tasks.php', {enabled : '0', task : liId});
      }
    });
  },
  // unstrike selected task item
  unstrikeThrough : function() {
    $('.checkbox input:checkbox').change(function() {
      if ($(this).is(':not(:checked)')) {
        $(this).parent().css('text-decoration', 'none');
        var liId = $(this).closest('li').attr('id');
        $.post('tasks.php', {enabled : '1', task : liId});
      }
    });
  },
  // if already checked on load, strike through
  checkedOnLoad : function() {
    $('.checkbox :checked').each(function(i) {
      $(this).parent().css('text-decoration', 'line-through');
    });
  },
  // erase task item with ajax using removeTask()
  removeTask : function() {
    $('#sortable .close').click(function() {
      var taskId = $(this).closest('li').attr('id');
      console.log(taskId);
      $.post('tasks.php', {taskdel : taskId})
      // no tasks remaining? add message of empty task list - ajax means default message won't show..
      /*.done(function(data) {
        if ($('.task .alert').length === 0) {
          $('#task-results').append('<p>Your list is empty.</p>');
          alert('hi');
        }
      })*/;
    });
  },
  // run our tasks in the required order
  run : function() {
    this.checkedOnLoad();
    this.strikeThrough();
    this.unstrikeThrough();
    this.removeTask();
  }
};

// run our literal object
Tasks.run();

/**************
//
// Jquery UI //
//
 *************/
$('#sortable').sortable({
  // DOM is re-ordered and then this callback happens, we save the new order to the DB
  update : function(event, ui) {
    var sortedIds = $(this).sortable('toArray');
    $.post('tasks.php', {'order[]' : sortedIds});
  }
});
//$('#sortable').disableSelection(); // dunno why this one yet
