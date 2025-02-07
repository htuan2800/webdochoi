<?php
require('inc/essentials.php');
require('inc/db_config.php');
adminLogin();
if($_SESSION['mUser']['RoleID'] != 2 && $_SESSION['mUser']['RoleID'] != 5){
    echo "You are not allowed to access this page.";
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - bills</title>
    <?php require('inc/links.php'); ?>
</head>

<body class="bg-light">

    <?php require('inc/header.php'); ?>
    <div class="container-fluid" id="main-content">
        <div class="row">
            <div class="col-lg-10 ms-auto p-4 overflow-hidden">
                <h3 class="mb-4">Bills</h3>


                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body">
                        <div class="table-responsive-md" style="height:450px; overflow-y:scroll;">
                            <table class="table table-hover border text-cennter">
                                <thead class="sticky-top">
                                    <tr class="bg-dark text-light">
                                        <th scope="col">#</th>
                                        <th scope="col">BillID</th>
                                        <th scope="col">CustomerID</th>
                                        <th scope="col">CreateTime</th>
                                        <th scope="col">UpdateTime</th>
                                        <th scope="col">Total</th>
                                        <th scope="col">Address</th>
                                        <th scope="col">Payment</th>
                                        <th scope="col">Status</th>
                                        <th scope="col">Action</th>
                                    </tr>
                                </thead>
                                <tbody id="bill-data">

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>


    <!-- Edit bill modal -->
    <div class="modal fade" id="edit-bill" data-bs-backdrop="static" data-bs-keyboard="true" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <form action="" id="edit_bill_form" autocomplete="off">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="staticBackdropLabel">Edit bill</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="table-responsive-md" style="height:200px; overflow-y:scroll;">
                        <table class="table table-hover border text-cennter">
                            <thead class="sticky-top">
                                <tr class="bg-dark text-light">
                                    <th scope="col">ProductID</th>
                                    <th scope="col">ProductName</th>
                                    <th scope="col">Quantity</th>
                                    <th scope="col">Unitprice</th>
                                </tr>
                            </thead>
                            <tbody id="billdt-data">

                            </tbody>
                        </table>
                    </div>

                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <h5>TOTAL: <span id="total"></span></h5>
                            </div>

                            <div class="col-md-6 mb-3" id="select_status">
                                
                            </div>
                            <input type="hidden" name="bill_id">
                        </div>



                    </div>
                    <div class="modal-footer">
                        <button type="reset" class="btn text-secondary shadow-none" data-bs-dismiss="modal">CANCEL</button>
                        <!-- <button type="submit" class="btn custom-bg text-white shadow-none">SUBMIT</button> -->
                    </div>
                </div>
            </form>
        </div>
    </div>


    <?php require('inc/scripts.php'); ?>

    <script src="scripts/bill.js"></script>
</body>

</html>