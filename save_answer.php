<?php

date_default_timezone_set('Europe/Bucharest');

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

// Preluare date JSON
$data = json_decode(file_get_contents('php://input'), true);

$question_id = $data['question_id'];
$chosen_option = $data['chosen_option'];
$correct_option = $data['correct_option'];
$user_ip = $_SERVER['REMOTE_ADDR']; // Preluare adresa IP a utilizatorului

// Interogare API ip-api.com pentru a obține informațiile de locație
$location_data = @file_get_contents("http://ip-api.com/json/{$user_ip}");
$location_info = json_decode($location_data, true);

$region_name = $location_info['regionName'] ?? 'Unknown';
$city = $location_info['city'] ?? 'Unknown';

// Obține timestamp-ul curent
$current_datetime = date('Y-m-d H:i:s');

// Salvare răspuns în baza de date
$sql = "INSERT INTO user_answers (question_id, chosen_option, correct_option, user_ip, region_name, city, timestamp) VALUES (?, ?, ?, ?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("iiissss", $question_id, $chosen_option, $correct_option, $user_ip, $region_name, $city, $current_datetime);

$response = array();
if ($stmt->execute()) {
    $response['success'] = true;
} else {
    $response['success'] = false;
    $response['message'] = "Error: " . $stmt->error;
}

$stmt->close();
$conn->close();

echo json_encode($response);
?>
