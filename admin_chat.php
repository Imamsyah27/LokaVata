<?php
session_start();
require('config.php');
require('Logout.php');

if (!isset($_SESSION["user_id"]) || $_SESSION["role"] !== "admin") {
    header("Location: Login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Live Chat</title>
    <link rel="stylesheet" href="admin_chat.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<header>
    <div class="logo">
      <img src="Images\LokaVata.png" alt="LokaVata Logo">
    </div>
    <nav>
      <a href="Home.php">Home</a>
      <a href="Maps.html">Maps</a>
      <a href="Faq.php"class="active">FAQ</a>
      <a href="About.php">About Us</a>
      <a href="contactus.php">Contact Us</a>
      <a href="?logout=true" style="color: red; font-weight: bold;">Log Out</a>
    </nav>
</header>
<body>
    <div class="admin-chat-container">
        <!-- Sidebar Daftar Chat -->
        <div id="user-list">
            <h3>Chat Pengguna</h3>
            <ul id="users"></ul>
        </div>

        <!-- Chatbox -->
        <div id="chat-container">
            <div id="chat-header">
                <h3 id="chat-title">Pilih Chat</h3>
            </div>
            <div id="chat-box"></div>

            <!-- Form Input Pesan -->
            <form id="chat-form">
                <input type="hidden" id="selected-user-id">
                <input type="text" id="message-input" placeholder="Tulis pesan..." required>
                <button type="submit">📩</button>
            </form>
        </div>
    </div>

    <script>
    $(document).ready(function() {
        function loadUsers() {
            $.ajax({
                url: "get_users.php",
                type: "GET",
                dataType: "json",
                success: function(response) {
                    let userList = $("#users");
                    userList.html("");
                    response.forEach(user => {
                        userList.append(`<li data-user="${user.id}" class="user-item">${user.name}</li>`);
                    });
                }
            });
        }

        function loadMessages(userId) {
            $.ajax({
                url: "get_history.php",
                type: "GET",
                data: { user_id: userId },
                dataType: "json",
                success: function(response) {
                    let chatBox = $("#chat-box");
                    chatBox.html("");

                    response.forEach(msg => {
                        let chatClass = msg.sender === "admin" ? "admin-message" : "user-message";
                        let chatBubble = `<div class="chat-message ${chatClass}">
                            <p>${msg.message}</p>
                            <span class="chat-time">${msg.timestamp}</span>
                        </div>`;

                        chatBox.append(chatBubble);
                    });

                    chatBox.scrollTop(chatBox.prop("scrollHeight"));
                }
            });
        }

        // Saat admin mengklik user di daftar, ubah isi chat
        $(document).on("click", ".user-item", function() {
            let userId = $(this).data("user");
            $("#selected-user-id").val(userId);
            $("#chat-title").text(`Chat dengan ${$(this).text()}`);
            loadMessages(userId);
        });

        $("#chat-form").submit(function(event) {
            event.preventDefault();
            let userId = $("#selected-user-id").val();
            let messageText = $("#message-input").val();

            $.ajax({
                url: "send_reply.php",
                type: "POST",
                data: { user_id: userId, reply: messageText },
                dataType: "json",
                success: function(response) {
                    if (response.success) {
                        $("#message-input").val("");
                        loadMessages(userId); // Pastikan chat di-refresh setelah admin mengirim pesan
                    } else {
                        alert("Gagal mengirim pesan: " + response.error);
                    }
                }
            });
        });

        loadUsers();
        setInterval(loadUsers, 5000);
    });
    </script>
    <script>
        $(document).ready(function() {
    function loadUsers() {
        $.ajax({
            url: "get_users.php",
            type: "GET",
            dataType: "json",
            success: function(response) {
                let userList = $("#users");
                userList.html("");
                response.forEach(user => {
                    let firstLetter = user.name.charAt(0).toUpperCase();
                    let newMessageBadge = user.new_messages ? `<span class="new-message">${user.new_messages}</span>` : "";
                    
                    userList.append(`
                        <li data-user="${user.id}" class="user-item">
                            <div class="user-avatar">${firstLetter}</div>
                            ${user.name} 
                            ${newMessageBadge}
                        </li>
                    `);
                });
            }
        });
    }

    // Load daftar user setiap 5 detik
    setInterval(loadUsers, 5000);
    loadUsers();
});

    </script>
</body>
</html>
