<?php
include 'connect.php';
$target_dir = "../uploads/";
$target_file = $target_dir . basename($_FILES["file"]["name"]);
if (move_uploaded_file($_FILES["file"]["tmp_name"], $target_file)) {
    $conn->query("insert into fonts (id,font_name,group_id) values (null,'".$_FILES["file"]["name"]."',null)");
    echo "The file ". htmlspecialchars( basename( $_FILES["file"]["name"])). " has been uploaded.";
} else {
    echo "Sorry, there was an error uploading your file.";
}
?>
