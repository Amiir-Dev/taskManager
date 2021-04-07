<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <title><?= SITE_TITLE ?></title>
  <link rel="stylesheet" href="<?= site_url("assets/css/style.css") ?>">
</head>

<body>
  <!-- partial:index.partial.html -->
  <div class="page">
    <div class="pageHeader">
      <div class="title">Dashboard</div>
      <div class="userPanel">
        <a href="<?= site_url("?logout=1") ?>" style="color: white"><i class="fa fa-sign-out"></i></a>
        <span class="username"> <?= "[ " . $user->name . " ]" ?? 'UnKnown' ?> </span>
        <img src="<?= $user->image ?>" width="40" height="40" />
      </div>
    </div>
    <div class="main">
      <div class="nav">
        
        <!-- Search box -->
        <div class="searchbox">
          <a href="<?= shapeSpace_add_var(current_site_url(), 'searchTask', $_GET['searchTask']) ?>">
            <div><i class="fa fa-search"></i>
          </a>
          <input type="text" name="searchTask" id="search" placeholder="search your task ..." />
        </div>
      </div>

      <!-- folders list -->
      <div class="menu">
        <div class="title">Folders</div>
        <ul id="folders-list">
          <li class="<?= isset($_GET['folder_id']) ? '' : 'active' ?>">
            <a href="<?= site_url() ?>"><i class="fa fa-tasks"></i>All Tasks</a>
          </li>
          <?php foreach ($folders as $folder) : ?>
            <li class="<?= ($_GET['folder_id'] == $folder->id) ? 'active' : '' ?>">
              <a href="<?= site_url("?folder_id=$folder->id") ?>"><i class="fa fa-folder"></i><?= $folder->name ?></a>
              <a href="<?= site_url("?remove_folder=$folder->id") ?>" class="remove" onclick="return confirm('Are you sure to delete \n FOLDER: [ <?= $folder->name ?> ] !??')">×</a>
            </li>
          <?php endforeach; ?>
        </ul>
      </div>
      <!-- Add New Folder Box -->
      <div>
        <input type="text" id="addFolderInput" placeholder="Add New Folder">
        <button id='addFolderBtn' class='btn clickable'>+</button>
      </div>
    </div>
    <div class="view">
      <div class="viewHeader">
        <div class="title">
          <input type="text" id="addTaskInput" placeholder="Add New Task">
        </div>
      </div>
      <div class="content">
        <div class="list">
          <div class="title">Tasks List</div>
          <!-- sorting Tasks -->
          <div>
            <a href="<?= shapeSpace_add_var(current_site_url(), "order", "ASC") ?>">
              <div class="fa fa-arrow-up" style="color: brown; margin-left: 10px"> Oldest First</div>
            </a>
            <a href="<?= shapeSpace_add_var(current_site_url(), "order", "DESC") ?>">
              <div class="fa fa-arrow-down" style="color: green; margin-left: 10px"> Newest First</div>
            </a>
          </div>

          <ul>
            <!-- View search task results -->
            <div class="res-style"></div>

            <!-- show tasks here -->
            <?php foreach ($tasks as $task) : ?>
              <li class="<?= $task->is_done ? 'checked' : '' ?>">
                <i data-taskId="<?= $task->id ?>" class="isDone fa clickable <?= $task->is_done ? 'fa-check-square-o' : 'fa-square-o' ?>"></i>
                <span><?= $task->title ?></span>
                <a href="<?= site_url("?remove_task= $task->id") ?>" class="remove" style="margin-right: 10px;" onclick="return confirm('Are you sure to delete \n TASK: [ <?= $task->title ?> ] !??')">×</a>
                <div class="info">
                  <span class="task_id created-at"><?= $task->created_at ?></span>
                </div>
              </li>
            <?php endforeach; ?>

            <!-- Pagination -->
            <div class=pagination>

              <a href="?page=<?= $_GET['[page'] - 1 ?>" class="<?= $_GET['page'] == 1 ? 'pn-active' : 'pn' ?>"> &laquo; </a>
              <?php for ($i = 1; $i <= $rows; $i++) : ?>
                <a class="<?= $_GET['page'] == $i ? 'pn-active' : 'pn' ?>" href="<?= shapeSpace_add_var(site_url(), 'page', $i) ?>"> <?= $i ?> </a>
              <?php endfor; ?>
              <a href="?page=<?= $_GET['[page'] + 1 ?>" class="<?= $_GET['page'] == $rows ? 'pn-active' : 'pn' ?>"> &raquo; </a>

            </div>

          </ul>
        </div>
      </div>
    </div>
  </div>
  </div>
  <!-- partial -->
  <script src='https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js'></script>
  <script src="<?= BASE_URL ?>assets/js/script.js"></script>
  <script>
    $(document).ready(function() {

      $('.isDone').click(function(e) {
        var tId = $(this).attr('data-taskId');
        $.ajax({
          url: "process/ajaxHandler.php",
          method: "POST",
          data: {
            action: "doneSwitch",
            taskId: tId
          },
          success: function(response) {
            location.reload();
          }
        });
      });


      $('#addFolderBtn').click(function(e) {
        var input = $('input#addFolderInput');
        $.ajax({
          url: "process/ajaxHandler.php",
          method: "POST",
          data: {
            action: "addFolder",
            folderName: input.val()
          },
          success: function(response) {
            if (response >= 1) {
              var newFolderId = response;
              $('<li><a href="?folder_id ="' + newFolderId + '"><i class="fa fa-folder"></i>' + input.val() + '</a></li>').appendTo('#folders-list');
            } else {
              alert(response);
            }
          }
        });
      });


      $('#addTaskInput').on('keypress', function(e) {
        if (e.which == 13) {

          $.ajax({
            url: "process/ajaxHandler.php",
            method: "POST",
            data: {
              action: "addTask",
              folderId: <?php echo $_GET['folder_id'] ?? 0 ?>,
              taskTitle: $('#addTaskInput').val()
            },
            success: function(response) {
              if (response == 1) {
                location.reload();
              } else {
                alert(response);
              }
            }
          });
        }
      });

      $("#search").keyup(function() {
        var searchInput = $("#search");
        var div = document.createElement('div');
        var boxResult = document.getElementById('#searchResult');

        $.ajax({
          url: 'proccess/ajaxHandler.php',
          method: 'POST',
          data: {
            action: 'search',
            searchKey: searchInput.val()
          },
          dataType: 'JSON',
          success: function(response) {
            $.each(response, function(i, item) {
              $('#searchResult').append("<li style='background-color:greenyellow;' class='taskPlace resultTaskSearch " + (item.is_done == "1" ? 'checked' : '') + " '> <i data-taskId=" + item.id + " class='isDone clickable fa " + ((item.is_done == "1") ? 'fa-check-square-o' : 'fa-square-o') + " ' ></i> <span> " + item.title + " </span> <div class='info'> <span class='created-at'> created at " + item.created_at + " </span> <a href='?delete_task=" + item.id + "' class='remove' style='color:red;float:right;' onclick='return confirm('Are you sure to delete " + item.title + "item');'><i class='fa fa-trash-o'></i> </a> </div> </li>");
            });
          }
        });
        $('#searchResult').html('');
      });

      $('#addTaskInput').focus();

    });
  </script>
</body>

</html>