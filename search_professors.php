<?php
include "db.php";

if (!isset($_GET['q'])) {
    exit();
}

$q = $conn->real_escape_string($_GET['q']);

$sql = "SELECT id, name FROM professors 
        WHERE name LIKE '%$q%' 
        ORDER BY name ASC 
        LIMIT 10";

$result = $conn->query($sql);

while ($row = $result->fetch_assoc()) {
    echo '<div class="search-item" onclick="goProfile('.$row['id'].')">'
         . htmlspecialchars($row['name']) .
         '</div>';
}
?>
