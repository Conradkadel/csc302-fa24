<?php
// CREATORS = TEO , CONRAD
// Worked together
require_once("questions.php");

error_reporting(E_ALL);
ini_set('display_errors', '1');

$dbName = 'data.db';  

$matches = [];
preg_match('#^/~([^/]*)#', $_SERVER['REQUEST_URI'], $matches);
$homeDir = count($matches) > 1 ? $matches[1] : '';
$dataDir = "/home/$homeDir/www-data";
if(!file_exists($dataDir)){
    $dataDir = __DIR__;
}

try {
    $dbh = new PDO("sqlite:$dataDir/$dbName");
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    $dbh->exec('CREATE TABLE IF NOT EXISTS quizItemResponse ( 
        id INTEGER PRIMARY KEY AUTOINCREMENT, 
        question TEXT, 
        userResponse TEXT, 
        correct BOOLEAN )');
    
} catch(PDOException $e) {
    echo "There was an error creating the quizItemResponse table: " . $e->getMessage();
    exit();
}

$gradedQuiz = array('questions' => array());
$userResponses = json_decode($_POST["response"], true);
$score = 0;

foreach($userResponses["questions"] as $question) {
    $questionData = array(
        "id" => $question["id"],
        "question" => $quiz[$question["id"]]["question"],
        "response" => $question["response"],
        "correct" => $question["response"] == $quiz[$question["id"]]["answer"]
    );
    
    // Insert question and user response into the database.
    // Support with ChatGPT for HW
    try {
        $statement = $dbh->prepare('INSERT INTO quizItemResponse (question, userResponse, correct) 
                                    VALUES (:question, :userResponse, :correct)');
        
        $statement->execute([
            ':question' => $questionData["question"],
            ':userResponse' => $questionData["response"],
            ':correct' => $questionData["correct"] ? 1 : 0  // Use 1 for TRUE and 0 for FALSE
        ]);

        echo "Data inserted successfully";
        
    } catch(PDOException $e) {
        echo "There was an error adding a question: " . $e->getMessage();
    }

    // Update score.
    if ($questionData["correct"]) {
        $score++;
    }

    // Append this question to the graded quiz.
    array_push($gradedQuiz['questions'], $questionData);
}

$gradedQuiz["score"] = $score;
$gradedQuiz["submissionId"] = 0; // This needs to be updated in the future.

echo json_encode($gradedQuiz);
?>
