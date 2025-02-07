<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    <?php require('inc/links.php'); ?>
    <title>MYKINGDOM - HOME</title>
    <style>
        .availability-form {
            margin-top: -50px;
            z-index: 2;
            position: relative;
        }

        @media screen and (max-width:575px) {
            .availability-form {
                margin-top: 0px;
                padding: 0 35px;
            }
        }
    </style>
</head>

<body class="bg-light">

    <?php require('inc/header.php'); ?>
    <!-- Carousel -->

    <div class="container-fluid px-lg-4 mt-4">
        <div class="swiper swiper-container">
            <div class="swiper-wrapper">
                <?php
                $res = selectAll('carousel');
                while ($row = mysqli_fetch_assoc($res)) {
                    $path = CAROUSEL_IMG_PATH;
                    echo <<<data
                        <div class="swiper-slide">
                            <img src="$path$row[image]" class="w-100 d-block" />
                        </div>
                    data;
                }
                ?>
            </div>
        </div>
    </div>

    <!-- Top Sales  -->
    <h2 class="mt-5 pt-4 mb-4 text-center fw-bold h-font">TOP SALES</h2>

    <div class="container">
        <div class="row">
            <?php
            $product_res = mysqli_query($con, "SELECT 
                p.*,
                br.BrandName,
                br.Status as StatusBrand,
                ca.TypeName,
                ca.Status as StatusCategory,
                SUM(bd.Quantity) AS TotalQuantitySold
            FROM 
                billdetail bd
            JOIN 
                bill b ON bd.BillID = b.BillID
            JOIN 
                product p ON bd.ProductID=p.ProductID
            JOIN
                brand br ON br.BrandID=p.BrandID
            JOIN
                category ca ON ca.TypeID=p.TypeID
            WHERE 
                b.status = 'Đã Nhận Hàng' AND p.status=0 AND P.deleted=0
            GROUP BY 
                bd.ProductID
            ORDER BY 
                TotalQuantitySold DESC
            LIMIT 3");

            while ($product_data = mysqli_fetch_assoc($product_res)) {
                    $category_data="";
                    if ($product_data['StatusCategory'] == 1) {
                        $category_data .= "<span class='badge rounded-pill bg-light text-dark text-wrap me-1 mb-1'>
                                Tạm ẩn
                        </span>";
                    } else {
                        $category_data .= "<span class='badge rounded-pill bg-light text-dark text-wrap me-1 mb-1'>
                                 $product_data[TypeName]
                                </span>";
                    }
                

                //get producer of product
                $fac_q = mysqli_query($con, "SELECT f.BrandName, f.Status FROM `brand` f 
                        INNER JOIN `product` rfac ON f.BrandID=rfac.BrandID
                        WHERE rfac.ProductID='$product_data[ProductID]'");

                $producer_data = "";
                    if ($product_data['StatusBrand'] == 1) {
                        $producer_data .= "<span class='badge rounded-pill bg-light text-dark text-wrap me-1 mb-1'>
                                Tạm ẩn
                            </span>";
                    } else {
                        $producer_data .= "<span class='badge rounded-pill bg-light text-dark text-wrap me-1 mb-1'>
                                 $product_data[BrandName]
                                </span>";
                    }
                

                // $imgBase64 = base64_encode($product_data['IMG']);
                // // Tạo đường dẫn dữ liệu (data URL) cho thẻ <img>
                // $imgSrc = 'data:image/jpeg;base64,' . $imgBase64;
                $formatted_price = number_format($product_data['ProductPrice'], 0, ',', '.') . ' VNĐ';

                $formatted_img='';
                if($product_data['IMG'] == null){
                    $formatted_img='images/products/thumbnail.jpg';
                }else {
                    $formatted_img=$product_data['IMG'];
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

    <!-- Our Products  -->
    <h2 class="mt-5 pt-4 mb-4 text-center fw-bold h-font">OUR PRODUCTS</h2>

    <div class="container">
        <div class="row">
            <?php
            $product_res = select("SELECT * FROM `product` WHERE `status`=? and `deleted`=? ORDER BY `ProductID` DESC LIMIT 6", ['0', '0'], 'ss');

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

                // $imgBase64 = base64_encode($product_data['IMG']);
                // // Tạo đường dẫn dữ liệu (data URL) cho thẻ <img>
                // $imgSrc = 'data:image/jpeg;base64,' . $imgBase64;
                $formatted_price = number_format($product_data['ProductPrice'], 0, ',', '.') . ' VNĐ';

                $formatted_img='';
                if($product_data['IMG'] == null){
                    $formatted_img='images/products/thumbnail.jpg';
                }else {
                    $formatted_img=$product_data['IMG'];
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
            <div class="col-lg-12 text-center mt-5">
                <a href="products.php" class="btn btn-sm btn-outline-dark rounded-0 fw-bold shadow-none">More Products >>></a>
            </div>
        </div>
    </div>

    <!-- Our Producer -->
    <h2 class="mt-5 pt-4 mb-4 text-center fw-bold h-font">OUR BRAND</h2>
    <div class="container">
        <div class="row justify-content-evenly px-lg-0 px-md-0 px-5">
            <?php
            $res = mysqli_query($con, "SELECT * FROM `brand` where `Status`=0 ORDER BY `BrandID` DESC  LIMIT 5");
            // $path = FACILITIES_IMG_PATH;
            while ($row = mysqli_fetch_assoc($res)) {
                echo <<<data
                        <div class="col-lg-2 col-md-2 text-center bg-white rounded shadow py-4 my-3">
                            <h5 class="mt-3">$row[BrandName]</h5>
                        </div>
                data;
            }
            ?>
            <div class="col-lg-12 text-center mt-5">
                <a href="producer.php" class="btn btn-sm btn-outline-dark rounded-0 fw-bold shadow-none">More Brands >>></a>
            </div>
        </div>
    </div>

    <!-- Reach Us -->

    <h2 class="mt-5 pt-4 mb-4 text-center fw-bold h-font">REACH US</h2>
    <div class="container">
        <div class="row">
            <div class="col-lg-8 col-md-8 p-4 mb-lg-0 mb-3 bg-white rounded">
                <iframe class="w-100 rounded mb-4" src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3919.6697269761835!2d106.67968337488225!3d10.759917089387907!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x31752f1b7c3ed289%3A0xa06651894598e488!2sSaigon%20University!5e0!3m2!1sen!2s!4v1713673191021!5m2!1sen!2s" height="320" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
            </div>
            <div class="col-lg-4 col-md-4">
                <div class="bg-white p-4 rounded mb-4">
                    <h5>Call us</h5>
                    <a href="tel: +88888888" class="d-inline-block mb-2 text-decoration-none text-dark">
                        <i class="bi bi-telephone-fill"></i> +88888888
                    </a>
                    <br>
                    <a href="tel: +7777777" class="d-inline-block text-decoration-none text-dark">
                        <i class="bi bi-telephone-fill"></i> +7777777
                    </a>
                </div>
                <div class="bg-white p-4 rounded mb-4">
                    <h5>Follow us</h5>
                    <a href="https://x.com/" class="d-inline-block mb-3">
                        <span class="badge bg-light text-dark fs-6 p-2">
                            <i class="bi bi-twitter-x"></i> Twitter
                        </span>
                    </a>
                    <a href="https://www.facebook.com/" class="d-inline-block mb-3">
                        <span class="badge bg-light text-dark fs-6 p-2">
                            <i class="bi bi-facebook"></i> Facebook
                        </span>
                    </a>
                    <br>
                    <a href="https://www.instagram.com/" class="d-inline-block">
                        <span class="badge bg-light text-dark fs-6 p-2">
                            <i class="bi bi-instagram"></i> Instagram
                        </span>
                    </a>
                </div>
            </div>
        </div>
    </div>


    <?php require('inc/footer.php'); ?>



    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

    <!-- Initialize Swiper -->
    <script>
        var swiper = new Swiper(".swiper-container", {
            spaceBetween: 30,
            effect: "fade",
            loop: true,
            autoplay: {
                delay: 3000,
                disableOnInteraction: false,
            }
        });


        var swiper = new Swiper(".swiper-testimonials", {
            effect: "coverflow",
            grabCursor: true,
            centeredSlides: true,
            slidesPerView: "auto",
            slidesPerView: "3",
            loop: true,
            coverflowEffect: {
                rotate: 50,
                stretch: 0,
                depth: 100,
                modifier: 1,
                slideShadows: false,
            },
            pagination: {
                el: ".swiper-pagination",
            },
            breakpoints: {
                320: {
                    slidesPerView: 1,
                },
                640: {
                    slidesPerView: 1,
                },
                768: {
                    slidesPerView: 2,
                },
                1024: {
                    slidesPerView: 3,
                }
            }
        });
    </script>
</body>

</html>