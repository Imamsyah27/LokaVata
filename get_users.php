<?php
session_start();
include('config.php');

header('Content-Type: application/json');

if (!isset($_SESSION["user_id"]) || $_SESSION["role"] !== "admin") {
    echo json_encode(["error" => "Unauthorized"]);
    exit();
}

$response = [];
$query = "SELECT id, name FROM users ORDER BY name ASC";
$result = $conn->query($query);

while ($row = $result->fetch_assoc()) {
    $response[] = [
        "id" => $row["id"],
        "name" => htmlspecialchars($row["name"])
    ];
}

echo json_encode($response);
?>
