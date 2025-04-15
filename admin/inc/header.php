<?php
$hname = 'localhost';
$uname = 'root';
$pass = 'admin';
$db = 'bandochoi';

$con = mysqli_connect($hname, $uname, $pass, $db);
?>
<div class="container-fluid bg-dark text-light p-3 d-flex align-items-center justify-content-between sticky-top">
    <h3 class="mb-0 h-font">MYKINGDOM</h3>
    <a href="logout.php" class="btn btn-light btn-sm">LOG OUT</a>
</div>

<div class="col-lg-2 bg-dark border-top border-3 border-secondary" id="dashboard-menu">
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container-fluid flex-lg-column align-items-stretch">
            <button class="navbar-toggler shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#adminDropdown" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse flex-column align-items-stretch mt-2" id="adminDropdown">
                <ul class="nav nav-pills flex-column">
                    <?php
                    switch ($_SESSION["mUser"]["RoleID"]) {
                        case 2:
                            echo '
                                        <li class="nav-item">
                                            <a class="nav-link text-white" href="users.php">Customer</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link text-white" href="bills.php">Bills</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link text-white" href="statistics_order.php">StatisticsOrder</a>
                                        </li>
                                    ';
                            break;
                        case 3:
                            echo ' 
                                        <li class="nav-item">
                                                <a class="nav-link text-white" href="account.php">Account</a>
                                        </li>
                                    ';
                            break;
                        case 4:
                            echo '
                                        <li class="nav-item">
                                            <a class="nav-link text-white" href="category_producer.php">Category & Brand</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link text-white" href="supplier.php">Supplier</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link text-white" href="import.php">Import</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link text-white" href="voucher.php">Voucher</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link text-white" href="products.php">Products</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link text-white" href="statisticsImport.php">Statistics Import</a>
                                        </li>
                                    ';
                            break;
                        case 5:
                            echo '
                                        <li class="nav-item">
                                                <a class="nav-link text-white" href="account.php">Account</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link text-white" href="users.php">Customer</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link text-white" href="bills.php">Bills</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link text-white" href="category_producer.php">Category & Brand</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link text-white" href="supplier.php">Supplier</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link text-white" href="import.php">Import</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link text-white" href="voucher.php">Voucher</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link text-white" href="products.php">Products</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link text-white" href="statisticsOrder.php">Statistics Order</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link text-white" href="statisticsImport.php">Statistics Import</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link text-white" href="statisticsProfit.php">Statistics Profit</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link text-white" href="contact.php">Contact</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link text-white" href="carousel.php">Carousel</a>
                                        </li>
                                    ';
                            break;
                    }
                    ?>

                </ul>
            </div>
        </div>
    </nav>
</div>