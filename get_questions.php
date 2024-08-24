<?php
$servername = "sql209.infinityfree.com";
$username = "if0_36812253";
$password = "SNYCJyjjL99oU4";
$dbname = "if0_36812253_math_db";

// Creare conexiune
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificare conexiune
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Adăugarea întrebărilor aleatorii
$sql = "SELECT id, question, option1, option2, option3, option4, correct_option, image_url FROM questions ORDER BY RAND() LIMIT 5";
$result = $conn->query($sql);

$questions = array();
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $questions[] = $row;
    }
}

$conn->close();

echo json_encode($questions);
?>
