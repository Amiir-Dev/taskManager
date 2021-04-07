<?php defined("BASE_PATH") or die("Permission Denied!");

function isAjaxRequest(){
    if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
        return true;
    }
    return false;
}

function redirect($url){
    header("location: $url");
    die();
}

function diePage($msg){
    echo "<div style = 'background-color: #ffc3c3; padding: 20px; margin: 120px 98px; border-radius: 5px; border: 1px solid #c3a3a3; font-family: sans-serif; color: #883939; font-size: large'>$msg</div>";
    die();
}

function message($msg, $messageStyle){
    echo "<div class = '$messageStyle'> $msg </div>";
}

function site_url($uri = ''){
    return BASE_URL . $uri;
}

function current_site_url($uri = ''){
    return CURRENT_URL . $uri;
}

function dd($var){
    echo "<pre style = color: 9c1400; background: #fff; z-index: 999; position: relative; padding: 10px; margin: 10px; border-radius: 5px; border-left: 3px solid red>";
    var_dump($var);
    echo "</pre>";
}

function shapeSpace_add_var($url, $key, $value) {
	
	$url = preg_replace('/(.*)(?|&)'. $key .'=[^&]+?(&)(.*)/i', '$1$2$4', $url .'&');
	$url = substr($url, 0, -1);
	
	if (strpos($url, '?') === false) {
		return ($url .'?'. $key .'='. $value);
	}
    else {
		return ($url .'&'. $key .'='. $value);
	}
}
