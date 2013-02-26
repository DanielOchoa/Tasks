
var Tasks = {
  // strikethrough selected checkbox, and unstrike unchecked
  strikeThrough : function() {
    $('.checkbox input:checkbox').change(function() {
      if ($(this).is(':checked')) {
        $(this).parent().css('text-decoration', 'line-through');
        var liId = $(this).closest('li').attr('id');
        $.post('tasks.php', {enabled : '0', task : liId, messages : 1});
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
      $.post('tasks.php', {taskdel : taskId})
      // no tasks remaining? add message of empty task list - ajax means default message won't show..
      .done(function(data) {
        if ($('.task .alert').length === 0) {
          $('#messages').append('<p>Your list is empty.</p>');
        }
      });
    });
  },
  ajaxAddTask : function() {
    $('#addTask').submit(function() {
      var thisTask = $('#addTask input').val();
      $.post('tasks.php', {newtask : thisTask})
      .done(function(data) {
        if (thisTask.length !== 0) {
          $('#messages').empty();
          $('#addTask input').val('');
          $('#task-results').empty().append(data);
        } else {
          $('#messages').append(data);
        }
      });
      // prevent form submission...
      return false;
    });
  },
  // jQuery UI
  sortableUI : function() {
    $('#sortable').sortable({
      // DOM is re-ordered and then this callback happens, we save the new order to the DB
      update : function(event, ui) {
        var sortedIds = $(this).sortable('toArray');
        $.post('tasks.php', {'order[]' : sortedIds});
      }
    });
  },
  // run our tasks in the required order. ajaxAddTask() is run outside since it's it works outside the list. the list get ajaxed so running it here would cause duplicate entries.
  // whats to prevent someone from running Tasks.ajaxAddTask() 100 times in the console and flooding the DB? nothing. Go ahead.
  run : function() {
    this.checkedOnLoad();
    this.strikeThrough();
    this.unstrikeThrough();
    this.removeTask();
    this.sortableUI();
  }
};

// run our literal object
Tasks.run();
Tasks.ajaxAddTask();
// somewhat dirty.. running tasks.run() again when ajax is completed under ajaxAddTask().. hence why I run it separately otherwise I get multiple entries when ajaxing
$(document).ajaxComplete(function(){
    Tasks.run();
});
