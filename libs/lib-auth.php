<?php defined("BASE_PATH") or die("Permission Denied!");

# Get LoggedIn User Id
function getCurrentUserId(){
    return getLoggedInUser()->id ?? 0;
    // return 1;
}

# name validation function
function nameValidate($inputName){
    if (preg_match("@[A-Z]@", $inputName) || preg_match("@[a-z]@", $inputName)){
        return true;
    }
    return false;
}

# password validate fucntion
function passValidate($pass){
    
    if (!empty($_POST["password"]) && $_POST["password"] != "") {
        if (strlen($pass) <= '8') {
            $err .= "[At Least 8 Digits]" . PHP_EOL;
        }
        else if (!preg_match("@[0-9]@", $pass)) {
            $err .= "[At Least 1 Number]" . PHP_EOL;
        }
            if(!nameValidate($pass)){
            $err .= "[At Least 1 Capital And Lowercase Letter]" . PHP_EOL;
        }
        // else {
        //     $err .= "[Please Enter your password]" . "<br>";
        // }
    }
    return $err;
}
function getUserByEmail($email){
    global $pdo;
    $sql = "SELECT * FROM users WHERE email = :email";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':email' => $email]);
    $records = $stmt->fetchAll(PDO::FETCH_OBJ);
    return $records[0] ?? NULL;
}

//var_dump(getUserByEmail($records));

function login($email, $pass){
    $user = getUserByEmail($email);

    if(is_null($user)){
        return false;
    }

    # check the password
    if(password_verify($pass, $user->password)){
        # successfully login
        $user->image = "https://www.gravatar.com/avatar/" . md5( strtolower( trim( $user->email ) ) );
        $_SESSION['login'] = $user;
        return true;
    }
    return false;
}

# Login fucntion
function isLogedIn(){
    return isset($_SESSION['login']) ? true : false;
}

function getLoggedInUser(){
    return ($_SESSION['login']) ?? NULL;
    
}

function logout(){
    unset($_SESSION['login']);
}

# Register fucntion
function register($userData){
    global $pdo;
    $Pass = $userData['password'];


    echo password_hash('salam', PASSWORD_BCRYPT);
    echo "salam";

    #Name Validation
    if(!nameValidate($userData['name'])){
        echo "Your Name Must Contain Lowercase And UpperCase Letters";
        return false;
    }

    #Email Validation
    if(!filter_var($userData['email'], FILTER_VALIDATE_EMAIL)){
        echo "Your Email Is Not Valid!";
        return false;
    }

    #Password Validation 
    if(!is_null(passValidate($Pass))){
        echo "Your Password Must Contain " . passValidate($Pass);
        return false;
    }

    #Hash Pass
    $hashPass = password_hash($userData['password'], PASSWORD_BCRYPT);

    $checkExistQuery = "SELECT id FROM users WHERE email LIKE :enterEmail";
    $checkStmt = $pdo->prepare($checkExistQuery);
    $checkStmt->execute([':enterEmail' => $userData['email']]);
    $count = $checkStmt->rowCount();
    if ($count) {
        echo "You have already registered with this email!";
        return false;
    } else {
        $sql = "INSERT INTO users (name, email, password) VALUES (:name, :email, :pass);";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':name' => $userData['name'], ':email' => $userData['email'], ':pass' => $hashPass]);
        return $stmt->rowCount() ? true : false;
    }
}


