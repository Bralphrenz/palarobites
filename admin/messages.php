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
    <!-- Tailwind CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css">
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <!-- Animate.css for subtle animations -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
    <!-- SweetAlert2 for beautiful alerts -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.12/dist/sweetalert2.min.css">
    <!-- Tippy.js for tooltips -->
    <link rel="stylesheet" href="https://unpkg.com/tippy.js@6/themes/light.css"/>
    <!-- Custom Scrollbar -->
    <link rel="stylesheet" href="https://unpkg.com/simplebar@latest/dist/simplebar.min.css"/>
    <!-- Custom Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&family=Playfair+Display:wght@700&display=swap" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.12/dist/sweetalert2.all.min.js"></script>
    <script src="https://unpkg.com/tippy.js@6"></script>
    <script src="https://unpkg.com/simplebar@latest/dist/simplebar.min.js"></script>
    <style>
        html, body {
            height: 100%;
            font-family: 'Montserrat', Arial, sans-serif;
        }
        body {
            min-height: 100vh;
            background: linear-gradient(135deg, #fffbe6 0%, #f7c873 100%);
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
        .restaurant-header {
            font-family: 'Playfair Display', serif;
            letter-spacing: 2px;
        }
        .glass {
            background: rgba(255,255,255,0.85);
            backdrop-filter: blur(8px);
        }
        .sidebar-gradient {
            background: linear-gradient(180deg, #fffbe6 0%, #f7c873 100%);
        }
        .chat-bubble-admin {
            background: linear-gradient(90deg, #fbbf24 0%, #f59e42 100%);
            color: #fff;
            box-shadow: 0 4px 24px 0 rgba(251,191,36,0.15);
        }
        .chat-bubble-user {
            background: #fffbe6;
            color: #a16207;
            box-shadow: 0 2px 12px 0 rgba(251,191,36,0.08);
        }
        .user-active {
            background: #fbbf24 !important;
            color: #fff !important;
            font-weight: bold;
        }
        /* Custom scrollbar for chat */
        [data-simplebar] .simplebar-scrollbar:before {
            background: #fbbf24;
        }
    </style>
</head>
<body class="min-h-screen flex flex-col md:ml-64"> <!-- Add md:ml-64 for sidebar margin -->
    <!-- Header -->
    <header class="w-full bg-gradient-to-r from-yellow-600 via-yellow-500 to-yellow-400 shadow-xl py-8 px-4 md:px-8 mb-8 animate__animated animate__fadeInDown">
        <div class="max-w-5xl mx-auto flex items-center">
            <i class="fa-solid fa-utensils text-white text-4xl mr-4"></i>
            <h1 class="text-4xl md:text-5xl font-extrabold text-white tracking-wider drop-shadow restaurant-header">Le Gourmet Admin Chat</h1>
        </div>
    </header>
    <!-- Main Chat Container -->
    <main class="flex-1 flex flex-col items-center w-full px-2 md:px-0">
        <div class="w-full max-w-5xl flex-1 flex flex-col md:flex-row glass rounded-3xl shadow-2xl overflow-hidden mx-auto border border-yellow-200 animate__animated animate__fadeInUp"
             style="min-height: 70vh;">
            <!-- User List (Desktop) -->
            <aside class="hidden md:block md:w-1/3 border-r border-yellow-200 sidebar-gradient">
                <div class="p-6 font-bold text-2xl border-b bg-yellow-100 text-yellow-900 flex items-center gap-3">
                    <i class="fa-solid fa-users text-yellow-500"></i>
                    Guests
                </div>
                <div class="overflow-y-auto" style="max-height:60vh;">
                    <ul id="userList" class="divide-y divide-yellow-100" data-simplebar></ul>
                </div>
            </aside>
            <!-- User Dropdown (Mobile) -->
            <aside class="block md:hidden w-full border-b border-yellow-100 bg-yellow-50">
                <select id="userDropdown" class="w-full p-4 bg-yellow-50 border-none focus:ring-0 text-lg font-semibold text-yellow-900 rounded-t-2xl">
                    <option value="">Select a guest</option>
                </select>
            </aside>
            <!-- Chat Area -->
            <section class="flex-1 flex flex-col min-h-0 bg-gradient-to-b from-white to-yellow-50">
                <div class="p-6 border-b font-semibold text-yellow-800 bg-yellow-50 flex items-center gap-3" id="chatHeader">
                    <i class="fa-solid fa-comments text-yellow-400"></i>
                    <span>Select a guest</span>
                </div>
                <div id="chatBox" class="flex-1 p-4 md:p-8 overflow-y-auto bg-white space-y-4 transition-all duration-200 rounded-b-2xl" data-simplebar></div>
                <div class="p-4 md:p-6 border-t bg-yellow-50 flex gap-2 items-center">
                    <input id="chatInput" type="text" class="flex-1 border border-yellow-300 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-yellow-400 transition bg-white shadow-sm text-lg" placeholder="Type a message..." disabled>
                    <button id="sendBtn" class="bg-yellow-500 hover:bg-yellow-600 text-white px-6 py-3 rounded-xl font-semibold shadow-lg transition disabled:opacity-50 text-lg flex items-center gap-2" disabled>
                        <i class="fa-solid fa-paper-plane"></i>
                        Send
                    </button>
                </div>
            </section>
        </div>
    </main>
    <!-- Toast Container -->
    <div id="toast-container" class="fixed top-6 right-6 z-50"></div>
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
                <button class="w-full text-left px-6 py-5 hover:bg-yellow-200 user-btn transition rounded-none flex items-center gap-3 text-lg" data-id="${u.user_id}" data-name="${u.first_name} ${u.last_name}" data-tippy-content="${u.email}">
                    <span class="inline-block w-10 h-10 rounded-full bg-yellow-200 text-yellow-700 flex items-center justify-center font-bold mr-2 text-xl shadow">${u.first_name[0] ?? ''}${u.last_name[0] ?? ''}</span>
                    <span class="font-semibold">${u.first_name} ${u.last_name}</span>
                    <span class="text-xs text-gray-400 ml-2">(${u.email})</span>
                </button>
            </li>`;
        });
        $("#userList").html(html);
        tippy('.user-btn', { theme: 'light', placement: 'right' });

        // Mobile dropdown
        let options = '<option value="">Select a guest</option>';
        users.forEach(u => {
            options += `<option value="${u.user_id}">${u.first_name} ${u.last_name} (${u.email})</option>`;
        });
        $("#userDropdown").html(options);
    });
}

// Load chat for selected user
function loadChat(uid, name) {
    currentUser = uid;
    $("#chatHeader").html(`<i class="fa-solid fa-comments text-yellow-400"></i> <span>${name}</span>`);
    $("#chatInput").prop("disabled", false);
    $("#sendBtn").prop("disabled", false);
    $(".user-btn").removeClass("user-active");
    $(`.user-btn[data-id='${uid}']`).addClass("user-active");
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
                        ? "chat-bubble-admin"
                        : "chat-bubble-user";
                    let tail = isAdmin
                        ? "rounded-br-none"
                        : "rounded-bl-none";
                    let icon = isAdmin
                        ? `<i class="fa-solid fa-user-tie text-yellow-700 mr-2"></i>`
                        : `<i class="fa-solid fa-user text-yellow-400 mr-2"></i>`;
                    html += `<div class="flex ${align}">
                        <div class="px-6 py-4 rounded-2xl ${tail} ${bubble} max-w-xs md:max-w-md break-words flex items-center shadow">
                            ${icon}<span>${escapeHtml(m.message)}</span>
                        </div>
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
            showToast('Message sent!', 'success');
        } else {
            showToast(res.error || "Failed to send", 'error');
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
        $("#chatHeader").html(`<i class="fa-solid fa-comments text-yellow-400"></i> <span>Select a guest</span>`);
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

// Toast notification
function showToast(message, type = 'success') {
    Swal.fire({
        toast: true,
        position: 'top-end',
        icon: type,
        title: message,
        showConfirmButton: false,
        timer: 2000,
        timerProgressBar: true,
        background: '#fffbe6',
        color: '#a16207',
        customClass: {
            popup: 'shadow-lg'
        }
    });
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