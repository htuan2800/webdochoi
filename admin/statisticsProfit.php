<?php
require('inc/essentials.php');
require('inc/db_config.php');
adminLogin();
if( $_SESSION['mUser']['RoleID'] != 5){
    echo "You are not allowed to access this page.";
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - Statistic Profit</title>
    <?php require('inc/links.php'); ?>
</head>

<body class="bg-light">

    <?php require('inc/header.php'); ?>

    <div class="container-fluid" id="main-content">
        <div class="row">
            <div class="col-lg-10 ms-auto p-4 overflow-hidden">
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body">

                        <div class="d-flex align-items-center gap-3">
                            <div class="d-flex align-items-center">
                                <label for="selectTime" class="mb-0">Báo cáo theo</label>
                                <select class="form-control mx-2" id="selectTime" aria-label="Default select example" onchange="selectTime()">
                                    <option value="1">Tháng</option>
                                    <option value="2">Năm</option>
                                </select>
                            </div>

                            <div class="d-flex align-items-center">
                                <label for="timeSelect" class="mb-0">Chọn thời gian</label>
                                <select class="form-control mx-2" id="timeSelect" aria-label="Default select example">
                                </select>
                            </div>

                            <button type="button" class="btn btn-primary shadow-none btn-sm" onclick="watchStatistics()">
                                Xem thống kê
                            </button>
                        </div>


                        <div class="table-responsive-md" style="height:350px; overflow-y:scroll;">
                            <table class="table table-hover border">
                                <thead class="sticky-top" id="headTable">
                                    
                                </thead>
                                <tbody id="statistics-data">

                                </tbody>
                            </table>
                        </div>

                        <button onclick="exportToPDF()" class="btn btn-primary shadow-none btn-sm"><i class="bi bi-printer"></i> Xuất PDF</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php require('inc/scripts.php'); ?>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js" integrity="sha512-GsLlZN/3F2ErC5ifS5QtgpiJtWd43JWSuIgh7mbzZ8zBps+dvLusV+eNQATqgA/HdeKFVgA5v3S/cIrLF7QnIg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="scripts/statistics_profit.js"></script>
</body>

</html>