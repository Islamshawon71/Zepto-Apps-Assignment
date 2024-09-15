<?php
include 'connect.php';

$limit = $_GET['length'];
$start = $_GET['start'];

$totalRecords = $conn->query("SELECT COUNT(*) FROM groups")->fetch_row()[0];

$query = "SELECT * FROM groups order by id desc ";
$query .= " LIMIT $start, $limit";

$result = $conn->query($query);

$data = array();
while ($row = $result->fetch_assoc()) {

    $fontsResutl = $conn->query("SELECT * FROM `groups` LEFT JOIN font_group on (groups.id = font_group.group_id) LEFT JOIN fonts on (font_group.font_id = fonts.id) where  group_id = '".$row['id']."' ");
    $fontData = array();
    $fontCount = 0;

    while ($fontsRow = $fontsResutl->fetch_assoc()) {
        $fontData[] = $fontsRow['font_name'];
        $fontCount++;
    }

    $row['fonts'] = implode(' , ',$fontData);
    $row['count'] = $fontCount;
    $row['group_name'] = $row['group_name'];
    $row['actions'] = '<button class="text-danger me-2 border-0 delete-group" value="'.$row['id'].'" >Delete</button><button class="text-success border-0 edit-group"  value="'.$row['id'].'" >Edit</button>';
    $data[] = $row;

}

$response = array(
    "draw" => intval($_GET['draw']),
    "recordsTotal" => intval($totalRecords),
    "recordsFiltered" => intval($totalRecords),
    "data" => $data
);

echo json_encode($response);
