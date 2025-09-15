<?php include 'db_connect.php' ?>
<style>
    .messages-container {
        background: white;
        border-radius: 15px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.08);
        overflow: hidden;
        height: 600px;
        display: flex;
    }
    
    .conversations-sidebar {
        width: 300px;
        border-right: 1px solid #e9ecef;
        background: #f8f9fa;
        overflow-y: auto;
    }
    
    .conversation-item {
        padding: 15px;
        border-bottom: 1px solid #e9ecef;
        cursor: pointer;
        transition: background-color 0.2s ease;
    }
    
    .conversation-item:hover {
        background: #e9ecef;
    }
    
    .conversation-item.active {
        background: #007bff;
        color: white;
    }
    
    .conversation-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 5px;
    }
    
    .conversation-title {
        font-weight: 600;
        font-size: 0.9rem;
    }
    
    .conversation-time {
        font-size: 0.8rem;
        color: #666;
    }
    
    .conversation-item.active .conversation-time {
        color: rgba(255,255,255,0.8);
    }
    
    .conversation-preview {
        font-size: 0.8rem;
        color: #666;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    
    .conversation-item.active .conversation-preview {
        color: rgba(255,255,255,0.9);
    }
    
    .conversation-meta {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: 5px;
    }
    
    .conversation-listing {
        font-size: 0.7rem;
        color: #999;
        background: #e9ecef;
        padding: 2px 6px;
        border-radius: 10px;
    }
    
    .conversation-item.active .conversation-listing {
        background: rgba(255,255,255,0.2);
        color: rgba(255,255,255,0.9);
    }
    
    .unread-badge {
        background: #dc3545;
        color: white;
        border-radius: 50%;
        width: 20px;
        height: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.7rem;
        font-weight: 600;
    }
    
    .conversation-item.active .unread-badge {
        background: rgba(255,255,255,0.3);
    }
    
    .chat-area {
        flex: 1;
        display: flex;
        flex-direction: column;
    }
    
    .chat-header {
        padding: 20px;
        border-bottom: 1px solid #e9ecef;
        background: white;
    }
    
    .chat-title {
        font-size: 1.2rem;
        font-weight: 600;
        margin-bottom: 5px;
    }
    
    .chat-subtitle {
        color: #666;
        font-size: 0.9rem;
    }
    
    .messages-area {
        flex: 1;
        padding: 20px;
        overflow-y: auto;
        background: #f8f9fa;
    }
    
    .message {
        margin-bottom: 15px;
        display: flex;
    }
    
    .message.sent {
        justify-content: flex-end;
    }
    
    .message.received {
        justify-content: flex-start;
    }
    
    .message-bubble {
        max-width: 70%;
        padding: 12px 16px;
        border-radius: 18px;
        position: relative;
    }
    
    .message.sent .message-bubble {
        background: #007bff;
        color: white;
        border-bottom-right-radius: 4px;
    }
    
    .message.received .message-bubble {
        background: white;
        color: #333;
        border: 1px solid #e9ecef;
        border-bottom-left-radius: 4px;
    }
    
    .message-time {
        font-size: 0.7rem;
        color: #999;
        margin-top: 5px;
        text-align: right;
    }
    
    .message.received .message-time {
        text-align: left;
    }
    
    .message-input-area {
        padding: 20px;
        border-top: 1px solid #e9ecef;
        background: white;
    }
    
    .message-input-container {
        display: flex;
        gap: 10px;
        align-items: flex-end;
    }
    
    .message-input {
        flex: 1;
        border: 1px solid #e9ecef;
        border-radius: 20px;
        padding: 12px 16px;
        resize: none;
        min-height: 40px;
        max-height: 120px;
    }
    
    .message-input:focus {
        outline: none;
        border-color: #007bff;
    }
    
    .send-button {
        background: #007bff;
        color: white;
        border: none;
        border-radius: 50%;
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: background-color 0.2s ease;
    }
    
    .send-button:hover {
        background: #0056b3;
    }
    
    .send-button:disabled {
        background: #6c757d;
        cursor: not-allowed;
    }
    
    .empty-state {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        height: 100%;
        color: #666;
        text-align: center;
    }
    
    .empty-state i {
        font-size: 4rem;
        margin-bottom: 20px;
        color: #ddd;
    }
    
    .no-conversations {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        height: 100%;
        color: #666;
        text-align: center;
        padding: 40px;
    }
    
    .no-conversations i {
        font-size: 3rem;
        margin-bottom: 15px;
        color: #ddd;
    }
    
    .conversation-search {
        padding: 15px;
        border-bottom: 1px solid #e9ecef;
    }
    
    .conversation-search input {
        width: 100%;
        padding: 8px 12px;
        border: 1px solid #e9ecef;
        border-radius: 20px;
        font-size: 0.9rem;
    }
    
    .conversation-search input:focus {
        outline: none;
        border-color: #007bff;
    }
    
    .message-status {
        font-size: 0.7rem;
        margin-top: 2px;
    }
    
    .message.sent .message-status {
        text-align: right;
    }
    
    .message.received .message-status {
        text-align: left;
    }
    
    .status-delivered {
        color: #28a745;
    }
    
    .status-read {
        color: #007bff;
    }
    
    .status-sent {
        color: #6c757d;
    }
</style>

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <h2 class="mb-4">Messages</h2>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="messages-container">
                <!-- Conversations Sidebar -->
                <div class="conversations-sidebar">
                    <div class="conversation-search">
                        <input type="text" id="conversation-search" placeholder="Search conversations...">
                    </div>
                    
                    <div id="conversations-list">
                        <?php
                        $user_id = $_SESSION['login_id'];
                        $user_role = $_SESSION['login_role'];
                        
                        // Get conversations based on user role
                        if ($user_role == 'owner') {
                            $conversations_sql = "SELECT mt.*, 
                                                         sp.full_name as student_name,
                                                         l.title as listing_title,
                                                         (SELECT COUNT(*) FROM messages m WHERE m.thread_id = mt.id AND m.sender_id != ? AND m.read_at IS NULL) as unread_count,
                                                         (SELECT m.body FROM messages m WHERE m.thread_id = mt.id ORDER BY m.created_at DESC LIMIT 1) as last_message,
                                                         (SELECT m.created_at FROM messages m WHERE m.thread_id = mt.id ORDER BY m.created_at DESC LIMIT 1) as last_message_time
                                                  FROM message_threads mt
                                                  LEFT JOIN student_profiles sp ON mt.student_id = sp.user_id
                                                  LEFT JOIN listings l ON mt.listing_id = l.id
                                                  WHERE mt.owner_id = ?
                                                  ORDER BY last_message_time DESC";
                        } else if ($user_role == 'student') {
                            $conversations_sql = "SELECT mt.*, 
                                                         op.full_name as owner_name,
                                                         l.title as listing_title,
                                                         (SELECT COUNT(*) FROM messages m WHERE m.thread_id = mt.id AND m.sender_id != ? AND m.read_at IS NULL) as unread_count,
                                                         (SELECT m.body FROM messages m WHERE m.thread_id = mt.id ORDER BY m.created_at DESC LIMIT 1) as last_message,
                                                         (SELECT m.created_at FROM messages m WHERE m.thread_id = mt.id ORDER BY m.created_at DESC LIMIT 1) as last_message_time
                                                  FROM message_threads mt
                                                  LEFT JOIN owner_profiles op ON mt.owner_id = op.user_id
                                                  LEFT JOIN listings l ON mt.listing_id = l.id
                                                  WHERE mt.student_id = ?
                                                  ORDER BY last_message_time DESC";
                        }
                        
                        $conversations_stmt = $conn->prepare($conversations_sql);
                        $conversations_stmt->bind_param("ii", $user_id, $user_id);
                        $conversations_stmt->execute();
                        $conversations_result = $conversations_stmt->get_result();
                        
                        if ($conversations_result->num_rows > 0):
                            while ($conversation = $conversations_result->fetch_assoc()):
                                $other_party_name = $user_role == 'owner' ? $conversation['student_name'] : $conversation['owner_name'];
                                $last_message_time = $conversation['last_message_time'] ? date('M d, H:i', strtotime($conversation['last_message_time'])) : 'No messages';
                        ?>
                        <div class="conversation-item" data-thread-id="<?php echo $conversation['id'] ?>" onclick="loadConversation(<?php echo $conversation['id'] ?>)">
                            <div class="conversation-header">
                                <div class="conversation-title"><?php echo htmlspecialchars($other_party_name) ?></div>
                                <div class="conversation-time"><?php echo $last_message_time ?></div>
                            </div>
                            <div class="conversation-preview">
                                <?php echo htmlspecialchars($conversation['last_message'] ?: 'No messages yet') ?>
                            </div>
                            <div class="conversation-meta">
                                <div class="conversation-listing"><?php echo htmlspecialchars($conversation['listing_title']) ?></div>
                                <?php if ($conversation['unread_count'] > 0): ?>
                                    <div class="unread-badge"><?php echo $conversation['unread_count'] ?></div>
                                <?php endif; ?>
                            </div>
                        </div>
                        <?php 
                            endwhile;
                        else:
                        ?>
                        <div class="no-conversations">
                            <i class="fa fa-comments"></i>
                            <h5>No conversations yet</h5>
                            <p>Start a conversation by viewing a listing or booking a room.</p>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
                
                <!-- Chat Area -->
                <div class="chat-area">
                    <div id="chat-content">
                        <div class="empty-state">
                            <i class="fa fa-comments"></i>
                            <h4>Select a conversation</h4>
                            <p>Choose a conversation from the sidebar to start messaging.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Message Input Template -->
<div id="message-input-template" style="display: none;">
    <div class="message-input-area">
        <div class="message-input-container">
            <textarea class="message-input" id="message-text" placeholder="Type your message..." rows="1"></textarea>
            <button class="send-button" id="send-message" onclick="sendMessage()">
                <i class="fa fa-paper-plane"></i>
            </button>
        </div>
    </div>
</div>

<script>
let currentThreadId = null;
let messageCheckInterval = null;

$(document).ready(function(){
    // Auto-resize textarea
    $('#message-text').on('input', function() {
        this.style.height = 'auto';
        this.style.height = (this.scrollHeight) + 'px';
    });
    
    // Send message on Enter (but allow Shift+Enter for new line)
    $('#message-text').on('keypress', function(e) {
        if (e.which === 13 && !e.shiftKey) {
            e.preventDefault();
            sendMessage();
        }
    });
    
    // Search conversations
    $('#conversation-search').on('keyup', function() {
        const searchTerm = $(this).val().toLowerCase();
        $('.conversation-item').each(function() {
            const title = $(this).find('.conversation-title').text().toLowerCase();
            const preview = $(this).find('.conversation-preview').text().toLowerCase();
            const listing = $(this).find('.conversation-listing').text().toLowerCase();
            
            if (title.includes(searchTerm) || preview.includes(searchTerm) || listing.includes(searchTerm)) {
                $(this).show();
            } else {
                $(this).hide();
            }
        });
    });
});

function loadConversation(threadId) {
    currentThreadId = threadId;
    
    // Update active conversation
    $('.conversation-item').removeClass('active');
    $(`.conversation-item[data-thread-id="${threadId}"]`).addClass('active');
    
    // Load conversation details
    $.ajax({
        url: 'ajax.php?action=get_conversation_details',
        method: 'POST',
        data: {thread_id: threadId},
        success: function(response) {
            try {
                var conversation = JSON.parse(response);
                displayConversation(conversation);
                
                // Start checking for new messages
                if (messageCheckInterval) {
                    clearInterval(messageCheckInterval);
                }
                messageCheckInterval = setInterval(checkNewMessages, 3000);
                
            } catch(e) {
                console.error('Failed to parse conversation data:', e);
                alert('Failed to load conversation');
            }
        },
        error: function() {
            alert('Failed to load conversation');
        }
    });
}

function displayConversation(conversation) {
    const userRole = '<?php echo $_SESSION['login_role'] ?>';
    const otherPartyName = userRole === 'owner' ? conversation.student_name : conversation.owner_name;
    const listingTitle = conversation.listing_title;
    
    const chatContent = `
        <div class="chat-header">
            <div class="chat-title">${otherPartyName}</div>
            <div class="chat-subtitle">About: ${listingTitle}</div>
        </div>
        <div class="messages-area" id="messages-area">
            ${renderMessages(conversation.messages)}
        </div>
        ${$('#message-input-template').html()}
    `;
    
    $('#chat-content').html(chatContent);
    
    // Scroll to bottom
    const messagesArea = document.getElementById('messages-area');
    messagesArea.scrollTop = messagesArea.scrollHeight;
    
    // Mark messages as read
    markMessagesAsRead();
}

function renderMessages(messages) {
    if (!messages || messages.length === 0) {
        return '<div class="text-center text-muted py-4">No messages yet. Start the conversation!</div>';
    }
    
    const userRole = '<?php echo $_SESSION['login_role'] ?>';
    const userId = <?php echo $_SESSION['login_id'] ?>;
    
    return messages.map(message => {
        const isSent = message.sender_id == userId;
        const messageTime = new Date(message.created_at).toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'});
        const statusClass = message.read_at ? 'status-read' : (message.delivered_at ? 'status-delivered' : 'status-sent');
        const statusText = message.read_at ? 'Read' : (message.delivered_at ? 'Delivered' : 'Sent');
        
        return `
            <div class="message ${isSent ? 'sent' : 'received'}">
                <div class="message-bubble">
                    ${message.body}
                    <div class="message-time">${messageTime}</div>
                    ${isSent ? `<div class="message-status ${statusClass}">${statusText}</div>` : ''}
                </div>
            </div>
        `;
    }).join('');
}

function sendMessage() {
    const messageText = $('#message-text').val().trim();
    
    if (!messageText || !currentThreadId) {
        return;
    }
    
    // Disable send button
    $('#send-message').prop('disabled', true);
    
    $.ajax({
        url: 'ajax.php?action=send_message',
        method: 'POST',
        data: {
            thread_id: currentThreadId,
            message: messageText
        },
        success: function(response) {
            if (response == 1) {
                // Clear input
                $('#message-text').val('');
                $('#message-text').css('height', 'auto');
                
                // Reload conversation
                loadConversation(currentThreadId);
            } else {
                alert('Failed to send message');
            }
        },
        error: function() {
            alert('Failed to send message');
        },
        complete: function() {
            $('#send-message').prop('disabled', false);
        }
    });
}

function markMessagesAsRead() {
    if (!currentThreadId) return;
    
    $.ajax({
        url: 'ajax.php?action=mark_messages_read',
        method: 'POST',
        data: {thread_id: currentThreadId}
    });
}

function checkNewMessages() {
    if (!currentThreadId) return;
    
    $.ajax({
        url: 'ajax.php?action=get_conversation_details',
        method: 'POST',
        data: {thread_id: currentThreadId},
        success: function(response) {
            try {
                var conversation = JSON.parse(response);
                const messagesArea = document.getElementById('messages-area');
                if (messagesArea) {
                    const currentScrollTop = messagesArea.scrollTop;
                    const isAtBottom = messagesArea.scrollTop + messagesArea.clientHeight >= messagesArea.scrollHeight - 10;
                    
                    messagesArea.innerHTML = renderMessages(conversation.messages);
                    
                    if (isAtBottom) {
                        messagesArea.scrollTop = messagesArea.scrollHeight;
                    } else {
                        messagesArea.scrollTop = currentScrollTop;
                    }
                }
            } catch(e) {
                console.error('Failed to check new messages:', e);
            }
        }
    });
}

// Clean up interval when page unloads
$(window).on('beforeunload', function() {
    if (messageCheckInterval) {
        clearInterval(messageCheckInterval);
    }
});
</script>
