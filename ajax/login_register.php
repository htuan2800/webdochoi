<?php

require('../admin/inc/db_config.php');
require('../admin/inc/essentials.php');

if (isset($_POST['register'])) {
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
    $u_exist = select("SELECT * FROM `user` WHERE `Phone`=? LIMIT 1", [$data['phonenum']], 's');

    if (mysqli_num_rows($u_exist) != 0) {
        echo 'phone_already';
        exit;
    }

    $enc_pass = password_hash($data['pass'], PASSWORD_BCRYPT);
    $createAt=date('Y-m-d');
    $query1 = "INSERT INTO `user`(`UserName`, `Password`, `RoleID`, `FullName`,`Phone`, `Email`, `Gender`, `Address`, `create_at`) VALUES (?,?,?,?,?,?,?,?,?)";
    $values1 = [$data['username'], $enc_pass, 1, $data['FullName'], $data['phonenum'], $data['email'], $data['gender'], $data['address'], $createAt];
    if (insert($query1, $values1, 'ssissssss')) {
        echo 1;
    } else {
        echo 'ins_failed';
    }
}

if (isset($_POST['login'])) {
    $data = filteration($_POST);

    // check user exists or not
    $u_exist = select("SELECT * FROM `user` WHERE `Email`=? OR `UserName`=?", [$data['email_mob'], $data['email_mob']], 'ss');

    if (mysqli_num_rows($u_exist) == 0) {
        echo 'inv_email_mob';
        exit;
    } else {
        $u_fetch = mysqli_fetch_assoc($u_exist);
        if ($u_fetch['Status'] == 1) {
            echo 'inactive';
        }
        elseif($u_fetch['RoleID'] !=1 && $u_fetch['RoleID'] !=5){
            echo 'Something went wrong';
        }
        else 
        {
            if (!password_verify($data['pass'], $u_fetch['Password'])) {
                echo 'invalid_pass';
            } else {
                session_start();
                $_SESSION['user'] = [
                    'login'    => true,
                    'uId'      => $u_fetch['UserID'],
                    'uName'    => $u_fetch['UserName'],
                    'uFullName' => $u_fetch['FullName'],
                    'uEmail'   => $u_fetch['Email'],
                    'uPic'     => $u_fetch['IMG'],
                    'uPhone'   => $u_fetch['Phone'],
                    'uAddress' => $u_fetch['Address'],
                    'RoleID'   => $u_fetch['RoleID']
                ];
                echo 1;
            }
        }
    }
}
