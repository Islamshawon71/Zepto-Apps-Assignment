<?php
include 'connect.php';

$query = "SELECT * FROM fonts order by id desc ";
$result = $conn->query($query);

$data = array();
while ($row = $result->fetch_assoc()) {
    $data[] = [
        "id" => $row['id'],
        "text" => $row['font_name'],
    ];
}
echo json_encode($data);
?>