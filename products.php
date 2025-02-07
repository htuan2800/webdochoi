<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    <?php require('inc/links.php'); ?>
    <title>MYKINGDOM - PRODUCTS</title>

    <style>
        ::selection {
            color: #fff;
            background: #17A2B8;
        }

        .range_price {
            width: 400px;
            border-radius: 10px;
            padding: 10px 20px 10px;
        }

        .price-input {
            width: 100%;
            display: flex;
            margin: 5px 0 10px;
        }

        .price-input .field {
            display: flex;
            width: 100%;
            height: 45px;
            align-items: center;
        }

        .field input {
            width: 100%;
            height: 100%;
            outline: none;
            font-size: 19px;
            margin-left: 12px;
            border-radius: 5px;
            text-align: center;
            border: 1px solid #999;
            -moz-appearance: textfield;
        }

        input[type="number"]::-webkit-outer-spin-button,
        input[type="number"]::-webkit-inner-spin-button {
            -webkit-appearance: none;
        }

        .price-input .separator {
            width: 130px;
            display: flex;
            font-size: 19px;
            align-items: center;
            justify-content: center;
        }

        .slider {
            height: 5px;
            position: relative;
            background: #ddd;
            border-radius: 5px;
        }

        .slider .progress {
            height: 100%;
            /* left: 25%;
            right: 25%; */

            left: 0%;
            right: 0%;
            position: absolute;
            border-radius: 5px;
            background: #17A2B8;
        }

        .range-input {
            position: relative;
        }

        .range-input input {
            position: absolute;
            width: 100%;
            height: 5px;
            top: -5px;
            background: none;
            pointer-events: none;
            -webkit-appearance: none;
            -moz-appearance: none;
        }

        input[type="range"]::-webkit-slider-thumb {
            height: 17px;
            width: 17px;
            border-radius: 50%;
            background: #17A2B8;
            pointer-events: auto;
            -webkit-appearance: none;
            box-shadow: 0 0 6px rgba(0, 0, 0, 0.05);
        }

        input[type="range"]::-moz-range-thumb {
            height: 17px;
            width: 17px;
            border: none;
            border-radius: 50%;
            background: #17A2B8;
            pointer-events: auto;
            -moz-appearance: none;
            box-shadow: 0 0 6px rgba(0, 0, 0, 0.05);
        }
    </style>
</head>

