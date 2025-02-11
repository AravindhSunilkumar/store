<?php
require_once 'connection.php';
$data = json_decode(file_get_contents("php://input"), true);
if ($data) {
    foreach ($data as $row) {
        $id = intval($row['id']);
        $priority = intval($row['priority']);
        $conn->query("UPDATE student_details SET priority = $priority WHERE id = $id");
    }
    echo json_encode(["message" => "Priorities updated successfully"]);
}
$conn->close();
?>