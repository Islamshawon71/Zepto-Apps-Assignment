<?php

include 'connect.php';

if ($_GET['action_type'] == 'delete' ){
    $conn->query("delete from fonts  where id='".$_GET['font_id']."'");
}