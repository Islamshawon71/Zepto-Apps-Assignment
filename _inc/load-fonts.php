<?php
include 'connect.php';

$limit = $_GET['length'];
$start = $_GET['start'];

$totalRecords = $conn->query("SELECT COUNT(*) FROM fonts")->fetch_row()[0];

$query = "SELECT * FROM fonts order by id desc ";
$query .= " LIMIT $start, $limit";

$result = $conn->query($query);

$data = array();
while ($row = $result->fetch_assoc()) {
    $row['preview'] = '
        <style>
                @font-face {
                    font-family: "'.$row['font_name'].'";
                    src: url("uploads/'.$row['font_name'].'") format("truetype");
                    font-weight: bold;
                    font-style: normal;
                }
        
        </style>
            <span style="font-family: \''.$row['font_name'].'\';" class="'.$row['font_name'].'" = >Preview</span>
       ';
    $row['actions'] = '<button class="text-danger me-2 border-0 delete-font" value="'.$row['id'].'" >Delete</button>';
    $data[] = $row;
}

$response = array(
    "draw" => intval($_GET['draw']),
    "recordsTotal" => intval($totalRecords),
    "recordsFiltered" => intval($totalRecords),
    "data" => $data
);

echo json_encode($response);
