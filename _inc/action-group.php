<?php

include 'connect.php';

if ($_GET['action_type'] == 'save-group' ){
    $conn->query("insert into groups (id,group_name) values (null,'".$_GET['group_name']."')");
    $group_id = $conn->insert_id;
    $conn->query("delete from font_group  where group_id='".$group_id."'");
    foreach ($_GET['fonts'] as $font){
        $conn->query("insert into font_group (font_id,group_id) values ('".$font['font_id']."','".$group_id."')");
    }
}

else if ($_GET['action_type'] == 'delete' ){
    $conn->query("delete from groups  where id='".$_GET['group_id']."'");
    $conn->query("delete from font_group  where group_id='".$_GET['action_type']."'");
}



else if ($_GET['action_type'] == 'edit' ){

    $result =$conn->query("SELECT * FROM groups where id='".$_GET['group_id']."'");
    $font_group = $result->fetch_assoc();

    $result = $conn->query("SELECT * FROM groups LEFT JOIN font_group on (groups.id = font_group.group_id) LEFT JOIN fonts on (font_group.font_id = fonts.id)  where group_id='".$_GET['group_id']."'");

    $html = '';
    while ($row = $result->fetch_assoc()) {
        $html = $html . '<tr>
        <td><select name="fonts" class="form-control font-select select2">
        <option value="'.$row['id'].'">'.$row['font_name'].'</option>
        </select></td>
        <td><button class="btn btn-danger btn-sm remove-font-list"> x </button></td>
    </tr>';
    }

   echo '<div class="input-group mb-3">
    <input type="text" class="form-control" id="group_name" placeholder="Group Name" value="'.$font_group['group_name'].'">
</div>
<table class="table table-striped font-group-table">
       '.$html.'
</table><div class="row"><div class="col text-end"><button class="btn btn-sm btn-default add-row">+ Add Row</button></div></div>';
}

else{
    $group_id = $_GET['action_type'];
    $conn->query("UPDATE groups SET group_name = '".$_GET['group_name']."' where id='".$group_id."' ");
    $conn->query("delete from font_group  where group_id='".$_GET['action_type']."'");
    foreach ($_GET['fonts'] as $font){
        $conn->query("insert into font_group (font_id,group_id) values ('".$font['font_id']."','".$group_id."')");
    }
}

