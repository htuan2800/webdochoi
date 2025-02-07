<?php

use Carbon\Carbon;

require('../inc/db_config.php');
require('../inc/essentials.php');
require('../../Carbon/autoload.php');
date_default_timezone_set('Asia/Ho_Chi_Minh');
if (isset($_POST['timeType'])) {
    $timeType = $_POST['timeType'];
    $timeValue = $_POST['timeValue'];
    $chart_data = [];
    //all
    $totalImport = 0;
    $totalProduct = 0;
    $totalPrice = 0;
    if ($timeType == "1") { // Theo tháng
        // Lấy ngày đầu và cuối của tháng
        $firstDay = Carbon::createFromDate(date('Y'), $timeValue, 1)->startOfMonth();
        $lastDay = Carbon::createFromDate(date('Y'), $timeValue, 1)->endOfMonth();

        // Chia tháng thành các tuần
        $currentDay = $firstDay->copy();
        $weekNum = 1;

        while ($currentDay <= $lastDay) {
            $weekStart = $currentDay->copy();
            $weekEnd = $currentDay->copy()->addDays(6)->min($lastDay);

            $sql = "SELECT 
                    COUNT(DISTINCT i.ImportID) as total_imports,
                    SUM(id.Quantity) as total_products,
                    SUM(id.Quantity * id.Unitprice) as total_cost
                FROM `import` i
                LEFT JOIN importdetail id ON i.ImportID = id.ImportID
                WHERE i.CreateTime BETWEEN '{$weekStart->format('Y-m-d')}' AND '{$weekEnd->format('Y-m-d')}'
                AND i.Status = 1";

            $sql_query = mysqli_query($con, $sql);
            $val = mysqli_fetch_array($sql_query);

            $chart_data[] = array(
                'period' => "Tuần " . $weekNum,
                'from_date' => $weekStart->format('d/m/Y'),
                'to_date' => $weekEnd->format('d/m/Y'),
                'total_imports' => $val['total_imports'] ?? 0,
                'total_products' => $val['total_products'] ?? 0,
                'total_cost' => $val['total_cost'] ?? 0
            );

            if ($val['total_imports']) {
                $totalImport += $val['total_imports'];
            } else {
                $totalImport += 0;
            }

            if ($val['total_products']) {
                $totalProduct += $val['total_products'];
            } else {
                $totalProduct += 0;
            }

            if ($val['total_cost']) {
                $totalPrice += $val['total_cost'];
            } else {
                $totalPrice += 0;
            }

            $currentDay->addDays(7);
            $weekNum++;
        }
    } else if ($timeType == "2") { // Theo năm
        for ($month = 1; $month <= 12; $month++) {
            $firstDay = Carbon::createFromDate($timeValue, $month, 1)->startOfMonth();
            $lastDay = Carbon::createFromDate($timeValue, $month, 1)->endOfMonth();

            $sql = "SELECT 
                    COUNT(DISTINCT i.ImportID) as total_imports,
                    SUM(id.Quantity) as total_products,
                    SUM(id.Quantity * id.Unitprice) as total_cost
                FROM `import` i
                LEFT JOIN importdetail id ON i.ImportID = id.ImportID
                WHERE i.CreateTime BETWEEN '{$firstDay->format('Y-m-d')}' AND '{$lastDay->format('Y-m-d')}'
                AND i.Status = 1";
            $sql_query = mysqli_query($con, $sql);
            $val = mysqli_fetch_array($sql_query);

            $chart_data[] = array(
                'period' => "Tháng " . $month,
                'from_date' => $firstDay->format('d/m/Y'),
                'to_date' => $lastDay->format('d/m/Y'),
                'total_imports' => $val['total_imports'] ?? 0,
                'total_products' => $val['total_products'] ?? 0,
                'total_cost' => $val['total_cost'] ?? 0
            );


            if ($val['total_imports']) {
                $totalImport += $val['total_imports'];
            } else {
                $totalImport += 0;
            }

            if ($val['total_products']) {
                $totalProduct += $val['total_products'];
            } else {
                $totalProduct += 0;
            }

            if ($val['total_cost']) {
                $totalPrice += $val['total_cost'];
            } else {
                $totalPrice += 0;
            }
        }
    }

    echo json_encode(["chart_data" => $chart_data, "totalImport" => $totalImport, "totalProduct" => $totalProduct, "totalPrice" => $totalPrice]);
}
