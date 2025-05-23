<?php
session_start();
include('config.php');

header('Content-Type: application/json');

if (!isset($_SESSION["user_id"])) {
    echo json_encode(["error" => "Unauthorized"]);
    exit();
}

$user_id = $_SESSION["user_id"];
$role = $_SESSION["role"];
$response = [];

// Jika user, ambil chat berdasarkan user_id yang login
if ($role === "user") {
    $stmt = $conn->prepare("SELECT message, reply, sender_role, timestamp FROM messages WHERE user_id = ? ORDER BY timestamp ASC");
    $stmt->bind_param("i", $user_id);
}

// Jika admin, ambil user_id dari GET parameter
else if ($role === "admin" && isset($_GET["user_id"])) {
    $stmt = $conn->prepare("SELECT message, reply, sender_role, timestamp FROM messages WHERE user_id = ? ORDER BY timestamp ASC");
    $stmt->bind_param("i", $_GET["user_id"]);
}

$stmt->execute();
$result = $stmt->get_result();

while ($row = $result->fetch_assoc()) {
    $response[] = [
        "sender" => $row["sender_role"],
        "message" => htmlspecialchars($row["message"]),
        "reply" => $row["reply"] ? htmlspecialchars($row["reply"]) : null,
        "timestamp" => date("H:i", strtotime($row["timestamp"]))
    ];
}

$stmt->close();
$conn->close();
echo json_encode($response);
?>
