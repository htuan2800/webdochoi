<?php
require('inc/essentials.php');
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
    <title>Admin Panel - Carousel</title>
    <?php require('inc/links.php'); ?>
</head>

<body class="bg-light">

    <?php require('inc/header.php'); ?>

    <div class="container-fluid" id="main-content">
        <div class="row">
            <div class="col-lg-10 ms-auto p-4 overflow-hidden">
                <h3 class="mb-4">Supplier</h3>
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body">

                        <div class="text-end mb-4">
                            <button type="button" class="btn btn-dark shadow-none btn-sm" data-bs-toggle="modal" data-bs-target="#add-supplier">
                                <i class="bi bi-plus-square"></i> Add
                            </button>
                        </div>


                    </div>
                </div>
                <div class="d-flex align-items-center gap-2">
                    <select class="form-select form-select-sm" aria-label="selectStatus" onchange="applyAllFilters()">
                        <option selected>---Tình trạng----</option>
                        <option value="0">Đang hoạt động</option>
                        <option value="1">Đã ẩn</option>
                    </select>
                    <input type="text" oninput="applyAllFilters()" class="form-control shadow-none w-25" id="myInput" placeholder="Type to search.....">
                </div>
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body">
                        <div class="table-responsive-md" style="height:450px; overflow-y:scroll;">
                            <table class="table table-hover border text-cennter">
                                <thead class="sticky-top">
                                    <tr class="bg-dark text-light">
                                        <th scope="col">SupplierID</th>
                                        <th scope="col">SupplierName</th>
                                        <th scope="col">Address</th>
                                        <th scope="col">Phone</th>
                                        <th scope="col">FaxNumber</th>
                                        <th scope="col">Status</th>
                                        <th scope="col">Action</th>
                                    </tr>
                                </thead>
                                <tbody id="supplier-data">

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>


    <div class="modal fade" id="add-supplier" data-bs-backdrop="static" data-bs-keyboard="true" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <form action="" id="add_supplier_form" autocomplete="off">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="staticBackdropLabel">Add supplier</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">supplierName</label>
                                <input type="text" name="suppliername" class="form-control shadow-none" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Address</label>
                                <input type="text" name="address" class="form-control shadow-none" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Phone</label>
                                <input id="phone" type="number" onblur="regex()" name="phone" class="form-control shadow-none" required>
                                <p id="p_error" style="display:none; color:red;">Điện Thoại không hợp lệ</p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">FaxNumber</label>
                                <input id="faxnumber" type="number" name="faxnumber" class="form-control shadow-none" required>
                                <p id="f_error" style="display:none;"></p>
                            </div>
                            <input type="hidden" name="add_supplier">
                        </div>
                        <div class="modal-footer">
                            <button type="reset" class="btn text-secondary shadow-none" data-bs-dismiss="modal">CANCEL</button>
                            <button type="submit" class="btn custom-bg text-white shadow-none">SUBMIT</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="modal fade" id="edit-supplier" data-bs-backdrop="static" data-bs-keyboard="true" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <form action="" id="edit_supplier_form" autocomplete="off">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="staticBackdropLabel">Edit supplier</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">supplierName</label>
                                <input type="text" name="suppliername" class="form-control shadow-none" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Address</label>
                                <input type="text" name="address" class="form-control shadow-none" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Phone</label>
                                <input id="phone" type="number" onblur="regex()" name="phone" class="form-control shadow-none" required>
                                <p id="p_error" style="display:none; color:red;">Điện Thoại không hợp lệ</p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">FaxNumber</label>
                                <input id="faxnumber" type="number" name="faxnumber" class="form-control shadow-none" required>
                                <p id="f_error" style="display:none;"></p>
                            </div>
                            <input type="hidden" name="supplierID" id="supplierID">
                        </div>
                        <div class="modal-footer">
                            <button type="reset" class="btn text-secondary shadow-none" data-bs-dismiss="modal">CANCEL</button>
                            <button type="submit" class="btn custom-bg text-white shadow-none">SUBMIT</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="modal fade" id="detail-supplier" data-bs-backdrop="static" data-bs-keyboard="true" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="staticBackdropLabel">Detail supplier</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="card border-0 shadow-sm mb-4">
                            <div class="card-body">
                                <div class="table-responsive-md" style="height:250px; overflow-y:scroll;">
                                    <table class="table table-hover border text-cennter">
                                        <thead class="sticky-top">
                                            <tr class="bg-dark text-light">
                                                <th scope="col">Tick</th>
                                                <th scope="col">ProductID</th>
                                                <th scope="col">ProductName</th>
                                            </tr>
                                        </thead>
                                        <tbody id="supplierdt-data">

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <input type="hidden" name="detail_supplier">
                    </div>
                </div>
            </div>

        </div>
    </div>
    <?php require('inc/scripts.php'); ?>
    <script src="scripts/supplier.js"></script>
</body>

</html>