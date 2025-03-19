<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    <?php require('inc/links.php'); ?>
    <title>MYKINGDOM - PRODUCT DETAILS</title>

    <style>
        input.empty {
            border-color: red;
        }
    </style>
</head>

<body class="bg-light">

    <?php require('inc/header.php'); ?>
    <?php
    if (!isset($_GET['id'])) {
        redirect('cart.php');
    }
    $data = filteration($_GET);
    $product_res = select("SELECT * FROM `product` WHERE `ProductID`=? AND `status`=?", [$data['id'], 'acti'], 'is');

    if (mysqli_num_rows($product_res) == 0) {
        redirect('cart.php');
    }

    $product_data = mysqli_fetch_assoc($product_res);
    ?>


    <div class="container">
        <div class="col-12 my-5 px-4">
            <div style="font-size:14px;">
                <a href="index.php" class="text-secondary tex-decoration-none">HOME</a>
                <span class="text-secondary"> > </span>
                <a href="cart.php" class="text-secondary tex-decoration-none">CART</a>
            </div>
        </div>
        <div id="showProduct">
            <?php
            //get features of product
            $fea_q = mysqli_query($con, "SELECT f.TypeName, f.Status FROM `category` f
                            INNER JOIN `product` rfea ON f.TypeID=rfea.TypeID
                            WHERE rfea.ProductID='$product_data[ProductID]'");

            $category_data = "";
            while ($fea_row = mysqli_fetch_assoc($fea_q)) {
                if ($fea_row['Status'] == 1) {
                    $category_data .= "Tạm ẩn";
                } else {
                    $category_data .= "$fea_row[TypeName]";
                }
            }

            //get producer of product
            $fac_q = mysqli_query($con, "SELECT f.BrandName, f.Status FROM `brand` f 
                        INNER JOIN `product` rfac ON f.BrandID=rfac.BrandID
                        WHERE rfac.ProductID='$product_data[ProductID]'");

            $producer_data = "";
            while ($fac_row = mysqli_fetch_assoc($fac_q)) {
                if ($fac_row['Status'] == 1) {
                    $producer_data .= "Tạm ẩn";
                } else {
                    $producer_data .= "$fac_row[BrandName] ";
                }
            }

            $inventory_data = "";
            if ($product_data['Quantity'] == 0) {
                $inventory_data .= "<h6 class='mb-4'>Hàng Sắp Về!</h6>";
            } else {
                $inventory_data .= "<h6 class='mb-4 text-success'>Còn $product_data[Quantity] Sản Phẩm</h6>";
            }

            $cart_btn = "";

            if ($product_data['Quantity'] != 0) {
                $cart_btn = "<button type='submit' class='btn btn-sm text-white custom-bg shadow-none'>Add To Cart</button>";
            } else {
                $cart_btn = "";
            }


            $quantity_form = "";
            if ($product_data['Quantity'] == 0) {
                $quantity_form = "";
            } else {
                $quantity_form = '
                                    <h6 class="mb-1">Quantity</h6>
                                    <input onblur="checkEmptyProductOrder()" type="number" min="1" max="10000" value="1" name="Quantity" id="p-quantity">
                ';
            }

            $formatted_img = '';
            if ($product_data['IMG'] == null) {
                $formatted_img = 'images/products/thumbnail.jpg';
            } else {
                $formatted_img = $product_data['IMG'];
            }
            //print room card
            $price = $product_data['ProductPrice'];
            $formatted_price = number_format($price, 0, ',', '.') . ' VNĐ';
            echo <<< data
                    <form action="" id="add-cart" class="d-flex">
                        <input type="hidden" name="ProductID" value="$product_data[ProductID]" />
                        <input type="hidden" name="ProductName" value="$product_data[ProductName]" />
                        <input type="hidden" name="inventory" value="$product_data[Quantity]" />
                        <input type="hidden" name="ProductPrice" value="$price" />
                        <img src="./admin/$formatted_img" class="card-img-top" style="width:40%;">
                        <div class="" style="max-width:350px; margin-left:100px;">
                            <div class="">
                                <h5>$product_data[ProductName]</h5>
                                <h6 class="mb-4">$formatted_price</h6>
                                <div class="producer mb-4">
                                   $inventory_data
                                </div>
                                <div class="category mb-4">
                                    <h6 class="mb-1">Category: $category_data</h6>
                                </div>
                                <div class="producer mb-4">
                                    <h6 class="mb-1">Brand: $producer_data</h6>
                                </div>
                                <div class="other mb-4">
                                    <h6 class="mb-4">Age: $product_data[Age]</h6>
                                    <h6 class="mb-4">Gender: $product_data[Gender]</h6>
                                    <h6 class="mb-4">Origin: $product_data[Origin]</h6>
                                    <h6 class="mb-1">Description: $product_data[Description]</h6>
                                </div>
                                <div class="producer mb-4">
                                    $quantity_form
                                </div>
                                <div class="">
                                    $cart_btn
                                </div>
                            </div>
                        </div>

                        </form>


                    data;

            ?>
        </div>
    </div>
    <?php require('inc/footer.php'); ?>

    <script>
        function checkEmptyProductOrder() {
            var input = document.getElementById('p-quantity');
            if (input.value === "" || input.value == 0) {
                input.value = 1;
            }
        }

        let ad_pr = document.getElementById('add-cart');
        ad_pr.addEventListener('submit', (e) => {
            e.preventDefault();
            if (Number(ad_pr.elements['Quantity'].value) > Number(ad_pr.elements['inventory'].value)) {
                toast('warning', "Kho không đủ số lượng bạn yêu cầu");
                return;
            }
            let data = new FormData();
            data.append('ProductID', ad_pr.elements['ProductID'].value);
            data.append('ProductName', ad_pr.elements['ProductName'].value);
            data.append('ProductPrice', ad_pr.elements['ProductPrice'].value);
            data.append('inventory', ad_pr.elements['inventory'].value);
            data.append('Quantity', ad_pr.elements['Quantity'].value);
            data.append('add_to_cart', '');

            let xhr = new XMLHttpRequest();
            xhr.open("POST", "ajax/cart.php", true);

            xhr.onload = function() {
                const data = JSON.parse(this.responseText);
                if (data.success == true) {
                    toast('success', data.message);

                } else if (data.success == false) {
                    toast('danger', data.message);
                }
                countCart()
            }
            xhr.send(data);
        })
    </script>
</body>

</html>