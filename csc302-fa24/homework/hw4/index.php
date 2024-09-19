<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script> 
    <script src="quizzer.js"></script>
    <title>Quizzer</title>

    <!-- TODO: add styles. -->
    <style>
        .incorrect {
            background-color: rgba(161, 11, 11, 0.172);
        }

        .correct {
            background-color: rgba(11, 161, 11, 0.172);
        }

        .panel {
            border: 1px solid black;
            padding: 1em;
            padding-top: 0;
            margin-bottom: 0.5em;
        }

        .hidden {
            display: none;
        }

        .toggle-link {
            margin-bottom: 1em;
            display: inline-block;
            cursor: pointer;
            color: blue;
            text-decoration: underline;
        }

    </style>
</head>
<body class="quizzer">
    <h1>Quizzer</h1>
    <a href="quizzer-admin.php" class="quizzer">Switch to Quizzer Admin</a>

    <div id="quiz-panel" class="panel quizzer">
        <h2>Quiz</h2>
        <span id="score"></span>
        <ol id="quiz">
        <?php
            
            $quiz = [
                "What is this class calles?" => "CSC302",
                "What is Teos first name?" => "toe",
                "How many languages have we learned yet?" => "8",
            
            ];

            // Loop through each question in the  array
            foreach ($quiz as $question => $answer) {
                echo '<li>';
                echo '<label>' . htmlspecialchars($question) . '</label><br>';
                echo '<textarea rows="3" class="response"></textarea></li>';
               
            }
         
        ?>


        </ol>

        <button id="check-quiz">Check</button>
        <button id="reset-quiz">Reset</button>
    </div>


    <footer>
        <?php 
            echo "Generated on: " . date("Y-m-d");
        ?>
    </footer>
</body>
</html>