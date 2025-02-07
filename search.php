<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    <?php require('inc/links.php'); ?>
    <title>MYKINGDOM - ROOM DETAILS</title>

    <style>
        input.empty {
            border-color: red;
        }
    </style>
</head>

<body class="bg-light">

    <?php require('inc/header.php'); ?>
    <?php
    $data = filteration($_GET);
    $param = $data['nameP'] . '%';
    ?>
    <div class="container">
        <div class="row">
            <?php
            $product_res = select("SELECT f.* FROM `product` f INNER JOIN `brand` rfea ON f.BrandID=rfea.BrandID WHERE (f.ProductName LIKE ? OR rfea.BrandName LIKE ?)  and f.status=0 and f.deleted=0", ["%".$param."%",$param], 'ss');

            while ($product_data = mysqli_fetch_assoc($product_res)) {
                //get features of product
                $fea_q = mysqli_query($con, "SELECT f.TypeName, f.Status FROM `category` f
                            INNER JOIN `product` rfea ON f.TypeID=rfea.TypeID
                            WHERE rfea.ProductID='$product_data[ProductID]'");

                $category_data = "";
                while ($fea_row = mysqli_fetch_assoc($fea_q)) {
                    if ($fea_row['Status'] == 1) {
                        $category_data .= "<span class='badge rounded-pill bg-light text-dark text-wrap me-1 mb-1'>
                                Tạm ẩn
                        </span>";
                    } else {
                        $category_data .= "<span class='badge rounded-pill bg-light text-dark text-wrap me-1 mb-1'>
                                 $fea_row[TypeName]
                                </span>";
                    }
                }

                //get producer of product
                $fac_q = mysqli_query($con, "SELECT f.BrandName, f.Status FROM `brand` f 
                        INNER JOIN `product` rfac ON f.BrandID=rfac.BrandID
                        WHERE rfac.ProductID='$product_data[ProductID]'");

                $producer_data = "";
                while ($fac_row = mysqli_fetch_assoc($fac_q)) {
                    if ($fac_row['Status'] == 1) {
                        $producer_data .= "<span class='badge rounded-pill bg-light text-dark text-wrap me-1 mb-1'>
                                Tạm ẩn
                            </span>";
                    } else {
                        $producer_data .= "<span class='badge rounded-pill bg-light text-dark text-wrap me-1 mb-1'>
                                 $fac_row[BrandName]
                                </span>";
                    }
                }

                $formatted_price = number_format($product_data['ProductPrice'], 0, ',', '.') . ' VNĐ';
                $formatted_img = '';
                if ($product_data['IMG'] == null) {
                    $formatted_img = 'images/products/thumbnail.jpg';
                } else {
                    $formatted_img = $product_data['IMG'];
                }

                //print room card
                echo <<< data
                        <div class="col-lg-4 col-md-6 my-3">

                        <div class="card border-0 shadow" style="max-width:350px; margin:auto;">
                            <img src="./admin/$formatted_img" class="card-img-top">
                            <div class="card-body">
                                <h5>$product_data[ProductName]</h5>
                                <h6 class="mb-4">$formatted_price</h6>
                                <div class="category mb-4">
                                    <h6 class="mb-1">Category</h6>
                                    $category_data
                                </div>
                                <div class="producer mb-4">
                                    <h6 class="mb-1">Brand</h6>
                                    $producer_data
                                </div>
                                <div class="other mb-4">
                                    <h6 class="mb-1">Other</h6>
                                    <span class="badge rounded-pill bg-light text-dark text-wrap">
                                        $product_data[Age]
                                    </span>
                                    <span class="badge rounded-pill bg-light text-dark text-wrap">
                                        $product_data[Description]
                                    </span>
                                </div>
                                <div class="d-flex justify-content-evenly mb-2">
                                    <a href="product_details.php?id=$product_data[ProductID]" class="btn btn-sm btn-outline-dark shadow-none">More details</a>
                                </div>
                            </div>
                        </div>
                    </div>

            data;
            }
            ?>
        </div>
    </div>




    <?php require('inc/footer.php'); ?>

    <script>

    </script>
</body>

</html>