<?php defined("BASE_PATH") or die("Permission Denied!");

# ---- Folders Function ----#
function addFolder($folder_name){
    global $pdo;
    $current_user_id = getCurrentUserId();
    $sql = "INSERT INTO folders (name, user_id) VALUES (:folder_name, :user_id);";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':folder_name' => $folder_name, ':user_id' => $current_user_id]);
    return $pdo->lastInsertId();
}

function getFolders(){
    global $pdo;
    $current_user_id = getCurrentUserId();
    $sql = "SELECT * FROM folders WHERE user_id = $current_user_id;";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $records = $stmt->fetchAll(PDO::FETCH_OBJ);
    return $records;
}

function removeFolder($folder_id){
    global $pdo;
    $sql = "DELETE FROM folders WHERE id = $folder_id;";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    return $stmt->rowCount();
}

function pagination(){
    global $pdo;
    $current_user_id = getCurrentUserId();

    # check if folder selected
    $folder = $_GET['folder_id'] ?? NULL;
    $folderCondition = '';
    if (isset($folder) and is_numeric($folder)) {
        $folderCondition = "and folder_id = $folder";
    }

    # check order condition
    $orderBy = $_GET['order'] ?? NULL;
    $order = '';
    if(isset($orderBy)){
        $order = "ORDER BY created_at $orderBy";
    }
    $sql = "SELECT COUNT(*) FROM tasks WHERE user_id = :user_ID {$folderCondition} $order;";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':user_ID' => $current_user_id]);
    $records = $stmt->fetch();
    $rowInEveryPage = ceil((int)$records[0] / TASK_EVERY_PAGE);
    return $rowInEveryPage;
}

# ---- Tasks Function ----# 
function addTask($taskTitle, $folderId){
    global $pdo;
    $current_user_id = getCurrentUserId();
    $sql = "INSERT INTO tasks (title, user_id, folder_id) VALUES (:task_title, :user_id, :folder_id);";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':task_title' => $taskTitle, ':user_id' => $current_user_id, ':folder_id' => $folderId]);
    return $stmt->rowCount();
}

function getTasks(){

    $page = isset($_GET['page']) ? $_GET['page'] : 1;
    $numPage = ((int) $page * TASK_EVERY_PAGE) - TASK_EVERY_PAGE;

    global $pdo;
    $current_user_id = getCurrentUserId();

    # check if folder selected
    $folder = $_GET['folder_id'] ?? NULL;
    $folderCondition = '';
    if (isset($folder) and is_numeric($folder)) {
        $folderCondition = "and folder_id = $folder";
    }

    # check order condition
    $orderBy = $_GET['order'] ?? NULL;
    $order = '';
    if(isset($orderBy)){
        $order = "ORDER BY created_at $orderBy";
    }
    $limitation = "LIMIT $numPage ," . TASK_EVERY_PAGE;
    $sql = "SELECT * FROM tasks WHERE user_id = $current_user_id {$folderCondition} {$order} {$limitation};";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $records = $stmt->fetchAll(PDO::FETCH_OBJ);
    return $records;
}

function removeTask($task_id){
    global $pdo;
    $sql = "DELETE FROM tasks WHERE id = $task_id;";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    return $stmt->rowCount();
}

function taskDoneToggle($task_id){
    global $pdo;
    $current_user_id = getCurrentUserId();
    $sql = "UPDATE tasks SET is_done = 1 - is_done WHERE user_id = :userID AND id = :taskID";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':taskID' => $task_id, ':userID' => $current_user_id]);
    return $stmt->rowCount();
}

function taskSearch($key)
{
    global $pdo;
    $current_user_id = getCurrentUserId();
    $sql = "SELECT * FROM tasks WHERE user_id = :user_id and title LIKE :searchKey";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':user_id', $current_user_id );
    $stmt->bindValue(':searchKey', $key . '%');
    $stmt->execute();
    $records = $stmt->fetchAll(PDO::FETCH_OBJ);
    return $records ?? null;
}