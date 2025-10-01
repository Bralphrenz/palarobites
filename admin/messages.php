<?php

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    echo "<div class='alert alert-danger'>Access denied</div>";
    exit();
}
?>

<!-- Add a wrapper to push content right of the sidebar and add margin -->
<div class="md:ml-64 ml-0">
  <div class="p-8 bg-gradient-to-br from-gray-50 to-yellow-50 min-h-screen">
    <div class="max-w-6xl mx-auto bg-white rounded-2xl shadow-2xl overflow-hidden flex flex-col md:flex-row" style="min-height: 700px;">
      <!-- User List -->
      <div class="md:w-1/3 w-full border-r border-gray-200 bg-gradient-to-b from-gray-100 to-yellow-50 flex flex-col">
        <div class="p-6 border-b border-gray-200 bg-white">
          <h3 class="font-semibold text-xl text-gray-800" style="font-family: 'Montserrat', sans-serif;">Users</h3>
        </div>
        <ul id="userList" class="flex-1 p-4 space-y-2 overflow-y-auto" style="max-height: 600px;">
          <?php
          $users = $conn->query("SELECT user_id, first_name, last_name FROM user_info WHERE role='user'");
          while($u = $users->fetch_assoc()):
          ?>
          <li>
            <button class="w-full text-left px-4 py-3 rounded-xl transition-all duration-200 hover:bg-yellow-50 hover:shadow-lg border border-transparent hover:border-yellow-300 group"
                    onclick="loadChat(<?php echo $u['user_id'] ?>)"
                    data-user-id="<?php echo $u['user_id'] ?>">
              <div class="flex items-center gap-3">
                <div class="w-12 h-12 rounded-full bg-gradient-to-br from-yellow-400 to-yellow-600 flex items-center justify-center text-white font-bold text-lg shadow">
                  <?php 
                    $initials = strtoupper(substr($u['first_name'], 0, 1) . substr($u['last_name'], 0, 1));
                    echo $initials;
                  ?>
                </div>
                <div class="flex-1">
                  <div class="font-medium text-gray-800 group-hover:text-yellow-700" style="font-family: 'Montserrat', sans-serif;">
                    <?php echo htmlspecialchars($u['first_name'].' '.$u['last_name']); ?>
                  </div>
                  <div class="text-xs text-gray-500">Start chatting</div>
                </div>
              </div>
            </button>
          </li>
          <?php endwhile; ?>
        </ul>
      </div>

      <!-- Chat Window -->
      <div class="md:w-2/3 w-full flex flex-col bg-white">
        <!-- Chat Header -->
        <div class="p-6 border-b border-gray-200 bg-gradient-to-r from-yellow-50 to-white flex items-center gap-4">
          <div id="chatAvatar" class="w-10 h-10 rounded-full bg-gradient-to-br from-gray-700 to-gray-900 flex items-center justify-center text-white font-semibold text-sm hidden"></div>
          <h3 class="font-semibold text-xl text-gray-800" style="font-family: 'Montserrat', sans-serif;" id="chatHeader">Chat Window</h3>
        </div>
        
        <!-- Chat Messages -->
        <div id="chatBox" class="flex-1 p-8 overflow-y-auto bg-gradient-to-b from-white to-yellow-50" style="min-height: 450px;">
          <div class="flex flex-col items-center justify-center h-full text-gray-400">
            <svg class="w-16 h-16 mb-4 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
            </svg>
            <p class="text-sm" style="font-family: 'Montserrat', sans-serif;">Select a user to start conversation</p>
          </div>
        </div>
        
        <!-- Message Input -->
        <div class="p-6 border-t border-gray-200 bg-white">
          <div class="flex gap-3">
            <input type="text" 
                   id="chatInput" 
                   class="flex-1 border border-gray-300 rounded-xl px-5 py-3 text-base focus:outline-none focus:ring-2 focus:ring-yellow-400 focus:border-transparent transition-all" 
                   placeholder="Type a message..."
                   style="font-family: 'Montserrat', sans-serif;">
            <button id="sendMessage" 
                    class="bg-gradient-to-r from-yellow-500 to-yellow-700 text-white px-6 py-3 rounded-xl hover:from-yellow-600 hover:to-yellow-800 transition-all duration-200 font-medium shadow-lg hover:shadow-xl flex items-center gap-2"
                    style="font-family: 'Montserrat', sans-serif;">
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
              </svg>
              Send
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<style>
  #userList::-webkit-scrollbar,
  #chatBox::-webkit-scrollbar {
    width: 6px;
  }
  #userList::-webkit-scrollbar-thumb,
  #chatBox::-webkit-scrollbar-thumb {
    background: #d4af37;
    border-radius: 3px;
  }
  #userList::-webkit-scrollbar-thumb:hover,
  #chatBox::-webkit-scrollbar-thumb:hover {
    background: #b8941f;
  }
  button[data-user-id].active {
    background: #fffbe6;
    border-color: #d4af37 !important;
    box-shadow: 0 2px 12px rgba(212, 175, 55, 0.15);
  }
  @keyframes fade-in {
    from { opacity: 0; transform: translateY(10px);}
    to { opacity: 1; transform: translateY(0);}
  }
  .animate-fade-in { animation: fade-in 0.3s ease-out; }
  /* Add margin to the top for mobile screens */
  @media (max-width: 767px) {
    .md\:ml-64 {
      margin-left: 0 !important;
    }
    .p-8 {
      padding: 1.5rem !important;
    }
  }
