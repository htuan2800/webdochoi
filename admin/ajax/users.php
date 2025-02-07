<?php

require('../inc/db_config.php');
require('../inc/essentials.php');

adminLogin();

if (isset($_POST['get_users'])) {
    $res = mysqli_query($con, "SELECT UserID, FullName, Email, Status, deleted FROM `user` where RoleID=1");
    $i = 1;

    $data = "";
    while ($row = mysqli_fetch_assoc($res)) {
        if ($row['Status'] == 0) {
            $status = "<button onclick='toggle_status($row[UserID],1)' class='btn btn-dark btn-sm shadow-none'>active</button>";
        } else {
            $status = "<button onclick='toggle_status($row[UserID],0)' class='btn btn-warning btn-sm shadow-none'>inactive</button>";
        }
        $del_btn="";

        if($row['deleted']==0)
        {
            $del_btn .= "
                <button type='button' onclick='edit_cus($row[UserID])' class='btn btn-primary shadow-none btn-sm' data-bs-toggle='modal' data-bs-target='#edit-cus'>
                                    <i class='bi bi-pencil-square'></i>
                </button>
                <button type='button' onclick='remove_user($row[UserID])' class='btn btn-danger shadow-none btn-sm'>
                            <i class='bi bi-trash'></i>
                 </button>
                ";
        }
        else {
            $status="";
            $del_btn.="
                <span>Khách hàng không còn hoạt động</span>
            ";
        }
        $data .= "
            <tr>
                <td>$i</td>
                <td>$row[FullName]</td>
                <td>$row[Email]</td>      
                <td></td>      
                <td>$del_btn</td>
            </tr>
            ";
        $i++;
    }
    echo $data;
}

if (isset($_POST['add_cus'])) {
    $data = filteration($_POST);

    // check user exists or not
    $u_exist = select("SELECT * FROM `user` WHERE `Email`=? or `UserName`=? LIMIT 1", [$data['email'], $data['username']], 'ss');

    if (mysqli_num_rows($u_exist) != 0) {
        $u_exist_fetch = mysqli_fetch_assoc($u_exist);
        echo ($u_exist_fetch['Email'] == $data['email']) ? 'email_already' : 'username_already';
        exit;
    }
    // check phone user exists or not
    $u_exist = select("SELECT * FROM `user` WHERE `Phone`=? LIMIT 1", [$data['phone']], 's');

    if (mysqli_num_rows($u_exist) != 0) {
        echo 'phone_already';
        exit;
    }

    $createAt = date('Y-m-d');
    $enc_pass = password_hash($data['pass'], PASSWORD_BCRYPT);
    $query1 = "INSERT INTO `user`(`UserName`, `Password`, `RoleID`, `FullName`,`Phone`, `Email`, `Gender`, `Address`,`IMG`, `create_at`) VALUES (?,?,?,?,?,?,?,?,?,?)";
    $values1 = [$data['username'], $enc_pass,  1, $data['fullname'], $data['phone'], $data['email'], $data['gender'], $data['address'], NULL, $createAt];
    if (insert($query1, $values1, 'ssisssssss')) {
        echo 1;
    } else {
        echo 'ins_failed';
    }
}


if (isset($_POST['toggle_status'])) {
    $frm_data = filteration($_POST);
    $q = "UPDATE `user` SET `Status`=? WHERE `UserID`=?";
    $v = [$frm_data['value'], $frm_data['toggle_status']];

    if (update($q, $v, 'si')) {
        echo 1;
    } else {
        echo 0;
    }
}

if (isset($_POST['remove_user'])) {
    $frm_data = filteration($_POST);
    $res = update("UPDATE `user` SET `deleted`=1 WHERE `UserID`=?", [$frm_data['user_id']], 'i');
    if ($res) {
        echo 1;
    } else {
        echo 0;
    }
}



if (isset($_POST['get_cus'])) {
    $frm_data = filteration($_POST);
    $res = mysqli_query($con, "SELECT * FROM `user` WHERE UserID=$frm_data[get_cus]");
    $cus_id = "";
    $fullname = "";
    $gender = "";
    $phone = "";
    $address = "";
    $status = "";
    if (mysqli_num_rows($res) > 0) {
        if ($row = mysqli_fetch_assoc($res)) {
            $cus_id = $row['UserID'];
            $fullname = $row['FullName'];
            $status = $row['Status'];
            if ($row['Gender'] == "m") {
                $gender = "1";
            } else if ($row['Gender'] == "fm") {
                $gender = "2";
            }
            $phone = $row['Phone'];
            $address = $row['Address'];
        }
    }

    $data = array('cus_id' => $cus_id, 'fullname' => $fullname, 'gender' => $gender, 'phone' => $phone, 'address' => $address, 'status' => $status);
    echo json_encode($data);
}


if (isset($_POST['edit_cus'])) {
    $frm_data = filteration($_POST);
    $flag = 0;

    $q1 = "UPDATE `user` SET `FullName`=?,`Gender`=?,`Phone`=?,`Address`=? WHERE `UserID`=?";
    $values = [$frm_data['fullname'], $frm_data['gender'], $frm_data['phone'], $frm_data['address'], $frm_data['cus_id']];

    if (update($q1, $values, 'ssssi')) {
        $flag = 1;
    }
    if ($flag) {
        echo 1;
    } else {
        echo 0;
    }
}
