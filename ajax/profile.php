<?php
require('../admin/inc/db_config.php');
require('../admin/inc/essentials.php');
session_start();

if (isset($_POST['info_form'])) {
    $data = filteration($_POST);
    // check user exists or not
    $u_exist = select("SELECT * FROM `user` WHERE (`Email`=? or `UserName`=?) AND `UserID`!=? LIMIT 1", [$data['email'], $data['username'],$_SESSION['user']['uId']], 'ssi');
    if (mysqli_num_rows($u_exist) != 0) {
        $u_exist_fetch = mysqli_fetch_assoc($u_exist);
        echo ($u_exist_fetch['email'] == $data['email']) ? 'email_already' : 'username_already';
        exit;
    }

    // check phone user exists or not
    $u_exist = select("SELECT * FROM `user` WHERE `Phone`=? AND `UserID`!=? LIMIT 1", [$data['phone'],$_SESSION['user']['uId']], 'si');
    if (mysqli_num_rows($u_exist) != 0) {
        echo 'phone_already';
        exit;
    }

    $query="UPDATE `user` SET `UserName`=?, `Email`=?, `FullName`=?, `Phone`=?, `Address`=? WHERE `UserID`=? ";
    $values=[$data['username'],$data['email'],$data['fullname'],$data['phone'],$data['address'],$_SESSION['user']['uId']];
    if(update($query,$values,'sssssi')){
        $_SESSION['user']['uName']=$data['username'];
        $_SESSION['user']['uEmail']=$data['email'];
        $_SESSION['user']['uFullName']=$data['fullname'];
        $_SESSION['user']['uPhone']=$data['phone'];
        $_SESSION['user']['uAddress']=$data['address'];
        echo 1;
    } else {
        echo 2;
    }

}


if (isset($_POST['pass_form'])) {
    $frm_data=filteration($_POST);
    if($frm_data['new_pass']!=$frm_data['confirm_pass']){
        echo 'mismatch';
        exit;
    }
    $enc_pass = password_hash($frm_data['new_pass'], PASSWORD_BCRYPT);
    $query="UPDATE `user` SET `Password`=? WHERE `UserID`=?";
    $values=[$enc_pass,$_SESSION['user']['uId']];
    if(update($query,$values,'ss')){
        echo 1;
    } else {
        echo 0;
    }
}
