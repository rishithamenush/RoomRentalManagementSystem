<?php
include 'config/db_connect.php';

echo "<h2>Messages Table Structure Check</h2>";

$structure = $conn->query("DESCRIBE messages");
echo "<h3>Current Messages Table Structure:</h3>";
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

// Check if we need to add missing columns
$columns = [];
$result = $conn->query("DESCRIBE messages");
while ($row = $result->fetch_assoc()) {
    $columns[] = $row['Field'];
}

echo "<h3>Missing Columns Check:</h3>";
$required_columns = ['receiver_id', 'message_text', 'date_created'];

foreach ($required_columns as $col) {
    if (!in_array($col, $columns)) {
        echo "<p>❌ Missing column: $col</p>";
    } else {
        echo "<p>✅ Column exists: $col</p>";
    }
}

// Show sample data
echo "<h3>Sample Messages Data:</h3>";
$messages = $conn->query("SELECT * FROM messages LIMIT 3");
echo "<table border='1'>";
echo "<tr>";
foreach ($columns as $col) {
    echo "<th>$col</th>";
}
echo "</tr>";
while ($msg = $messages->fetch_assoc()) {
    echo "<tr>";
    foreach ($columns as $col) {
        echo "<td>" . (isset($msg[$col]) ? $msg[$col] : 'NULL') . "</td>";
    }
    echo "</tr>";
}
echo "</table>";
?>
