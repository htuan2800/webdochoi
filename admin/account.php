<?php
require('inc/essentials.php');
require('inc/db_config.php');
adminLogin();
if($_SESSION['mUser']['RoleID'] != 3 && $_SESSION['mUser']['RoleID'] != 5){
    echo "You are not allowed to access this page.";
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - Account</title>
    <?php require('inc/links.php'); ?>
</head>

<body class="bg-light">

    <?php require('inc/header.php'); ?>

    <div class="container-fluid" id="main-content">
        <div class="row">
            <div class="col-lg-10 ms-auto p-4 overflow-hidden">
                <h3 class="mb-4">ACCOUNT & ROLE</h3>

                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body">

                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <h5 class="card-title m-0">Account</h5>
                            <button type="button" class="btn btn-dark shadow-none btn-sm" data-bs-toggle="modal" data-bs-target="#account-s">
                                <i class="bi bi-plus-square"></i> Add
                            </button>
                        </div>

                        <div class="table-responsive-md" style="height:350px; overflow-y:scroll;">
                            <table class="table table-hover border">
                                <thead class="sticky-top">
                                    <tr class="bg-dark text-light">
                                        <th scope="col">#</th>
                                        <th scope="col">UserID</th>
                                        <th scope="col">UserName</th>
                                        <th scope="col">RoleID</th>
                                        <th scope="col">Email</th>
                                        <th scope="col">Status</th>
                                        <th scope="col">Action</th>
                                    </tr>
                                </thead>
                                <tbody id="account-data">

                                </tbody>
                            </table>
                        </div>


                    </div>
                </div>

            </div>
        </div>
    </div>

    <!-- account modal -->
    <div class="modal fade" id="account-s" data-bs-backdrop="static" data-bs-keyboard="true" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form action="" id="account_s_form">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="staticBackdropLabel">Add Account</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div id="add_account_form_data">
                            <div class='col-md-12 ps-0 mb-3'>
                                <label class='form-label'>FullName</label>
                                <input name='fullname' id='fullname' type='text' class='form-control shadow-none' required>
                            </div>

                            <!-- <div class='col-md-12 mb-3'>
                                    <label class='form-label fw-bold'>DOB</label>
                                    <input type='date' id='dob' name='dob' class='form-control shadow-none' required>
                            </div> -->

                            <div class='col-md-12 ps-0 mb-3'>
                                <input type='radio' id='male' name='gender' value='1' required>
                                  <label for='male'>MALE</label><br>
                                <input type='radio' id='female' name='gender' value='2' required>
                                  <label for='female'>FEMALE</label><br>
                            </div>

                            <div class='col-md-12 p-0 mb-3'>
                                <label class='form-label'>Email</label>
                                <input name='email' id='email' type='email' class='form-control shadow-none' required>
                            </div>

                            <div class='col-md-12 ps-0 mb-3'>
                                <label class='form-label'>Phone Number</label>
                                <input name='phonenum' id='phonenum' type='number' class='form-control shadow-none' required>
                            </div>

                            <div class='col-md-12 ps-0 mb-3'>
                                <label class='form-label'>UserName</label>
                                <input name='username' id='username' type='text' class='form-control shadow-none' required>
                            </div>

                            <div class='col-md-12 p-0 mb-3'>
                                <label class='form-label'>Address</label>
                                <textarea name='address' id='address' class='form-control shadow-none' rows='1' required></textarea>
                            </div>

                            <div class='col-md-12 ps-0 mb-3 tpass'>
                                <label class='form-label'>Password</label>
                                <input id='pass' name='pass' id='pass' type='password' class='form-control shadow-none' required>
                            </div>

                            <div class='col-md-12 p-0 mb-3 tcpass'>
                                <label class='form-label'>Confirm Password</label>
                                <input id='cpass' name='cpass' id='cpass' type='password' class='form-control shadow-none' required>
                            </div>
                            <div class='col-md-12 p-0 mb-3 tcpass'>
                                <label class='form-label'>Role</label>
                                <select class='form-control shadow-none' name='roles' id='roles'>
                                    <option value=0>Chọn Quyền</option>
                                    <?php
                                    $res = selectAll('role');
                                    while ($row = mysqli_fetch_assoc($res)) {
                                        echo "<option value='$row[RoleID]'>$row[RoleName]</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="reset" class="btn text-secondary shadow-none" data-bs-dismiss="modal">CANCEL</button>
                        <button type="submit" class="btn custom-bg text-white shadow-none">SUBMIT</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Edit acc modal -->
    <div class="modal fade" id="edit-acc" data-bs-backdrop="static" data-bs-keyboard="true" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <form action="" id="edit_acc_form" autocomplete="off">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h1 class="modal-title fs-5" id="staticBackdropLabel">Edit Account</h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold">UserName</label>
                                    <input type="text" id="username" name="username" class="form-control shadow-none" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold">FullName</label>
                                    <input type="text" id="fullname" name="fullname" class="form-control shadow-none">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold">Address</label>
                                    <input type="text" id="address" name="address" class="form-control shadow-none">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold">Email</label>
                                    <input id="email" type="text" name="email" class="form-control shadow-none" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold">Phone</label>
                                    <input id="phone" type="text" name="phone" class="form-control shadow-none" required>
                                </div>
                                <div class='col-md-6 p-0 mb-3 tcpass'>
                                <label class='form-label'>Role</label>
                                <select class='form-control shadow-none' name='roles' id='roles'>
                                    <option value=0>Chọn Quyền</option>
                                    <?php
                                    $res = selectAll('role');
                                    while ($row = mysqli_fetch_assoc($res)) {
                                        echo "<option value='$row[RoleID]'>$row[RoleName]</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                                <input type="hidden" name="user_id">
                            </div>

                        </div>
                        <div class="modal-footer">
                            <button type="reset" class="btn text-secondary shadow-none" data-bs-dismiss="modal">CANCEL</button>
                            <button type="submit" class="btn custom-bg text-white shadow-none">SUBMIT</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

    <?php require('inc/scripts.php'); ?>

    <script src="scripts/account.js"></script>
</body>

</html>