<?php
session_start();
include('config.php');

header('Content-Type: application/json'); // Pastikan response dikembalikan dalam format JSON

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["message"])) {
    if (!isset($_SESSION["user_id"])) {
        echo json_encode(["success" => false, "error" => "User belum login"]);
        exit();
    }

    $user_id = $_SESSION["user_id"];
    $message = trim($_POST["message"]);

    if (!empty($message)) {
        $stmt = $conn->prepare("INSERT INTO messages (user_id, message) VALUES (?, ?)");
        $stmt->bind_param("is", $user_id, $message);
        if ($stmt->execute()) {
            echo json_encode(["success" => true]);
        } else {
            echo json_encode(["success" => false, "error" => $stmt->error]);
        }
        $stmt->close();
    } else {
        echo json_encode(["success" => false, "error" => "Pesan tidak boleh kosong"]);
    }
}
?>
