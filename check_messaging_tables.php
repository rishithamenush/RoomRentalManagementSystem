<?php
include 'config/db_connect.php';

echo "<h2>Messaging Tables Check</h2>";

// Check if messages table exists
$tables = $conn->query("SHOW TABLES LIKE 'messages'");
if ($tables->num_rows > 0) {
    echo "<h3>Messages Table Structure:</h3>";
    $structure = $conn->query("DESCRIBE messages");
    echo "<table border='1'>";
    echo "<tr><th>Field</th><th>Type</th><th>Null</th><th>Key</th><th>Default</th><th>Extra</th></tr>";
    while ($row = $structure->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . $row['Field'] . "</td>";
        echo "<td>" . $row['Type'] . "</td>";
        echo "<td>" . $row['Null'] . "</td>";
        echo "<td>" . $row['Key'] . "</td>";
        echo "<td>" . $row['Default'] . "</td>";
        echo "<td>" . $row['Extra'] . "</td>";
        echo "</tr>";
    }
    echo "</table>";
    
    // Check messages data
    $count = $conn->query("SELECT COUNT(*) as count FROM messages")->fetch_assoc()['count'];
    echo "<p>Total messages: " . $count . "</p>";
    
    if ($count > 0) {
        echo "<h4>Sample Messages:</h4>";
        $messages = $conn->query("SELECT * FROM messages LIMIT 5");
        echo "<table border='1'>";
        echo "<tr><th>ID</th><th>Sender ID</th><th>Receiver ID</th><th>Message</th><th>Date</th></tr>";
        while ($msg = $messages->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $msg['id'] . "</td>";
            echo "<td>" . $msg['sender_id'] . "</td>";
            echo "<td>" . $msg['receiver_id'] . "</td>";
            echo "<td>" . substr($msg['message_text'], 0, 50) . "...</td>";
            echo "<td>" . $msg['date_created'] . "</td>";
            echo "</tr>";
        }
        echo "</table>";
    }
} else {
    echo "<p>❌ Messages table does not exist</p>";
}

// Check if message_threads table exists
$threads = $conn->query("SHOW TABLES LIKE 'message_threads'");
if ($threads->num_rows > 0) {
    echo "<h3>Message Threads Table Structure:</h3>";
    $structure = $conn->query("DESCRIBE message_threads");
    echo "<table border='1'>";
    echo "<tr><th>Field</th><th>Type</th><th>Null</th><th>Key</th><th>Default</th><th>Extra</th></tr>";
    while ($row = $structure->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . $row['Field'] . "</td>";
        echo "<td>" . $row['Type'] . "</td>";
        echo "<td>" . $row['Null'] . "</td>";
        echo "<td>" . $row['Key'] . "</td>";
        echo "<td>" . $row['Default'] . "</td>";
        echo "<td>" . $row['Extra'] . "</td>";
        echo "</tr>";
    }
    echo "</table>";
    
    $count = $conn->query("SELECT COUNT(*) as count FROM message_threads")->fetch_assoc()['count'];
    echo "<p>Total message threads: " . $count . "</p>";
} else {
    echo "<p>❌ Message threads table does not exist</p>";
}
?>
