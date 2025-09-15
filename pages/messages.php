<?php include 'config/db_connect.php' ?>
<div class="container-fluid">
	<div class="col-lg-12">
		<div class="card">
			<div class="card-header">
				<large><b>Messages</b></large>
			</div>
			<div class="card-body">
				<div class="row">
					<div class="col-md-4">
						<div class="card">
							<div class="card-header">
								<h5>Conversations</h5>
							</div>
							<div class="card-body" style="height: 400px; overflow-y: auto;">
								<div class="list-group" id="conversations">
									<?php
									$myId = (int)$_SESSION['login_id'];
									$openUserId = isset($_GET['open_user']) ? (int)$_GET['open_user'] : 0;
									
									// Get conversations from message_threads table
									$conversations = $conn->query("SELECT mt.*,
									        COALESCE(sp.full_name, op.full_name, u.email) AS other_user_name,
									        u.role AS other_user_role,
									        u.id as other_user_id,
									        l.title as listing_title,
									        mt.last_message_at
									    FROM message_threads mt
									    INNER JOIN users u ON (CASE WHEN mt.student_id = $myId THEN mt.owner_id ELSE mt.student_id END) = u.id
									    LEFT JOIN student_profiles sp ON u.id = sp.user_id AND u.role = 'student'
									    LEFT JOIN owner_profiles op ON u.id = op.user_id AND u.role = 'owner'
									    LEFT JOIN listings l ON mt.listing_id = l.id
									    WHERE mt.student_id = $myId OR mt.owner_id = $myId
									    ORDER BY mt.last_message_at DESC, mt.created_at DESC");
									    
									while($row = $conversations->fetch_assoc()):
									?>
									<a href="#" class="list-group-item list-group-item-action conversation-item d-flex align-items-center" 
									   data-thread-id="<?php echo $row['id'] ?>" data-user-id="<?php echo $row['other_user_id'] ?>">
										<div class="avatar rounded-circle bg-secondary text-white d-inline-flex align-items-center justify-content-center mr-2" style="width:34px;height:34px;">
											<span><?php echo strtoupper(substr($row['other_user_name'],0,1)) ?></span>
										</div>
										<div class="flex-grow-1">
											<div class="d-flex w-100 justify-content-between">
												<strong><?php echo $row['other_user_name'] ?></strong>
												<small class="text-muted"><?php echo $row['last_message_at'] ? date('M d', strtotime($row['last_message_at'])) : '' ?></small>
											</div>
											<small class="text-muted"><?php echo ucfirst($row['other_user_role']) ?></small>
											<?php if ($row['listing_title']): ?>
											<small class="text-muted d-block">Re: <?php echo $row['listing_title'] ?></small>
											<?php endif; ?>
										</div>
									</a>
									<?php endwhile; ?>
									<?php
									// Fallback: if no conversations found but open_user is provided, show that user as a starter item
									if (isset($conversations) && $conversations->num_rows == 0 && $openUserId > 0) {
									    $uRes = $conn->query("SELECT u.id,
									        COALESCE(sp.full_name, op.full_name, u.email) AS name,
									        u.role
									     FROM users u
									     LEFT JOIN student_profiles sp ON u.id = sp.user_id
									     LEFT JOIN owner_profiles op ON u.id = op.user_id
									     WHERE u.id = $openUserId");
									    if ($uRes && $uRes->num_rows > 0) {
									        $u = $uRes->fetch_assoc();
									        ?>
									        <a href="#" class="list-group-item list-group-item-action conversation-item d-flex align-items-center" data-user-id="<?php echo $u['id'] ?>">
									            <div class="avatar rounded-circle bg-secondary text-white d-inline-flex align-items-center justify-content-center mr-2" style="width:34px;height:34px;">
									                <span><?php echo strtoupper(substr($u['name'],0,1)) ?></span>
									            </div>
									            <div class="flex-grow-1">
									                <div class="d-flex w-100 justify-content-between">
									                    <strong><?php echo $u['name'] ?></strong>
									                    <small class="text-muted"></small>
									                </div>
									                <small class="text-muted"><?php echo ucfirst($u['role']) ?></small>
									            </div>
									        </a>
									        <?php
									    } else {
									        echo '<div class="p-3 text-muted">No conversations yet. Click the Message button on a listing to start chatting.</div>';
									    }
									}
									
									// If no conversations at all, show helpful message
									if (isset($conversations) && $conversations->num_rows == 0 && $openUserId == 0) {
									    echo '<div class="p-3 text-muted text-center">
									        <i class="fa fa-comments fa-3x mb-3 text-secondary"></i><br>
									        No conversations yet.<br>
									        <small>Visit a property listing and click "Message Owner" to start chatting!</small>
									    </div>';
									}
									?>
								</div>
							</div>
						</div>
					</div>
					<div class="col-md-8">
						<div class="card">
							<div class="card-header d-flex align-items-center">
								<div class="mr-2 avatar rounded-circle bg-primary text-white d-inline-flex align-items-center justify-content-center" style="width:36px;height:36px;">
									<span id="chat-initial">?</span>
								</div>
								<h5 id="chat-title" class="mb-0">Select a conversation</h5>
							</div>
							<div class="card-body" style="height: 360px; overflow-y: auto; background:#f8fafc;" id="messages-container">
								<div class="text-center text-muted mt-5">
									<p>Select a conversation to start messaging</p>
								</div>
							</div>
							<div class="card-footer" id="message-form" style="display: none;">
								<form id="send-message-form">
									<div class="input-group">
										<input type="hidden" id="thread_id" name="thread_id">
										<input type="hidden" id="receiver_id" name="receiver_id">
										<input type="text" class="form-control" id="message_text" name="message_text" placeholder="Type your message and press Enter">
										<div class="input-group-append">
											<button class="btn btn-primary" type="submit"><i class="fa fa-paper-plane"></i></button>
										</div>
									</div>
								</form>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<script>
    // Auto-open via open_user if present in URL
    (function(){
        var params = new URLSearchParams(window.location.search);
        var openUser = params.get('open_user');
        if (openUser) {
            setTimeout(function(){
                var el = document.querySelector('.conversation-item[data-user-id="' + openUser + '"]');
                if (el) { el.click(); }
            }, 200);
        }
    })();
	var currentThreadId = null;
	var currentUserId = null;
	
	$('.conversation-item').click(function(e){
		e.preventDefault();
		var threadId = $(this).data('thread-id');
		var userId = $(this).data('user-id');
		var userName = $(this).find('strong').length ? $(this).find('strong').text() : $(this).find('h6').text();
		
		currentThreadId = threadId;
		currentUserId = userId;
		
		$('.conversation-item').removeClass('active');
		$(this).addClass('active');
		
		$('#chat-title').text('Chat with ' + userName);
    	$('#chat-initial').text(userName ? userName.charAt(0).toUpperCase() : '?');
		$('#receiver_id').val(userId);
		$('#thread_id').val(threadId);
		$('#message-form').show();
		
		if (threadId) {
			loadThreadMessages(threadId);
		} else {
			// For new conversations without thread yet
			$('#messages-container').html('<div class="text-center text-muted mt-5"><p>Start a new conversation by sending a message</p></div>');
		}
	});
	
	function loadThreadMessages(threadId) {
		$.ajax({
			url: 'api/ajax.php?action=get_thread_messages',
			method: 'POST',
			data: {thread_id: threadId},
			success: function(resp){
				try {
					var messages = JSON.parse(resp);
					var html = '';
					
					if (messages.length === 0) {
						html = '<div class="text-center text-muted mt-5"><p>No messages yet. Start the conversation!</p></div>';
					} else {
						messages.forEach(function(msg){
							var isOwn = msg.sender_id == '<?php echo $_SESSION['login_id'] ?>';
							var alignClass = isOwn ? 'text-right' : 'text-left';
							var bgClass = isOwn ? 'bg-primary text-white' : 'bg-white';
							
							html += '<div class="mb-2 ' + alignClass + '">';
							html += '<div class="d-inline-block p-2 rounded shadow-sm ' + bgClass + '" style="max-width:75%;">';
							html += '<div>' + msg.body + '</div>';
							html += '<div class="text-muted small">' + msg.created_at + '</div>';
							html += '</div></div>';
						});
					}
					
					$('#messages-container').html(html);
					$('#messages-container').scrollTop($('#messages-container')[0].scrollHeight);
					
					// Mark messages as read
					if (messages.length > 0) {
						$.ajax({
							url: 'api/ajax.php?action=mark_messages_read',
							method: 'POST',
							data: {thread_id: threadId}
						});
					}
				} catch (e) {
					console.error('Error parsing messages:', e);
					$('#messages-container').html('<div class="text-center text-muted mt-5"><p>Error loading messages. Please try again.</p></div>');
				}
			},
			error: function(){
				$('#messages-container').html('<div class="text-center text-muted mt-5"><p>Error loading messages. Please try again.</p></div>');
			}
		});
	}
	
	$('#send-message-form').submit(function(e){
		e.preventDefault();
		
		var messageText = $('#message_text').val().trim();
		if (!messageText) {
			return;
		}
		
		var formData = {
			thread_id: currentThreadId,
			receiver_id: currentUserId,
			message_text: messageText
		};
		
		$.ajax({
			url: 'api/ajax.php?action=send_thread_message',
			method: 'POST',
			data: formData,
			success: function(resp){
				try {
					var result = JSON.parse(resp);
					if (result.status === 'success') {
						$('#message_text').val('');
						if (currentThreadId) {
							loadThreadMessages(currentThreadId);
						} else if (result.thread_id) {
							// New thread was created
							currentThreadId = result.thread_id;
							$('#thread_id').val(currentThreadId);
							loadThreadMessages(currentThreadId);
							// Update the conversation item with thread ID
							$('.conversation-item.active').attr('data-thread-id', currentThreadId);
						}
					} else {
						alert('Error sending message: ' + result.message);
					}
				} catch (e) {
					console.error('Error parsing response:', e);
					alert('Error sending message. Please try again.');
				}
			},
			error: function(){
				alert('Error sending message. Please try again.');
			}
		});
	});
</script>
