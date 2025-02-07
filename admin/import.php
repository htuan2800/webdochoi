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
    <title>Admin Panel - Import</title>
    <?php require('inc/links.php'); ?>
</head>

<body class="bg-light">

    <?php require('inc/header.php'); ?>

    <div class="container-fluid" id="main-content">
        <div class="row">
            <div class="col-lg-10 ms-auto p-4 overflow-hidden">
                <h3 class="mb-4">Import</h3>
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body">

                        <div class="text-end mb-4">
                            <button type="button" class="btn btn-dark shadow-none btn-sm" data-bs-toggle="modal" data-bs-target="#add-import">
                                <i class="bi bi-plus-square"></i> Add
                            </button>
                        </div>


                    </div>
                </div>

                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body">
                        <div class="table-responsive-md" style="height:450px; overflow-y:scroll;">
                            <table class="table table-hover border text-cennter">
                                <thead class="sticky-top">
                                    <tr class="bg-dark text-light">
                                        <th scope="col">ImportID</th>
                                        <th scope="col">SupplierID</th>
                                        <th scope="col">CreateTime</th>
                                        <th scope="col">UpdateTime</th>
                                        <th scope="col">Total</th>
                                        <th scope="col">Status</th>
                                        <th scope="col">Action</th>
                                    </tr>
                                </thead>
                                <tbody id="import-data">

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>


    <div class="modal fade" id="add-import" data-bs-backdrop="static" data-bs-keyboard="true" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <form action="" id="add_import_form" autocomplete="off">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="staticBackdropLabel">Add Import</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div id="select_supplier">
                                <select name='suppliers' id='suppliers' onchange='select_s()'>
                                    <option value=0>Chọn nhà cung cấp</option>
                                    <?php
                                    $res = selectAll('supplier');
                                    while ($row = mysqli_fetch_assoc($res)) {
                                        echo "<option value='$row[SupplierID]'>$row[SupplierName]</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="card border-0 shadow-sm mb-4">
                                <div class="card-body">
                                    <div class="table-responsive-md" style="height:450px; overflow-y:scroll;">
                                        <table class="table table-hover border text-cennter">
                                            <thead class="sticky-top">
                                                <tr class="bg-dark text-light">
                                                    <th scope="col">Tick</th>
                                                    <th scope="col">ProductID</th>
                                                    <th scope="col">ProductName</th>
                                                    <th scope="col">UnitPrice</th>
                                                    <th scope="col">Quantity</th>
                                                </tr>
                                            </thead>
                                            <tbody id="importdt-data">

                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <input type="hidden" name="add_import">
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

    <!-- Detail import modal -->
    <div class="modal fade" id="detail-import" data-bs-backdrop="static" data-bs-keyboard="true" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="staticBackdropLabel">Import Detail</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="card-body">
                        <div class="table-responsive-md" style="height:450px; overflow-y:scroll;">
                            <table class="table table-hover border text-cennter" id="detailTable">
                                <thead class="sticky-top">
                                    <tr class="bg-dark text-light">
                                        <th scope="col">ProductID</th>
                                        <th scope="col">ProductName</th>
                                        <th scope="col">Quantity</th>
                                        <th scope="col">UnitPrice</th>
                                    </tr>
                                </thead>
                                <tbody id="importdetail-data">

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

    <!-- edit import modal -->
    <div class="modal fade" id="edit-import" data-bs-backdrop="static" data-bs-keyboard="true" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
        <form action="" id="edit_import_form" autocomplete="off">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="staticBackdropLabel">Edit Import</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="card-body">
                        <div class="table-responsive-md" style="height:450px; overflow-y:scroll;">
                            <table class="table table-hover border text-cennter" id="detailTable">
                                <thead class="sticky-top">
                                    <tr class="bg-dark text-light">
                                        <th scope="col">ProductID</th>
                                        <th scope="col">ProductName</th>
                                        <th scope="col">UnitPrice</th>
                                        <th scope="col">Quantity</th>
                                    </tr>
                                </thead>
                                <tbody id="editimport-data">

                                </tbody>
                            </table>
                        </div>
                    </div>
                    <input type="hidden" name="importID" id="importID">
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js" integrity="sha512-GsLlZN/3F2ErC5ifS5QtgpiJtWd43JWSuIgh7mbzZ8zBps+dvLusV+eNQATqgA/HdeKFVgA5v3S/cIrLF7QnIg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="scripts/import.js"></script>
</body>

</html>