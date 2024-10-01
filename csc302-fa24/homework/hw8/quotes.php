<?php
// Worked with Conrad and Troy
// Extracts the value of n from GET parameters.
// Function to generate random quotes from CSV
// Used CHATGBT for help throughout function was not sure how to construct fn myself 
function generateQuotes($quoteNum) {
    $filename = 'quotes.csv';
    
    // Initialize array to hold quotes
    $quotes = [];

    // Open the CSV file for reading
    if (($handle = fopen($filename, 'r')) !== FALSE) {
        // Skip the header
        fgetcsv($handle);

        // Read each row in the CSV file
        while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
            // Check if the quote is valid (not empty)
            if (!empty($data[1])) {
                $quotes[] = [
                    'author' => $data[0],
                    'quote'  => $data[1]
                ];
            }
        }

        // Close the file
        fclose($handle);
    } else {
        echo json_encode(["error" => "Could not open the CSV file."]);
        return;
    }

    // Ensure the requested number of quotes doesn't exceed the total available
    $quoteNum = min($quoteNum, count($quotes));

    // Shuffle the array of quotes to randomize
    shuffle($quotes);

    // Select the requested number of random quotes
    $randomQuotes = array_slice($quotes, 0, $quoteNum);

    // Return the selected quotes as a JSON response
    echo json_encode($randomQuotes, JSON_PRETTY_PRINT);
}

    // For DEBUGGING
    error_reporting(E_ALL);
    ini_set('display_errors', '1');

    // PHP response code
    $quoteNum = $_POST['quotes'];
    // Send $quoteNum as param into fn
    generateQuotes($quoteNum);
?>