<?php
session_start();
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

$question_id = $_POST['question_id'];
$selected_option = $_POST['answer'];

$sql = "SELECT correct_option FROM questions WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $question_id);
$stmt->execute();
$stmt->bind_result($correct_option);
$stmt->fetch();
$stmt->close();

$is_correct = ($selected_option == $correct_option);

if ($is_correct) {
    $_SESSION['score'] += 1;
}

$_SESSION['current_question'] += 1;

$conn->close();

header("Location: quiz.php");
exit();
?>
