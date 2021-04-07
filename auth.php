<?php
include "bootstrap/init.php";


$home_url = site_url();
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $action = $_GET['action'];
    $params = $_POST;

    if ($action == 'register') {
        $result = register($params);
        if (!$result) {
            message("Registration Error!<br>" . $result, "error");
        }
        else {
            message("Your registration was successful (: <br><br>
            <a style = 'text-decoration: none; font-size: medium; color: #2a6123;font-weight: bold;' href='{$home_url}auth.php'>Please Login ...</a>", "success");
        }
    }
    else if ($action == 'login') {
        $result = login($params['email'], $params['password']);
        if (!$result) {
            message("Email OR Password Is Incorrect :(", "error");
        }
        else {
            // message("You are now LogedIn <br> <a href='{$home_url}'>Manage your Tasks</a>", "success");
            redirect(site_url());
        }
    }
}

include "tpl/tpl-auth.php";
