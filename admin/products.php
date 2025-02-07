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
                <h3 class="mb-4">products</h3>

                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body">

                        <div class="text-end mb-4">
                            <button type="button" class="btn btn-dark shadow-none btn-sm" data-bs-toggle="modal" data-bs-target="#add-product">
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
                    <select class="form-select form-select-sm" aria-label="SelectQuantity" onchange="applyAllFilters()">
                        <option selected>---Tồn kho----</option>
                        <option value="0">Thấp đến cao</option>
                        <option value="1">Cao đến thấp</option>
                    </select>
                    <input type="text" oninput="applyAllFilters()" class="form-control shadow-none w-25" id="myInput" placeholder="Type to search.....">
                </div>
                <div class="table-responsive-md" style="height:450px; overflow-y:scroll;">
                            <table class="table table-hover border text-cennter">
                                <thead class="sticky-top">
                                    <tr class="bg-dark text-light">
                                        <th scope="col">#</th>
                                        <th scope="col">TypeID</th>
                                        <th scope="col">ProductName</th>
                                        <th scope="col">Quantity</th>
                                        <th scope="col">Price</th>
                                        <th scope="col">BrandID</th>
                                        <th scope="col">Description</th>
                                        <th scope="col">Status</th>
                                    </tr>
                                </thead>
                                <tbody id="product-data">

                                </tbody>
                            </table>
                </div>

            </div>
        </div>
    </div>

    <div class="modal fade" id="add-product" data-bs-backdrop="static" data-bs-keyboard="true" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <form action="" id="add_product_form" autocomplete="off">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="staticBackdropLabel">Add product</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">ProductName</label>
                                <input type="text" name="productname" class="form-control shadow-none" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Price</label>
                                <input type="number" min="1" name="productprice" class="form-control shadow-none" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Age</label>
                                <input type="text" min="1" name="age" class="form-control shadow-none" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Origin</label>
                                <input type="text" min="1" name="origin" class="form-control shadow-none" required>
                            </div>
                            <div class="col-md-12 mb-3">
                                <label class="form-label fw-bold">Gender</label>
                                <select name="gender" id="gender" class="form-control shadow-none">
                                    <option value="Boy" selected>Boy</option>
                                    <option value="Girl">Girl</option>
                                    <option value="Unisex">Unisex</option>
                                </select>
                            </div>
                            
                            <div class="col-12 mb-3">
                                <label class="form-label fw-bold">Categories</label>
                                <div class="row">
                                    <?php
                                    $res = selectAll('category');
                                    while ($opt = mysqli_fetch_assoc($res)) {
                                        if($opt['Status'] == 1){
                                            echo "
                                                <div class='col-md-3'>
                                                <label>
                                                    <input type='radio' name='category' value='$opt[TypeID]' class='form-check-input shadow-none' disabled>
                                                    $opt[TypeName]
                                                </label>
                                                </div>
                                                ";
                                        }
                                        else {
                                            echo "
                                                <div class='col-md-3'>
                                                <label>
                                                    <input type='radio' name='category' value='$opt[TypeID]' class='form-check-input shadow-none'>
                                                    $opt[TypeName]
                                                </label>
                                                </div>
                                                ";
                                        }
                                    }
                                    ?>
                                </div>
                            </div>
                            <div class="col-12 mb-3">
                                <label class="form-label fw-bold">Brand</label>
                                <div class="row">
                                    <?php
                                    $res = selectAll('brand');
                                    while ($opt = mysqli_fetch_assoc($res)) {
                                        if($opt['Status'] == 1){
                                            echo "
                                                <div class='col-md-3'>
                                                <label>
                                                    <input type='radio' name='brand' value='$opt[BrandID]' class='form-check-input shadow-none' disabled>
                                                    $opt[BrandName]
                                                </label>
                                                </div>
                                                ";
                                        }
                                        else {
                                            echo "
                                                <div class='col-md-3'>
                                                <label>
                                                    <input type='radio' name='brand' value='$opt[BrandID]' class='form-check-input shadow-none'>
                                                    $opt[BrandName]
                                                </label>
                                                </div>
                                                ";
                                        }
                                    }
                                    ?>
                                </div>
                            </div>
                            <div class="col-12 mb-3">
                                <label class="form-label fw-bold">Description</label>
                                <textarea name="desc" rows="4" class="form-control shadow-none" required></textarea>
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

    <!-- Edit product modal -->
    <div class="modal fade" id="edit-product" data-bs-backdrop="static" data-bs-keyboard="true" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <form action="" id="edit_product_form" autocomplete="off">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="staticBackdropLabel">Edit product</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">ProductName</label>
                                <input type="text" name="productname" class="form-control shadow-none" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Price</label>
                                <input type="number" min="1" name="productprice" class="form-control shadow-none" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Age</label>
                                <input type="text" name="age" class="form-control shadow-none" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Origin</label>
                                <input type="text" name="origin" class="form-control shadow-none" required>
                            </div>
                            <div class="col-md-12 mb-3">
                                <label class="form-label fw-bold">Gender</label>
                                <select name="gender" id="gender" class="form-control shadow-none">
                                    <option value="Boy" selected>Boy</option>
                                    <option value="Girl">Girl</option>
                                    <option value="Unisex">Unisex</option>
                                </select>
                            </div>
                            <div class="col-12 mb-3">
                                <label class="form-label fw-bold">category</label>
                                <div class="row">
                                    <?php
                                    $res = selectAll('category');
                                    while ($opt = mysqli_fetch_assoc($res)) {
                                        if($opt['Status'] == 1){
                                            echo "
                                                <div class='col-md-3'>
                                                <label>
                                                    <input type='radio' name='categories' value='$opt[TypeID]' class='form-check-input shadow-none' disabled>
                                                    $opt[TypeName]
                                                </label>
                                                </div>
                                                ";
                                        }
                                        else {
                                            echo "
                                                <div class='col-md-3'>
                                                <label>
                                                    <input type='radio' name='categories' value='$opt[TypeID]' class='form-check-input shadow-none'>
                                                    $opt[TypeName]
                                                </label>
                                                </div>
                                                ";
                                        }
                                    }
                                    ?>
                                </div>
                            </div>
                            <div class="col-12 mb-3">
                                <label class="form-label fw-bold">Brand</label>
                                <div class="row">
                                    <?php
                                    $res = selectAll('brand');
                                    while ($opt = mysqli_fetch_assoc($res)) {
                                        if($opt['Status'] == 1){
                                            echo "
                                                <div class='col-md-3'>
                                                <label>
                                                    <input type='radio' name='brand' value='$opt[BrandID]' class='form-check-input shadow-none' disabled>
                                                    $opt[BrandName]
                                                </label>
                                                </div>
                                                ";
                                        }
                                        else {
                                            echo "
                                                <div class='col-md-3'>
                                                <label>
                                                    <input type='radio' name='brand' value='$opt[BrandID]' class='form-check-input shadow-none'>
                                                    $opt[BrandName]
                                                </label>
                                                </div>
                                                ";
                                        }
                                    }
                                    ?>
                                </div>
                            </div>
                            <div class="col-12 mb-3">
                                <label class="form-label fw-bold">Description</label>
                                <textarea name="desc" rows="4" class="form-control shadow-none" required></textarea>
                            </div>
                            <input type="hidden" name="product_id">
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

    <!-- Manage product images -->
    <div class="modal fade" id="product-images" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"></h5>
                    <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="image-alert"></div>
                    <div class="border-bottom border-3 pb-3 mb-3">
                        <form id="add_image_form">
                            <label class="form-label fw-bold">Add Image</label>
                            <input type="file" name="image" accept=".jpg, .png, .webp, .jpeg" class="form-control shadow-none mb-3" required>
                            <button class="btn custom-bg text-white shadow-none">ADD</button>
                            <input type="hidden" name="product_id">
                        </form>
                    </div>
                </div>
                <div class="table-responsive-md" style="height:350px; overflow-y:scroll;">
                    <table class="table table-hover border text-cennter">
                        <thead class="sticky-top">
                            <tr class="bg-dark text-light sticky-top">
                                <th scope="col" width="60%">Image</th>
                                <th scope="col">Delete</th>
                            </tr>
                        </thead>
                        <tbody id="product-image-data">

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <?php require('inc/scripts.php'); ?>

    <script src="scripts/products.js"></script>
</body>

</html>