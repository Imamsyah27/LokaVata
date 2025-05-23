<?php
session_start();
include('config.php');

header('Content-Type: application/json');

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["user_id"]) && isset($_POST["reply"])) {
    if (!isset($_SESSION["user_id"]) || $_SESSION["role"] !== "admin") {
        echo json_encode(["success" => false, "error" => "Unauthorized"]);
        exit();
    }

    $user_id = $_POST["user_id"];
    $reply = trim($_POST["reply"]);

    if (!empty($reply)) {
        // Simpan pesan admin ke database
        $stmt = $conn->prepare("INSERT INTO messages (user_id, message, sender_role) VALUES (?, ?, 'admin')");
        $stmt->bind_param("is", $user_id, $reply);
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
