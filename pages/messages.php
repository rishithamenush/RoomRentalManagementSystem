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
									$conversations = $conn->query("SELECT DISTINCT 
										CASE 
											WHEN sender_id = '{$_SESSION['login_id']}' THEN receiver_id 
											ELSE sender_id 
										END as other_user_id,
										u.name as other_user_name,
										u.role as other_user_role,
										MAX(m.date_created) as last_message_time
										FROM messages m 
										LEFT JOIN users u ON (CASE 
											WHEN m.sender_id = '{$_SESSION['login_id']}' THEN m.receiver_id 
											ELSE m.sender_id 
										END) = u.id
										WHERE m.sender_id = '{$_SESSION['login_id']}' OR m.receiver_id = '{$_SESSION['login_id']}'
										GROUP BY other_user_id
										ORDER BY last_message_time DESC");
									
									while($row = $conversations->fetch_assoc()):
									?>
									<a href="#" class="list-group-item list-group-item-action conversation-item" 
									   data-user-id="<?php echo $row['other_user_id'] ?>">
										<div class="d-flex w-100 justify-content-between">
											<h6 class="mb-1"><?php echo $row['other_user_name'] ?></h6>
											<small><?php echo date('M d', strtotime($row['last_message_time'])) ?></small>
										</div>
										<p class="mb-1 text-muted"><?php echo ucfirst($row['other_user_role']) ?></p>
									</a>
									<?php endwhile; ?>
								</div>
							</div>
						</div>
					</div>
					<div class="col-md-8">
						<div class="card">
							<div class="card-header">
								<h5 id="chat-title">Select a conversation</h5>
							</div>
							<div class="card-body" style="height: 400px; overflow-y: auto;" id="messages-container">
								<div class="text-center text-muted">
									<p>Select a conversation to start messaging</p>
								</div>
							</div>
							<div class="card-footer" id="message-form" style="display: none;">
								<form id="send-message-form">
									<div class="input-group">
										<input type="hidden" id="receiver_id" name="receiver_id">
										<input type="text" class="form-control" id="message_text" name="message_text" placeholder="Type your message...">
										<div class="input-group-append">
											<button class="btn btn-primary" type="submit">Send</button>
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
	$('.conversation-item').click(function(e){
		e.preventDefault();
		var userId = $(this).data('user-id');
		var userName = $(this).find('h6').text();
		
		$('.conversation-item').removeClass('active');
		$(this).addClass('active');
		
		$('#chat-title').text('Chat with ' + userName);
		$('#receiver_id').val(userId);
		$('#message-form').show();
		
		loadMessages(userId);
	});
	
	function loadMessages(userId) {
		$.ajax({
			url: 'api/ajax.php?action=get_messages',
			method: 'POST',
			data: {other_user_id: userId},
			success: function(resp){
				var messages = JSON.parse(resp);
				var html = '';
				
				messages.forEach(function(msg){
					var isOwn = msg.sender_id == '<?php echo $_SESSION['login_id'] ?>';
					var alignClass = isOwn ? 'text-right' : 'text-left';
					var bgClass = isOwn ? 'bg-primary text-white' : 'bg-light';
					
					html += '<div class="mb-2 ' + alignClass + '">';
					html += '<div class="d-inline-block p-2 rounded ' + bgClass + '">';
					html += '<small class="d-block">' + msg.message_text + '</small>';
					html += '<small class="text-muted">' + msg.date_created + '</small>';
					html += '</div></div>';
				});
				
				$('#messages-container').html(html);
				$('#messages-container').scrollTop($('#messages-container')[0].scrollHeight);
			}
		});
	}
	
	$('#send-message-form').submit(function(e){
		e.preventDefault();
		
		$.ajax({
			url: 'api/ajax.php?action=send_message',
			method: 'POST',
			data: $(this).serialize(),
			success: function(resp){
				$('#message_text').val('');
				loadMessages($('#receiver_id').val());
			}
		});
	});
</script>
