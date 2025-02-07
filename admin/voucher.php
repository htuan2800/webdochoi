<?php
require('inc/essentials.php');
require('inc/db_config.php');
adminLogin();
if($_SESSION['mUser']['RoleID'] != 4 && $_SESSION['mUser']['RoleID'] != 5){
    echo "You are not allowed to access this page.";
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - products</title>
    <?php require('inc/links.php'); ?>
</head>

<body class="bg-light">

    <?php require('inc/header.php'); ?>

    <div class="container-fluid" id="main-content">
        <div class="row">
            <div class="col-lg-10 ms-auto p-4 overflow-hidden">
                <h3 class="mb-4">Vouchers</h3>

                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body">

                        <div class="text-end mb-4">
                            <button type="button" class="btn btn-dark shadow-none btn-sm" data-bs-toggle="modal" data-bs-target="#add-voucher">
                                <i class="bi bi-plus-square"></i> Add
                            </button>
                        </div>


                    </div>
                </div>
                <div class="table-responsive-md" style="height:450px; overflow-y:scroll;">
                    <table class="table table-hover border text-cennter">
                        <thead class="sticky-top">
                            <tr class="bg-dark text-light">
                                <th scope="col">VoucherID</th>
                                <th scope="col">VoucherCode</th>
                                <th scope="col">Condition</th>
                                <th scope="col">Desc</th>
                                <th scope="col">CartValue</th>
                                <th scope="col">DiscountValue</th>
                                <th scope="col">UsageLimit</th>
                                <th scope="col">UsageCount</th>
                                <th scope="col">StatusUsage</th>
                                <th scope="col">StatusCode</th>
                                <th scope="col">Action</th>
                            </tr>
                        </thead>
                        <tbody id="voucher-data">

                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>

    <!-- category modal -->
    <div class="modal fade" id="add-voucher" data-bs-backdrop="static" data-bs-keyboard="true" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <form action="" id="add_voucher_form" autocomplete="off">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="staticBackdropLabel">Add Voucher</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">VoucherCode</label>
                                <input type="text" name="vouchername" class="form-control shadow-none" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">CartValue</label>
                                <input type="text" name="cartvalue" class="form-control shadow-none" required>
                            </div>
                            <div id="select_supplier" class="mb-3 col-md-12">
                                <select name='voucherType' id='voucherType' class="form-select form-select-sm">
                                    <option value=0>Chọn loại Voucher</option>
                                    <option value=1>Giảm theo tiền</option>
                                    <option value=2>Giảm theo %</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Desc</label>
                                <input type="text" min="1" name="description" class="form-control shadow-none" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">DisValue</label>
                                <input type="number" min="1" name="discountValue" class="form-control shadow-none" required>
                            </div>
                            <div class="col-12 mb-3">
                                <label class="form-label fw-bold">UsageLimit</label>
                                <input type="number" min="1" name="UsageLimit" class="form-control shadow-none" required>
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


    <!-- Detail voucher modal -->
    <div class="modal fade" id="detail-voucher" data-bs-backdrop="static" data-bs-keyboard="true" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="staticBackdropLabel">Voucher Detail</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="card-body">
                        <div class="table-responsive-md" style="height:450px; overflow-y:scroll;">
                            <table class="table table-hover border text-cennter">
                                <thead class="sticky-top">
                                    <tr class="bg-dark text-light">
                                        <th scope="col">#</th>
                                        <th scope="col">UserID</th>
                                        <th scope="col">BillID</th>
                                    </tr>
                                </thead>
                                <tbody id="voucherdetail-data">

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">

                </div>
            </div>
        </div>
    </div>


    <?php require('inc/scripts.php'); ?>

    <script src="scripts/voucher.js"></script>
</body>

</html>