</style>

<script>
  let currentUser = null;

  function loadChat(uid) {
    currentUser = uid;
    document.querySelectorAll('button[data-user-id]').forEach(btn => btn.classList.remove('active'));
    const btn = document.querySelector(`button[data-user-id="${uid}"]`);
    btn.classList.add('active');
    // Show avatar in header
    const initials = btn.querySelector('div').textContent.trim();
    document.getElementById('chatAvatar').textContent = initials;
    document.getElementById('chatAvatar').classList.remove('hidden');
    const userName = btn.querySelector('.font-medium').textContent.trim();
    $("#chatHeader").text("Chat with " + userName);

    // Use ajax.php for fetching messages
    $.get("ajax.php?action=fetch_messages&user_id="+uid, function(res){
      console.log(res); // <-- Add this line
      if(res.success){
        $("#chatBox").html("");
        res.messages.forEach(m=>{
          let isAdmin = (m.sender_id == <?php echo $_SESSION['user_id'] ?? 0; ?>);
          let alignment = isAdmin ? "justify-end" : "justify-start";
          let bgColor = isAdmin 
            ? "bg-gradient-to-r from-yellow-500 to-yellow-700 text-white"
            : "bg-white border border-yellow-200 text-gray-800";
          let messageHtml = `
            <div class="flex ${alignment} mb-4 animate-fade-in">
              <div class="max-w-xs lg:max-w-md">
                <div class="${bgColor} px-5 py-3 rounded-2xl shadow">
                  <p class="text-base" style="font-family: 'Montserrat', sans-serif;">${m.message}</p>
                </div>
              </div>
            </div>
          `;
          $("#chatBox").append(messageHtml);
        });
        $("#chatBox").scrollTop($("#chatBox")[0].scrollHeight);
      }
    },"json");
  }

  $("#sendMessage").click(function(){
    if(!currentUser) return alert("Select a user first");
    let msg = $("#chatInput").val().trim();
    if(msg==="") return;
    // Use ajax.php for sending messages
    $.post("ajax.php?action=send_message", {message:msg, receiver_id:currentUser},function(res){
      if(res.success){
        let messageHtml = `
          <div class="flex justify-end mb-4 animate-fade-in">
            <div class="max-w-xs lg:max-w-md">
              <div class="bg-gradient-to-r from-yellow-500 to-yellow-700 text-white px-5 py-3 rounded-2xl shadow">
                <p class="text-base" style="font-family: 'Montserrat', sans-serif;">${msg}</p>
              </div>
            </div>
          </div>
        `;
        $("#chatBox").append(messageHtml);
        $("#chatInput").val("");
        $("#chatBox").scrollTop($("#chatBox")[0].scrollHeight);
      }
    },"json");
  });
  
  $("#chatInput").keypress(function(e){
    if(e.which === 13) {
      $("#sendMessage").click();
    }
  });

  // Optional: auto-refresh messages every 5 seconds if a chat is open
  setInterval(function(){
    if(currentUser) loadChat(currentUser);
  }, 5000);
</script>