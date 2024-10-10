<?php
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

try{
    $statement = $dbh->prepare("select * from Questions");
    $statement->execute();
    $columns = ['id', 'question', 'response'];
    $quiz = array();
    while($row = $statement->fetch(PDO::FETCH_ASSOC)){
       $quiz.array_push($quiz ,array("question"=>$row[$columns[1]], "response"=>$row[$columns[2]]));
    };
} catch(PDOException $e){
    echo "There was an error fetching rows from Books.";
}
// Come back to here and extract QA pairs from $_GET.
?>