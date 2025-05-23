<?php
session_start();
include('config.php');

header('Content-Type: application/json');

if (!isset($_SESSION["user_id"]) || $_SESSION["role"] !== "admin" || !isset($_GET["user_id"])) {
    echo json_encode(["error" => "Unauthorized"]);
    exit();
}

$user_id = $_GET["user_id"];
$response = [];

$stmt = $conn->prepare("SELECT id, message, reply FROM messages WHERE user_id = ? ORDER BY timestamp ASC");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

while ($row = $result->fetch_assoc()) {
    $response[] = [
        "id" => $row["id"],
        "message" => htmlspecialchars($row["message"]),
        "reply" => htmlspecialchars($row["reply"] ?? "")
    ];
}

$stmt->close();
echo json_encode($response);
?>
