<?php
header('Content-type: application/json');

// For debugging:
error_reporting(E_ALL);
ini_set('display_errors', '1');

// TODO Change this as needed. SQLite will look for a file with this name, or
// create one if it can't find it.
$dbName = 'data.db';

// Leave this alone. It checks if you have a directory named www-data in
// you home directory (on a *nix server). If so, the database file is
// sought/created there. Otherwise, it uses the current directory.
// The former works on digdug where I've set up the www-data folder for you;
// the latter should work on your computer.
$matches = [];
preg_match('#^/~([^/]*)#', $_SERVER['REQUEST_URI'], $matches);
$homeDir = count($matches) > 1 ? $matches[1] : '';
$dataDir = "/home/$homeDir/www-data";
if(!file_exists($dataDir)){
    $dataDir = __DIR__;
}
$dbh = new PDO("sqlite:$dataDir/$dbName")   ;
// Set our PDO instance to raise exceptions when errors are encountered.
$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Put your other code here.

createTables();

// Handle incoming requests.
if(array_key_exists('action', $_POST)){
    $action = $_POST['action'];
    if($action == 'add-quiz-item'){
        addQuizItem($_POST);
    } else if ($action == 'remove-quiz-item') {
        removeQuizItem($_POST);
    } else if ($action == 'update_quiz_item') {
        updateQuizItem($_POST);
    } else if($action == 'add-patron') {
        addPatron($_POST);
    } else if($action == 'checkout-book'){
        checkoutBook($_POST);
    } else if($action == 'return-book'){
        returnBook($_POST);
    } else if($action == 'get-overdue-books'){
        getOverDueBooks($_POST);
    } else if($action == 'get-books'){
        getTable('Books');
    } else if($action == 'get-book'){
        getTableRow('Books', $_POST);
    } else if($action == 'get-patrons'){
        getTable('Patrons');
    } else if($action == 'get-patron'){
        getTableRow('Patrons', $_POST);
    } else if($action == 'get-checkouts'){
        getTable('Checkouts');
    } else if($action == 'get-checkout'){
        getTableRow('Checkouts', $_POST);

    } else {
        echo json_encode([
            'success' => false, 
            'error' => 'Invalid action: '. $action
        ]);
    }
}


function createTables(){
    global $dbh;

    // Create the QuizItems table.
    try{
        $dbh->exec('create table if not exists QuizItems('. 
            'id integer primary key autoincrement, '. 
            'question text, answer text, '. 
            'createdAt datetime default(datetime()), '.
            'updatedAt datetime default(datetime()))');
    } catch(PDOException $e){
        echo json_encode([
            'success' => false, 
            'error' => "There was an error creating the tables: $e"
        ]);
    }

}


/**
 * Adds a quiz item to the database. Requires the parameters:
 * - question
 * - answer
 * @param data An associative array holding parameters and their values.
 */

function addQuizItem($data){
    global $dbh;

    try {
        $statement = $dbh->prepare('insert into QuizItems(question, answer) '.
            'values (:question, :answer)');
        $statement->execute([
            ':question'  => $data['question'], 
            ':answer'   => $data['answer']
            ]);

        echo json_encode(['success' => true]);

    } catch(PDOException $e){
        echo json_encode([
            'success' => false, 
            'error' => "There was an error adding a book: $e"
        ]);
    }
    
}

/**
 * Removes a quiz item from the database. Requires the parameters:
 * - id
 * @param data An associative array holding parameters and their values.
 */

function removeQuizItem($data){
    global $dbh;
    $quiz_item_id = $data['quiz_item_id']; // Example value
    
    
    try {
        $statement = $dbh->prepare('delete from QuizItems where id = :quiz_item_id');
        $statement->bindParam(':quiz_item_id', $quiz_item_id, PDO::PARAM_INT);
        $statement->execute();

        echo json_encode(['success' => true]);

    } catch(PDOException $e){
        echo json_encode([
            'success' => false, 
            'error' => "There was an error adding a book: $e"
        ]);
    }
    
}

/**
 * Updates a quiz item from the database. Requires the parameters:
 * - id
 * - new question
 * - new answer
 * @param data An associative array holding parameters and their values.
 */

function updateQuizItem($data){
    global $dbh;

    try {
        $statement = $dbh->prepare('UPDATE QuizItems
                                    SET question = :question, 
                                        answer = :answer, 
                                        updatedAt = datetime(\'now\') 
                                    WHERE id = :quiz_item_id');
        $statement->execute([
            ':question' => $data['updated_question'],
            ':answer' => $data['updated_answer'],
            'quiz_item_id' => $data['quiz_item_id']]);
        echo json_encode(['success' => true]);

    } catch(PDOException $e){
        echo json_encode([
            'success' => false, 
            'error' => "There was an error returning the book: $e"
        ]);
    }
}


?>