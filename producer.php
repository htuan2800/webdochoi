<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    <?php require('inc/links.php'); ?>
    <title>MYKINGDOM - PRODUCER</title>
    <style>
        .pop:hover {
            border-top-color: var(--teal) !important;
            transform: scale(1.03);
            transition: all 0.3s;
        }
    </style>
</head>

<body class="bg-light">

    <?php require('inc/header.php'); ?>
    <div class="my-5 px-4">
        <h2 class="fw-bold h-font text-center">OUR BRAND</h2>
        <div class="bg-dark h-line"></div>
        <p class="text-center mt-3">Lorem ipsum, or lipsum as it is sometimes known, is <br> dummy text used in laying out print, graphic or web designs.
            The passage is attributed to an unknown typesetter in the 15th century.
        </p>
    </div>

    <div class="container">
        <div class="row">
            <?php
            $res = selectAll('brand');
            while ($row = mysqli_fetch_assoc($res)) {
                echo <<<data
                    <div class="col-lg-4 col-md-6 mb-5 px-4">
                        <div class="bg-white rounded shadow p-4 border-top border-4 border-dark pop">
                        <div class="d-flex align-items-center mb-2">
                            <h5 class="m-0 ms-3">$row[BrandName]</h5>
                        </div>
                         </div>
                    </div>
                data;
            }
            ?>
        </div>
    </div>
    <?php require('inc/footer.php'); ?>
</body>

</html>