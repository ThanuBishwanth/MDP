<?php
header('Content-Type: application/json');
$servername = "localhost";
$username = "root"; // Your MySQL username
$password = "";     // Your MySQL password
$dbname = "disease_prediction";

// Connect to the database
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Helper function to extract keywords, ignoring common words
function extractKeywords($text) {
    $commonWords = ['what', 'is', 'the', 'of', 'and', 'to', 'for', 'in', 'a'];
    $words = explode(' ', strtolower($text));
    $keywords = array_filter($words, function($word) use ($commonWords) {
        return !in_array($word, $commonWords);
    });
    return $keywords;
}

$response = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userQuestion = $_POST['question'];
    $userKeywords = extractKeywords($userQuestion);

    $matches = [];

    // Fetch all questions and responses from the database
    $result = $conn->query("SELECT question, response FROM faq");
    while ($row = $result->fetch_assoc()) {
        $dbQuestion = strtolower($row['question']);
        $dbKeywords = extractKeywords($dbQuestion);

        // Calculate keyword match percentage
        $matchCount = count(array_intersect($userKeywords, $dbKeywords));
        $totalKeywords = count($userKeywords);
        $matchPercentage = $totalKeywords > 0 ? ($matchCount / $totalKeywords) * 100 : 0;

        // If match percentage is at least 50%, add to matches
        if ($matchPercentage >= 50) {
            $matches[] = [
                "question" => $row['question'],
                "response" => $row['response']
            ];
        }
    }

    // Respond with matched questions and their responses
    if (count($matches) > 0) {
        $response = ["matches" => $matches];
    } else {
        $response = ["reply" => "I'm not sure about that. Please try asking a different question."];
    }
} 

// Output as JSON
echo json_encode($response);

$conn->close();
?>
