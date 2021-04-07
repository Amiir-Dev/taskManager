<?php
include "bootstrap/init.php";




if (isset($_GET['logout'])) {
    logout();
}

if (!isLogedIn()) {
    redirect(site_url('auth.php'));
}

# user is loggedIn
$user = getLoggedInUser();

if (isset($_GET["remove_folder"]) && is_numeric($_GET["remove_folder"])) {
    $removedCount = removeFolder($_GET["remove_folder"]);
    // echo "$removedCount folder was removed successfully!";
}

if (isset($_GET["remove_task"]) && is_numeric($_GET["remove_task"])) {
    $removedCount = removeTask($_GET["remove_task"]);
    // echo "$removedCount task was removed successfully!";
}

$folders = getFolders();

if (isset($_GET["order"])) {
    getTasks($_GET["order"]);
}

$rows = pagination();

if (!isset($_GET['page'])) {
    $_GET['page']  = 1;
}

$tasks = getTasks();

include BASE_PATH . "tpl/tpl-index.php";
