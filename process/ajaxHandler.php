<?php

include "../bootstrap/init.php";


if (!isAjaxRequest()) {
    diePage("Invalid Request!");
}

if (!isset($_POST['action']) || empty($_POST['action'])) {
    diePage("Invalid Action!");
}



switch ($_POST['action']) {
    case "addFolder":
        if (!isset($_POST['folderName']) || strlen($_POST['folderName']) < 3) {
            echo "Folder name must be at least 3 characters";
            die();
        }
        echo addFolder($_POST['folderName']);
        break;


    case "addTask":
        $folderId = $_POST['folderId'];
        $taskTitle = $_POST['taskTitle'];
        //echo "I Get Ajax Request :))";
        if (!isset($folderId) || empty($folderId)) {
            echo "please choose your folder to add Task into it!";
            die();
        }
        if (!isset($taskTitle) || strlen($taskTitle) < 3) {
            echo "Task title must be at least 3 characters!";
            die();
        }
        echo addTask($taskTitle, $folderId);
        break;


    case "doneSwitch":
        $task_id = $_POST['taskId'];
        if (!isset($task_id) || !is_numeric($task_id)) {
            echo "Invalid Task ID!!";
            die();
        }
        echo taskDoneToggle($task_id);
        break;

    case 'search':
        $searchKey = $_POST['searchKey'];
        if (strlen(trim($searchKey)) > 0) {
            $result = taskSearch($searchKey);
            echo json_encode($result);
        }
        break;
    default:
        diePage("Invalid Request!");
}
