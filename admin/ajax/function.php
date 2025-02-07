<?php

require('../inc/db_config.php');
require('../inc/essentials.php');

adminLogin();

if (isset($_POST['get_funcs'])) {
    $res = mysqli_query($con, "SELECT * FROM `function`");
    $i = 1;
    $data = "";
    while ($row = mysqli_fetch_assoc($res)) {
        $btn = "
            <button type='button' onclick='edit_func($row[FunctionID])' class='btn btn-primary shadow-none btn-sm' data-bs-toggle='modal' data-bs-target='#edit-func'>
                                <i class='bi bi-pencil-square'></i>
            </button>";
        $data .= "
            <tr>
                <td>$i</td>
                <td>$row[FunctionID]</td>
                <td>$row[FunctionName]</td>      
                <td>$btn</td>
            </tr>
            ";
        $i++;
    }
    echo $data;
}

if (isset($_POST['edit_func'])) {
    $frm_data = filteration($_POST);
    $res = mysqli_query($con, "SELECT * FROM `role`");
    $data1 = "";
    while ($row = mysqli_fetch_assoc($res)) {
        if($row['RoleID']!=1){
            $data1 .= "
    <tr class='align-middle'>
        <td>" . htmlspecialchars($row['RoleName']) . "</td>
        <td>
            <input 
                onchange='submit_edit_role(\"role" . $row['RoleID'] . "\", " . $row['RoleID'] . ", " . $frm_data['edit_func'] . ")' 
                type='checkbox' 
                id='role" . $row['RoleID'] . "' 
                name='role[" . $row['RoleID'] . "]' 
                value='" . $row['RoleID'] . "'
            >
        </td>
    </tr>
";
        }
    }
    $res = mysqli_query($con, "SELECT f.RoleID FROM `roledetail` f INNER JOIN `function` rfac ON f.FunctionID=rfac.FunctionID WHERE rfac.FunctionID=$frm_data[edit_func]");
    $data2 = [];
    while ($row = mysqli_fetch_assoc($res)) {
                    array_push($data2,$row['RoleID']);
    }
     $data=array('data1'=>$data1, 'data2'=>$data2, "func_id"=>$frm_data['edit_func']);
    echo json_encode($data);
}

if (isset($_POST['edit_roleF'])) {
    $frm_data = filteration($_POST);
    if($frm_data['action']=='add'){
        if(insert("INSERT INTO `roledetail`(`RoleID`, `FunctionID`) VALUES (?,?)",[$frm_data['rid'],$frm_data['fid']],"ii")){
            echo 1;
        }
    } else if($frm_data['action']=='delete'){
        if(delete("DELETE FROM `roledetail` WHERE `RoleID`=? AND `FunctionID`=?",[$frm_data['rid'],$frm_data['fid']],"ii")){
            echo 1;
        }
    }
}