<body class="bg-light">

    <?php require('inc/header.php'); ?>

    <div class="container-fluid">
        <div class="d-flex align-items-center" style="margin:0 100px;">
            <select name="brand" class="form-select form-select-sm" aria-label="Small select example" style="width:20%; margin-right:10px;">
                <option value="Brand" selected>All Brand</option>
                <?php
                    $brand_res = selectAll('brand');
                    while ($brand_data = mysqli_fetch_assoc($brand_res)) {
                        if($brand_data['Status'] == 1) continue;
                        echo "<option value='{$brand_data['BrandID']}'>{$brand_data['BrandName']}</option>";
                    }
                ?>
            </select>

            <select name="category" class="form-select form-select-sm" aria-label="Small select example" style="width:20%;">
                <option value="Category" selected>All Category</option>
                <?php
                        $category_res = selectAll('category');
                        while ($category_data = mysqli_fetch_assoc($category_res)) {
                            if($category_data['Status'] == 1) continue;
                            echo "<option value='{$category_data['TypeID']}'>{$category_data['TypeName']}</option>";
                        }
                ?>
            </select>

            <div class="range_price">
                <div class="price-input">
                    <div class="field">
                        <span>Min</span>
                        <input type="number" class="input-min" value="0" disabled>
                    </div>
                    <div class="separator">-</div>
                    <div class="field">
                        <span>Max</span>
                        <input type="number" class="input-max" value="1000000" disabled>
                    </div>
                </div>
                <div class="slider">
                    <div class="progress"></div>
                </div>
                <div class="range-input">
                    <input type="range" class="range-min" min="0" max="1000000" value="0" step="1000">
                    <input type="range" class="range-max" min="0" max="1000000" value="1000000" step="1000">
                </div>
            </div>

            <button onclick="checkFilter()" class='btn btn-sm text-white shadow-none' style='background: #ff523b; padding: 10px;font-weight: bold;cursor: pointer;margin: 20px;border-radius: 20px;'>Xác Nhận</button>
        </div>

        <div id="showProduct" class="row">

        </div>
    </div>

    <div class="d-flex justify-content-center">
        <nav aria-label="...">
            <ul class="pagination">

            </ul>
        </nav>
    </div>
    <?php require('inc/footer.php'); ?>

    <script>
        const rangeInput = document.querySelectorAll(".range-input input"),
            priceInput = document.querySelectorAll(".price-input input"),
            range = document.querySelector(".slider .progress");
        let priceGap = 1000;
        priceInput.forEach(input => {
            input.addEventListener("blur", e => {
                let minPrice = parseInt(priceInput[0].value),
                    maxPrice = parseInt(priceInput[1].value);

                if ((maxPrice - minPrice >= priceGap) && maxPrice <= rangeInput[1].max) {
                    if (e.target.className === "input-min") {
                        rangeInput[0].value = minPrice;
                        range.style.left = ((minPrice / rangeInput[0].max) * 100) + "%";
                    } else {
                        rangeInput[1].value = maxPrice;
                        range.style.right = 100 - (maxPrice / rangeInput[1].max) * 100 + "%";
                    }
                }
            });
        });
        rangeInput.forEach(input => {
            input.addEventListener("input", e => {
                let minVal = parseInt(rangeInput[0].value),
                    maxVal = parseInt(rangeInput[1].value);
                if ((maxVal - minVal) < priceGap) {
                    if (e.target.className === "range-min") {
                        rangeInput[0].value = maxVal - priceGap
                    } else {
                        rangeInput[1].value = minVal + priceGap;
                    }
                } else {
                    priceInput[0].value = minVal;
                    priceInput[1].value = maxVal;
                    range.style.left = ((minVal / rangeInput[0].max) * 100) + "%";
                    range.style.right = 100 - (maxVal / rangeInput[1].max) * 100 + "%";
                }
            });
        });

        function checkFilter() {
            var selectedValue1 = $('select[name="brand"]').val();
            var selectedValue2 = $('select[name="category"]').val();
            const priceInput = document.querySelectorAll(".price-input input");
            $.ajax({
                method: 'POST',
                url: 'ajax/product.php',
                data: {
                    checkFilter: true,
                    brandValue: selectedValue1,
                    categoryValue: selectedValue2,
                    minPrice: priceInput[0].value,
                    maxPrice: priceInput[1].value
                },
                dataType: 'json',
                success: function(data) {
                    products = data;
                    console.log(products);
                    renderProducts();
                    renderPagination();
                },
                error: function(xhr, status, error) {
                    console.error(error);
                }
            });
        }



        //pagination
        const productsPerPage = 6;
        let currentPage = 1;
        let products = [];

        $(document).ready(function() {
            $.ajax({
                url: 'ajax/product.php',
                method: 'POST',
                data: {
                    action: 'get_products'
                },
                dataType: 'json',
                success: function(data) {
                    products = data;
                    console.log(products);
                    renderProducts();
                    renderPagination();
                },
                error: function(xhr, status, error) {
                    console.error(error);
                }
            });
        });

        function renderProducts() {
            const start = (currentPage - 1) * productsPerPage;
            const end = start + productsPerPage;
            const paginatedProducts = products.slice(start, end);

            const productList = $('#showProduct');
            productList.empty();

            paginatedProducts.forEach(product => {
                if(product.IMG == null){
                    product.IMG = 'images/products/thumbnail.jpg';
                }
                const productCard = `
            <div class="col-lg-4 col-md-6 my-3">
                <div class="card border-0 shadow" style="max-width:350px; margin:auto;">
                    <img src="./admin/${product.IMG}" class="card-img-top">
                    <div class="card-body">
                        <h5>${product.ProductName}</h5>
                        <h6 class="mb-4">${Number(product.ProductPrice).toLocaleString('vi-VN')} VNĐ</h6>
                        <div class="category mb-4">
                            <h6 class="mb-1">Category</h6>
                            ${product.Category}
                        </div>
                        <div class="producer mb-4">
                            <h6 class="mb-1">Brand</h6>
                            ${product.Brand}
                        </div>
                        <div class="other mb-4">
                            <h6 class="mb-1">Other</h6>
                            <span class="badge rounded-pill bg-light text-dark text-wrap">
                                ${product.Age}
                            </span>
                            <span class="badge rounded-pill bg-light text-dark text-wrap">
                                ${product.Description}
                            </span>
                        </div>
                        <div class="d-flex justify-content-evenly mb-2">
                            <a href="product_details.php?id=${product.ProductID}" class="btn btn-sm btn-outline-dark shadow-none">More details</a>
                        </div>
                    </div>
                </div>
            </div>
        `;
                productList.append(productCard);
            });
        }

        function renderPagination() {
            const totalPages = Math.ceil(products.length / productsPerPage);
            const pagination = $('.pagination');
            pagination.empty();

            for (let i = 1; i <= totalPages; i++) {
                const li = $(`
            <li class="page-item ${i === currentPage ? 'active' : ''}">
                <a class="page-link" href="#">${i}</a>
            </li>
        `);
                li.on('click', function() {
                    currentPage = i;
                    renderProducts();
                    renderPagination();
                });
                pagination.append(li);
            }
        }
    </script>
</body>

</html>