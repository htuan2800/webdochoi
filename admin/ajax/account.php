<?php
require('../inc/db_config.php');
require('../inc/essentials.php');
session_start();
date_default_timezone_set('Asia/Ho_Chi_Minh');
if (isset($_POST['addUser'])) {
    $data = filteration($_POST);

    if ($data['pass'] != $data['cpass']) {
        echo 'pass_mismatch';
        exit;
    }

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
    $values1 = [$data['username'], $enc_pass,  $data['role'], $data['fullname'], $data['phone'], $data['email'], $data['gender'], $data['address'], NULL, $createAt];
    if (insert($query1, $values1, 'ssisssssss')) {
        echo 1;
    } else {
        echo 'ins_failed';
    }
}

if (isset($_POST['get_accounts'])) {
    $res = mysqli_query($con, "SELECT f.UserID, f.UserName, f.RoleID, f.Email, f.Status, f.deleted, rfac.RoleName FROM `user` f INNER JOIN `role` rfac ON f.RoleID=rfac.RoleID");
    $i = 1;

    $data = "";
    while ($row = mysqli_fetch_assoc($res)) {
        if (($row['UserID'] != $_SESSION['mUser']['muId'])) {
            $status = "";
            if (!$row['Status']) {
                $status = "<button onclick='toggle_status($row[UserID],1)' class='btn btn-dark btn-sm shadow-none'>
            active</button>";
            } else {
                $status = "<button onclick='toggle_status($row[UserID],0)' class='btn btn-warning btn-sm shadow-none'>
                inactive</button>";
            }
            $btn = "";

            if ($row['deleted'] == 0) {
                $btn = "<button type='button' onclick='remove_acc($row[UserID])' class='btn btn-danger shadow-none btn-sm'>
                                    <i class='bi bi-trash'></i>
                                </button>
                                <button type='button' onclick='edit_acc($row[UserID])' class='btn btn-primary shadow-none btn-sm' data-bs-toggle='modal' data-bs-target='#edit-acc'>
                                            <i class='bi bi-pencil-square'></i>
                                </button>";
            } else {
                $status = "";
                $btn .= "
                                        <span>Người dùng không còn hoạt động</span>
                                    ";
            }
            $data .= "
            <tr>
                <td>$i</td>
                <td>$row[UserID]</td>
                <td>$row[UserName]</td>
                <td title='$row[RoleName]'>$row[RoleID]</td>
                <td>$row[Email]</td> 
                <td>$status</td>      
                <td>$btn</td>
            </tr>
            ";
            $i++;
        }
    }
    echo $data;
}

if (isset($_POST['toggle_status'])) {
    $frm_data = filteration($_POST);
    $q = "UPDATE `user` SET `Status`=? WHERE `UserID`=?";
    $v = [$frm_data['value'], $frm_data['toggle_status']];

    if (update($q, $v, 'ii')) {
        echo 1;
    } else {
        echo 0;
    }
}

if (isset($_POST['get_acc'])) {
    $frm_data = filteration($_POST);
    $res = mysqli_query($con, "SELECT * FROM `user` WHERE UserID=$frm_data[get_acc]");
    $user_id = "";
    $username = "";
    $fullname = "";
    $email = "";
    $phone = "";
    $address = "";
    $roleID = "";
    if (mysqli_num_rows($res) > 0) {
        if ($row = mysqli_fetch_assoc($res)) {
            $user_id = $row['UserID'];
            $username = $row['UserName'];
            $fullname = $row['FullName'];
            $email = $row['Email'];
            $phone = $row['Phone'];
            $address = $row['Address'];
            $roleID = $row['RoleID'];
        }
    }

    $data = array('user_id' => $user_id, 'username' => $username, 'fullname' => $fullname, 'email' => $email, 'address' => $address, 'phone' => $phone, 'roleID' => $roleID);
    echo json_encode($data);
}

if (isset($_POST['edit_account'])) {
    $frm_data = filteration($_POST);
    $flag = 0;
    $q1 = "UPDATE `user` SET `UserName`=?,`FullName`=?, `Phone`=?, `Address`=?, `RoleID`=?, `Email`=? WHERE `UserID`=?";
    $values = [$frm_data['username'], $frm_data['fullname'], $frm_data['phone'], $frm_data['address'], $frm_data['roles'], $frm_data['email'], $frm_data['user_id']];
    if (update($q1, $values, 'ssssssi')) {
        $flag = 1;
    }
    if ($flag) {
        echo 1;
    } else {
        echo 0;
    }
}


if (isset($_POST['remove_acc'])) {
    $frm_data = filteration($_POST);
    $res5 = update("UPDATE `user` SET `deleted`=? WHERE `UserID`=?", [1, $frm_data['user_id']], 'ii');
    if ($res5) {
        echo 1;
    } else {
        echo 0;
    }
}
