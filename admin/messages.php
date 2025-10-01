<?php
if (!isset($_SESSION['user_id']) || ($_SESSION['role'] ?? '') !== 'admin') {
    echo "<div class='alert alert-danger'>Access denied</div>";
    exit();
}
include 'db_connect.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Messages</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        html, body {
            height: 100%;
        }
        body {
            min-height: 100vh;
        }
        #chatBox {
            max-height: calc(100vh - 260px);
            min-height: 200px;
        }
        @media (min-width: 768px) {
            #chatBox {
                max-height: 420px;
                min-height: 420px;
            }
        }
    </style>
</head>
<body class="bg-gradient-to-br from-yellow-50 to-yellow-100 min-h-screen flex flex-col">
    <!-- Header -->
    <header class="w-full bg-yellow-500 shadow-lg py-4 px-4 md:px-8 mb-6">
        <div class="max-w-5xl mx-auto flex items-center">
            <h1 class="text-2xl font-bold text-white tracking-wide">Admin Chat Panel</h1>
        </div>
    </header>
    <!-- Main Chat Container -->
    <main class="flex-1 flex flex-col items-center w-full px-2 md:px-0">
        <div class="w-full max-w-5xl flex-1 flex flex-col md:flex-row bg-white rounded-2xl shadow-2xl overflow-hidden mx-auto"
             style="min-height: 70vh;">
            <!-- User List (Desktop) -->
            <aside class="hidden md:block md:w-1/3 border-r border-gray-200 bg-yellow-50">
                <div class="p-5 font-bold text-lg border-b bg-yellow-100 text-yellow-900">Users</div>
                <ul id="userList" class="divide-y divide-yellow-100"></ul>
            </aside>
            <!-- User Dropdown (Mobile) -->
            <aside class="block md:hidden w-full border-b border-gray-200 bg-yellow-50">
                <select id="userDropdown" class="w-full p-4 bg-yellow-50 border-none focus:ring-0 text-lg font-semibold text-yellow-900">
                    <option value="">Select a user</option>
                </select>
            </aside>
            <!-- Chat Area -->
            <section class="flex-1 flex flex-col min-h-0">
                <div class="p-4 md:p-5 border-b font-semibold text-gray-700 bg-yellow-50" id="chatHeader">Select a user</div>
                <div id="chatBox" class="flex-1 p-3 md:p-6 overflow-y-auto bg-white space-y-2 transition-all duration-200"></div>
                <div class="p-3 md:p-5 border-t bg-yellow-50 flex gap-2">
                    <input id="chatInput" type="text" class="flex-1 border border-yellow-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-yellow-400 transition" placeholder="Type a message..." disabled>
                    <button id="sendBtn" class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 md:px-6 py-2 rounded-lg font-semibold shadow" disabled>Send</button>
                </div>
            </section>
        </div>
    </main>
<script>
let currentUser = null;
let users = [];

// Load user list
function loadUsers() {
    $.getJSON("get_users.php", function(data) {
        users = data;
        // Desktop list
        let html = '';
        users.forEach(u => {
            html += `<li>
                <button class="w-full text-left px-5 py-3 hover:bg-yellow-200 user-btn transition rounded-none" data-id="${u.user_id}" data-name="${u.first_name} ${u.last_name}">
                    <span class="font-medium">${u.first_name} ${u.last_name}</span>
                    <span class="text-xs text-gray-400 ml-2">(${u.email})</span>
                </button>
            </li>`;
        });
        $("#userList").html(html);

        // Mobile dropdown
        let options = '<option value="">Select a user</option>';
        users.forEach(u => {
            options += `<option value="${u.user_id}">${u.first_name} ${u.last_name} (${u.email})</option>`;
        });
        $("#userDropdown").html(options);
    });
}

// Load chat for selected user
function loadChat(uid, name) {
    currentUser = uid;
    $("#chatHeader").text(name);
    $("#chatInput").prop("disabled", false);
    $("#sendBtn").prop("disabled", false);
    $(".user-btn").removeClass("bg-yellow-300 text-yellow-900 font-bold");
    $(`.user-btn[data-id='${uid}']`).addClass("bg-yellow-300 text-yellow-900 font-bold");
    $("#userDropdown").val(uid);
    fetchMessages();
}

// Fetch messages
function fetchMessages() {
    if (!currentUser) return;
    $.get("ajax.php?action=fetch_messages&user_id=" + currentUser, function(res) {
        if (res.success) {
            let html = '';
            if (res.messages.length === 0) {
                html = `<div class="text-gray-400 text-center mt-10">No messages yet.</div>`;
            } else {
                res.messages.forEach(m => {
                    let isAdmin = m.sender_id == 3;
                    let align = isAdmin ? "justify-end" : "justify-start";
                    let bubble = isAdmin
                        ? "bg-yellow-400 text-white"
                        : "bg-gray-200 text-gray-800";
                    let tail = isAdmin
                        ? "rounded-br-none"
                        : "rounded-bl-none";
                    html += `<div class="flex ${align}">
                        <div class="px-4 py-2 rounded-2xl ${tail} ${bubble} max-w-xs shadow">${escapeHtml(m.message)}</div>
                    </div>`;
                });
            }
            $("#chatBox").html(html);
            $("#chatBox").scrollTop($("#chatBox")[0].scrollHeight);
        }
    }, "json");
}

// Send message
$("#sendBtn").click(function() {
    let msg = $("#chatInput").val().trim();
    if (!msg || !currentUser) return;
    $("#sendBtn").prop("disabled", true);
    $.post("ajax.php?action=send_message", {message: msg, receiver_id: currentUser}, function(res) {
        if (res.success) {
            $("#chatInput").val("");
            fetchMessages();
        } else {
            alert(res.error || "Failed to send");
        }
        $("#sendBtn").prop("disabled", false);
    }, "json");
});
$("#chatInput").keypress(function(e){
    if(e.which === 13 && !e.shiftKey) {
        e.preventDefault();
        $("#sendBtn").click();
    }
});

// Desktop: Click user to load chat
$(document).on("click", ".user-btn", function() {
    let uid = $(this).data("id");
    let name = $(this).data("name");
    loadChat(uid, name);
});

// Mobile: Select user from dropdown
$("#userDropdown").on("change", function() {
    let uid = $(this).val();
    if (!uid) {
        $("#chatHeader").text("Select a user");
        $("#chatInput").prop("disabled", true);
        $("#sendBtn").prop("disabled", true);
        $("#chatBox").html('');
        currentUser = null;
        return;
    }
    let user = users.find(u => u.user_id == uid);
    if (user) {
        loadChat(uid, user.first_name + " " + user.last_name);
    }
});

// Escape HTML
function escapeHtml(text) {
    return $('<div>').text(text).html();
}

// Poll for new messages every 3s
setInterval(function() {
    if (currentUser) fetchMessages();
}, 3000);

// On page load
$(function() {
    loadUsers();
});
</script>
</body>
</html>