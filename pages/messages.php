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
							<div class="card-header d-flex justify-content-between align-items-center">
								<h5 class="mb-0">Messages</h5>
								<div class="btn-group btn-group-sm" role="group">
									<button type="button" class="btn btn-outline-primary active" id="btn-conversations">Chats</button>
									<button type="button" class="btn btn-outline-primary" id="btn-all-users">All Users</button>
								</div>
							</div>
							<div class="card-body" style="height: 400px; overflow-y: auto;">
								<!-- Active Conversations Tab -->
								<div id="conversations-tab" class="list-group">
									<?php
									$myId = (int)$_SESSION['login_id'];
									$openUserId = isset($_GET['open_user']) ? (int)$_GET['open_user'] : 0;
									
									// Get conversations that have actual messages
									$conversations = $conn->query("SELECT mt.*,
									        COALESCE(sp.full_name, op.full_name, u.email) AS other_user_name,
									        u.role AS other_user_role,
									        u.id as other_user_id,
									        l.title as listing_title,
									        mt.last_message_at,
									        (SELECT COUNT(*) FROM messages WHERE thread_id = mt.id) as message_count,
									        (SELECT body FROM messages WHERE thread_id = mt.id ORDER BY created_at DESC LIMIT 1) as last_message
									    FROM message_threads mt
									    INNER JOIN users u ON (CASE WHEN mt.student_id = $myId THEN mt.owner_id ELSE mt.student_id END) = u.id
									    LEFT JOIN student_profiles sp ON u.id = sp.user_id AND u.role = 'student'
									    LEFT JOIN owner_profiles op ON u.id = op.user_id AND u.role = 'owner'
									    LEFT JOIN listings l ON mt.listing_id = l.id
									    WHERE (mt.student_id = $myId OR mt.owner_id = $myId)
									    AND EXISTS (SELECT 1 FROM messages WHERE thread_id = mt.id)
									    ORDER BY mt.last_message_at DESC, mt.created_at DESC");
									    
									if ($conversations->num_rows > 0):
									    while($row = $conversations->fetch_assoc()):
									?>
									<a href="#" class="list-group-item list-group-item-action conversation-item d-flex align-items-center" 
									   data-thread-id="<?php echo $row['id'] ?>" data-user-id="<?php echo $row['other_user_id'] ?>">
										<div class="avatar rounded-circle bg-primary text-white d-inline-flex align-items-center justify-content-center mr-2" style="width:40px;height:40px;">
											<span><?php echo strtoupper(substr($row['other_user_name'],0,1)) ?></span>
										</div>
										<div class="flex-grow-1">
											<div class="d-flex w-100 justify-content-between">
												<strong><?php echo $row['other_user_name'] ?></strong>
												<small class="text-muted"><?php echo $row['last_message_at'] ? date('M d', strtotime($row['last_message_at'])) : date('M d', strtotime($row['created_at'])) ?></small>
											</div>
											<small class="text-muted"><?php echo ucfirst($row['other_user_role']) ?></small>
											<?php if ($row['listing_title']): ?>
											<small class="text-muted d-block">Re: <?php echo $row['listing_title'] ?></small>
											<?php endif; ?>
											<?php if ($row['last_message']): ?>
											<small class="text-muted d-block"><?php echo substr($row['last_message'], 0, 50) . (strlen($row['last_message']) > 50 ? '...' : '') ?></small>
											<?php endif; ?>
										</div>
									</a>
									<?php 
									    endwhile;
									else:
									?>
									<div class="p-3 text-muted text-center">
									    <i class="fa fa-comments fa-3x mb-3 text-secondary"></i><br>
									    No active conversations yet.<br>
									    <small>Click "All Users" to start chatting with someone!</small>
									</div>
									<?php endif; ?>
								</div>
								
								<!-- All Users Tab -->
								<div id="all-users-tab" class="list-group" style="display: none;">
									<?php
									// Get all users except current user, grouped by role
									$myRole = $_SESSION['login_role'];
									
									// Show different user types based on current user's role
									if ($myRole == 'student') {
									    // Students can message owners and other students
									    $all_users = $conn->query("SELECT u.id, u.role,
									        COALESCE(sp.full_name, op.full_name, u.email) AS name,
									        u.email
									    FROM users u
									    LEFT JOIN student_profiles sp ON u.id = sp.user_id
									    LEFT JOIN owner_profiles op ON u.id = op.user_id
									    WHERE u.id != $myId AND u.role IN ('owner', 'student') AND u.status = 'active'
									    ORDER BY u.role, name");
									} elseif ($myRole == 'owner') {
									    // Owners can message students and other owners
									    $all_users = $conn->query("SELECT u.id, u.role,
									        COALESCE(sp.full_name, op.full_name, u.email) AS name,
									        u.email
									    FROM users u
									    LEFT JOIN student_profiles sp ON u.id = sp.user_id
									    LEFT JOIN owner_profiles op ON u.id = op.user_id
									    WHERE u.id != $myId AND u.role IN ('student', 'owner') AND u.status = 'active'
									    ORDER BY u.role, name");
									} else {
									    // Admins can message everyone
									    $all_users = $conn->query("SELECT u.id, u.role,
									        COALESCE(sp.full_name, op.full_name, ap.full_name, u.email) AS name,
									        u.email
									    FROM users u
									    LEFT JOIN student_profiles sp ON u.id = sp.user_id
									    LEFT JOIN owner_profiles op ON u.id = op.user_id
									    LEFT JOIN admin_profiles ap ON u.id = ap.user_id
									    WHERE u.id != $myId AND u.status = 'active'
									    ORDER BY u.role, name");
									}
									
									$currentRole = '';
									while($user = $all_users->fetch_assoc()):
									    if ($currentRole != $user['role']):
									        if ($currentRole != '') echo '</div>';
									        $currentRole = $user['role'];
									        echo '<div class="list-group-item-heading bg-light p-2"><strong>' . ucfirst($currentRole) . 's</strong></div>';
									        echo '<div class="role-group">';
									    endif;
									?>
									<a href="#" class="list-group-item list-group-item-action conversation-item d-flex align-items-center" 
									   data-user-id="<?php echo $user['id'] ?>" data-is-new="true">
										<div class="avatar rounded-circle bg-secondary text-white d-inline-flex align-items-center justify-content-center mr-2" style="width:36px;height:36px;">
											<span><?php echo strtoupper(substr($user['name'],0,1)) ?></span>
										</div>
										<div class="flex-grow-1">
											<div class="d-flex w-100 justify-content-between">
												<strong><?php echo $user['name'] ?></strong>
												<small class="text-success"><i class="fa fa-plus"></i></small>
											</div>
											<small class="text-muted"><?php echo $user['email'] ?></small>
											<small class="text-muted"><?php echo ucfirst($user['role']) ?></small>
										</div>
									</a>
									<?php 
									endwhile;
									if ($currentRole != '') echo '</div>';
									?>
								</div>
								
								<?php
								// Handle open_user parameter for new conversations
								if ($openUserId > 0) {
								    $uRes = $conn->query("SELECT u.id,
								        COALESCE(sp.full_name, op.full_name, u.email) AS name,
								        u.role
								     FROM users u
								     LEFT JOIN student_profiles sp ON u.id = sp.user_id
								     LEFT JOIN owner_profiles op ON u.id = op.user_id
								     WHERE u.id = $openUserId");
								    if ($uRes && $uRes->num_rows > 0) {
								        echo '<script>
								        $(document).ready(function() {
								            $("#btn-all-users").click();
								            setTimeout(function() {
								                $(".conversation-item[data-user-id=\'' . $openUserId . '\']").click();
								            }, 300);
								        });
								        </script>';
								    }
								}
								?>
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
	
	// Tab switching functionality
	$('#btn-conversations').click(function() {
		$('#conversations-tab').show();
		$('#all-users-tab').hide();
		$(this).addClass('active');
		$('#btn-all-users').removeClass('active');
	});
	
	$('#btn-all-users').click(function() {
		$('#conversations-tab').hide();
		$('#all-users-tab').show();
		$(this).addClass('active');
		$('#btn-conversations').removeClass('active');
	});
	
	// Conversation item click handler (using event delegation for dynamic content)
	$(document).on('click', '.conversation-item', function(e){
		e.preventDefault();
		var threadId = $(this).data('thread-id');
		var userId = $(this).data('user-id');
		var userName = $(this).find('strong').length ? $(this).find('strong').text() : $(this).find('h6').text();
		var isNew = $(this).data('is-new');
		
		currentThreadId = threadId || null;
		currentUserId = userId;
		
		$('.conversation-item').removeClass('active');
		$(this).addClass('active');
		
		$('#chat-title').text('Chat with ' + userName);
    	$('#chat-initial').text(userName ? userName.charAt(0).toUpperCase() : '?');
		$('#receiver_id').val(userId);
		$('#thread_id').val(threadId || '');
		$('#message-form').show();
		
		if (threadId) {
			// Existing conversation with messages
			loadThreadMessages(threadId);
		} else if (isNew) {
			// New conversation
			$('#messages-container').html('<div class="text-center text-muted mt-5"><i class="fa fa-paper-plane fa-2x mb-3 text-primary"></i><br><p>Start a new conversation with ' + userName + '</p><small class="text-muted">Send your first message below!</small></div>');
		} else {
			// Fallback
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
							// Switch to conversations tab to show the new conversation
							if ($('#all-users-tab').is(':visible')) {
								$('#btn-conversations').click();
								// Refresh the page to show the new conversation in the list
								setTimeout(function() {
									location.reload();
								}, 1000);
							}
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
