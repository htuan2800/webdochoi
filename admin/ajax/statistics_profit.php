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
    $totalBills = 0;
    $totalImports = 0;
    $totalProfits = 0;
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

            //bill
            $sql1 = "SELECT 
                        SUM(i.Total) as TotalBill
                FROM `bill` i
                WHERE i.CreateTime BETWEEN '{$weekStart->format('Y-m-d')}' AND '{$weekEnd->format('Y-m-d')}'
                AND i.status = 'Đã Nhận Hàng'";
            $sql1_query = mysqli_query($con, $sql1);
            $val1 = mysqli_fetch_array($sql1_query);

            //import
            $sql2 = "SELECT 
                        SUM(i.Total) as TotalImport
                FROM `import` i
                WHERE i.CreateTime BETWEEN '{$weekStart->format('Y-m-d')}' AND '{$weekEnd->format('Y-m-d')}'
                AND i.Status = 1";
            $sql2_query = mysqli_query($con, $sql2);
            $val2 = mysqli_fetch_array($sql2_query);


            $TotalBill=$val1['TotalBill'] ?? 0;
            $TotalImport=$val2['TotalImport'] ?? 0;
            $TotalProfit=$TotalBill-$TotalImport;
            $chart_data[] = array(
                'period' => "Tuần " . $weekNum,
                'from_date' => $weekStart->format('d/m/Y'),
                'to_date' => $weekEnd->format('d/m/Y'),
                'total_bill' => $TotalBill,
                'total_import' => $TotalImport,
                'total_profit' => $TotalProfit
            );

            $totalBills += $TotalBill;
            $totalImports += $TotalImport;
            $totalProfits+=$TotalProfit;

            $currentDay->addDays(7);
            $weekNum++;
        }
    } else if ($timeType == "2") { // Theo năm
        for ($month = 1; $month <= 12; $month++) {
            $firstDay = Carbon::createFromDate($timeValue, $month, 1)->startOfMonth();
            $lastDay = Carbon::createFromDate($timeValue, $month, 1)->endOfMonth();

            $sql1 = "SELECT 
                        SUM(i.Total) as TotalBill
                FROM `bill` i
                WHERE i.CreateTime BETWEEN '{$firstDay->format('Y-m-d')}' AND '{$lastDay->format('Y-m-d')}'
                AND i.status = 'Đã Nhận Hàng'";
            $sql1_query = mysqli_query($con, $sql1);
            $val1 = mysqli_fetch_array($sql1_query);

            $sql2 = "SELECT 
                        SUM(i.Total) as TotalImport
                FROM `import` i
                WHERE i.CreateTime BETWEEN '{$firstDay->format('Y-m-d')}' AND '{$lastDay->format('Y-m-d')}'
                AND i.Status = 1";
            $sql2_query = mysqli_query($con, $sql2);
            $val2 = mysqli_fetch_array($sql2_query);

            $TotalBill=$val1['TotalBill'] ?? 0;
            $TotalImport=$val2['TotalImport'] ?? 0;
            $TotalProfit=$TotalBill-$TotalImport;
            $chart_data[] = array(
                'period' => "Tháng " . $month,
                'from_date' => $firstDay->format('d/m/Y'),
                'to_date' => $lastDay->format('d/m/Y'),
                'total_bill' => $TotalBill,
                'total_import' => $TotalImport,
                'total_profit' => $TotalProfit
            );


            $totalBills += $TotalBill;
            $totalImports += $TotalImport;
            $totalProfits+=$TotalProfit;
        }
    }

    echo json_encode(["chart_data" => $chart_data, "totalBills" => $totalBills, "totalImports" => $totalImports, "totalProfits" => $totalProfits]);
}
