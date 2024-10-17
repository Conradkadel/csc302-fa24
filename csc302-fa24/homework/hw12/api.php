<?php
header('Content-type: application/json');

// For debugging:
error_reporting(E_ALL);
ini_set('display_errors', '1');

require_once('db.php');

function signup($data){
    // TODO: add code to check that username and password params are
    //       present.

    $password = $data['password'];
    $saltedHash = password_hash($password, PASSWORD_BCRYPT);
    addUser($data['username'], $saltedHash);
    echo json_encode(['success' => true]);
}


function authenticate($username, $password){
    // TODO: add code to check that username and password params are
    //       present.

    $userInfo = getUserByUsername($username,$password);

    if($userInfo != null && password_verify($data['password'], $userInfo['password'])){
        echo json_encode(['success' => true]);

    } else {
        echo json_encode(['success' => false]);

    }
}

function getUserByUsername($username, $password){
    global $dbh;
    try {
        $statement = $dbh->prepare("select * from Users where username = :username");
        $statement->execute(
            [':username' => $username],
            [':password' => $password]
        );
        $row = $statement->fetch(PDO::FETCH_ASSOC);
        return $row;

    } catch(PDOException $e){
        die(json_encode([
            'success' => false, 
            'error' => "There was an error fetching rows from table $table: $e"
        ]));
    }
}

// Handle incoming requests.
if(array_key_exists('action', $_POST)){
    $action = $_POST['action'];

    if($action == 'getQuizItems'){
        echo json_encode(getQuizItems());


    } else if($action == 'addQuizItem'){
        echo json_encode(addQuizItem($_POST['quizId'], $_POST['question'], $_POST['answer']));

    } else if($action == 'removeQuizItem') {
        echo json_encode(removeQuizItem($_POST['quizItemId']));


    } else if($action == 'updateQuizItem'){
        echo json_encode(updateQuizItem($_POST['quizItemId'], $_POST['quizId'], $_POST['question'], $_POST['answer']));

    } else if($action == 'addUser'){
        echo json_encode(addUser($_POST['username'],$_POST['passwort']));

    } else if($action == 'addQuiz'){
        echo json_encode(addQuiz($_POST['name'], $_POST['authorId']));




    } else {
        echo json_encode([
            'success' => false, 
            'error' => 'Invalid action: '. $action
        ]);
    }
}

?>