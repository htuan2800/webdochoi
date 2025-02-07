<?php
require('inc/essentials.php');
require('inc/db_config.php');

session_start();
if ((isset($_SESSION['mUser']['mlogin']) && $_SESSION['mUser']['mlogin'] == true)) {
    if ($_SESSION['mUser']['RoleID'] == 2) {
        redirect('bills.php');
    } else if ($_SESSION['mUser']['RoleID'] == 3) {
        redirect('account.php');
    } else if ($_SESSION['mUser']['RoleID'] == 4) {
        redirect('category_producer.php');
    } else if ($_SESSION['mUser']['RoleID'] == 5) {
        redirect('account.php');
    } else {
        alert('error', 'You are not Welcome!');
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login Panel</title>
    <?php require('inc/links.php'); ?>
    <style>
        .login-form {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 400px;
        }
    </style>
</head>

<body class="bg-light">
    <div class="login-form text-center rounded bg-white shadow overflow-none">
        <form method="POST">
            <h4 class="bg-dark text-white py-3">ADMIN LOGIN PANEL</h4>
            <div class="p-4">
                <div class="mb-3">
                    <input name="admin_name" required type="text" class="form-control shadow-none text-center" placeholder="Admin Name">
                </div>
                <div class="mb-4">
                    <input name="admin_pass" required type="password" class="form-control shadow-none text-center" placeholder="Password">
                </div>
                <button name="login" type="submit" class="btn text-white custom-bg shadow-none">LOGIN</button>
            </div>
        </form>
    </div>

    <?php
    if (isset($_POST['login'])) {
        $frm_data = filteration($_POST);
        // check user exists or not
        $u_exist = select("SELECT * FROM `user` WHERE `Email`=? OR `UserName`=?", [$frm_data['admin_name'], $frm_data['admin_name']], 'ss');

        if (mysqli_num_rows($u_exist) == 0) {
            echo 'Login failed - Invalid Credentials!';
            exit;
        } else {
            $u_fetch = mysqli_fetch_assoc($u_exist);
            if ($u_fetch['Status'] == 1) {
                echo 'inactive';
            } else {
                if (!password_verify($frm_data['admin_pass'], $u_fetch['Password'])) {
                    echo 'invalid_pass';
                } else {
                    if($u_fetch['RoleID']==1)
                    {
                        redirect('http://localhost/shoes1/index.php');
                    }
                    else
                    {
                        $_SESSION['mUser'] = [
                            'mlogin'    => true,
                            'muId'      => $u_fetch['UserID'],
                            'mName'    => $u_fetch['UserName'],
                            'mFullName' => $u_fetch['FullName'],
                            'mEmail'   => $u_fetch['Email'],
                            'mPic'     => $u_fetch['IMG'],
                            'mPhone'   => $u_fetch['Phone'],
                            'mAddress' => $u_fetch['Address'],
                            'RoleID'   => $u_fetch['RoleID'],
                        ];
    
                        if ($_SESSION['mUser']['RoleID'] == 2) {
                            redirect('bills.php');
                        } else if ($_SESSION['mUser']['RoleID'] == 3) {
                            redirect('account.php');
                        } else if ($_SESSION['mUser']['RoleID'] == 4) {
                            redirect('category_producer.php');
                        } else if ($_SESSION['mUser']['RoleID'] == 5) {
                            redirect('account.php');
                        }
                    }
                }
            }
        }
    }
    ?>

    <script>
        function toast(type, msg, position = 'body') {
            let bs_class = (type == 'success') ? 'alert-success' : 'alert-danger';
            let element = document.createElement('div');
            element.innerHTML = `
        <div class="alert ${bs_class} alert-dismissible fade show" role="alert">
                <strong class="me-3">${msg}</strong>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div> 
        `;
            if (position == 'body') {
                document.body.append(element);
                element.classList.add('custom-alert');
            } else {
                document.getElementById(position).appendChild(element);
            }
            setTimeout(remAlert, 2000);
        }

        function remAlert() {
            document.getElementsByClassName('alert')[0].remove();
        }
    </script>
</body>

</html>