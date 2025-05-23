<?php
session_start();
include('config.php');

header('Content-Type: application/json');

if (!isset($_SESSION["user_id"])) {
    echo json_encode(["error" => "User not logged in"]);
    exit();
}

$user_id = $_SESSION["user_id"];
$response = [];

$stmt = $conn->prepare("SELECT message, reply, timestamp FROM messages WHERE user_id = ? ORDER BY timestamp DESC");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

while ($row = $result->fetch_assoc()) {
    $response[] = [
        "message" => htmlspecialchars($row["message"]),
        "reply" => htmlspecialchars($row["reply"] ?? ""),
        "timestamp" => date('H:i', strtotime($row["timestamp"]))
    ];
}

$stmt->close();
echo json_encode($response);
?>
