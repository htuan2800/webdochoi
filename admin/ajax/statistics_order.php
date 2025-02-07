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
    $totalBill = 0;
    $subTotal = 0;
    $Total = 0;
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
                        COUNT(DISTINCT i.BillID) as total_bills,
                        SUM(i.Subtotal) as Subtotal,
                        SUM(i.Total) as Total
                FROM `bill` i
                WHERE i.CreateTime BETWEEN '{$weekStart->format('Y-m-d')}' AND '{$weekEnd->format('Y-m-d')}'
                AND i.status = 'Đã Nhận Hàng'";

            $sql_query = mysqli_query($con, $sql);
            $val = mysqli_fetch_array($sql_query);

            $chart_data[] = array(
                'period' => "Tuần " . $weekNum,
                'from_date' => $weekStart->format('d/m/Y'),
                'to_date' => $weekEnd->format('d/m/Y'),
                'total_bills' => $val['total_bills'] ?? 0,
                'subtotal' => $val['Subtotal'] ?? 0,
                'total' => $val['Total'] ?? 0
            );

            if ($val['total_bills']) {
                $totalBill += $val['total_bills'];
            } else {
                $totalBill += 0;
            }

            if ($val['Subtotal']) {
                $subTotal += $val['Subtotal'];
            } else {
                $subTotal += 0;
            }

            if ($val['Total']) {
                $Total += $val['Total'];
            } else {
                $Total += 0;
            }

            $currentDay->addDays(7);
            $weekNum++;
        }
    } else if ($timeType == "2") { // Theo năm
        for ($month = 1; $month <= 12; $month++) {
            $firstDay = Carbon::createFromDate($timeValue, $month, 1)->startOfMonth();
            $lastDay = Carbon::createFromDate($timeValue, $month, 1)->endOfMonth();

            $sql = "SELECT 
                        COUNT(DISTINCT i.BillID) as total_bills,
                        SUM(i.Subtotal) as Subtotal,
                        SUM(i.Total) as Total
                FROM `bill` i
                WHERE i.CreateTime BETWEEN '{$firstDay->format('Y-m-d')}' AND '{$lastDay->format('Y-m-d')}'
                AND i.status = 'Đã Nhận Hàng'";
            $sql_query = mysqli_query($con, $sql);
            $val = mysqli_fetch_array($sql_query);

            $chart_data[] = array(
                'period' => "Tháng " . $month,
                'from_date' => $firstDay->format('d/m/Y'),
                'to_date' => $lastDay->format('d/m/Y'),
                'total_bills' => $val['total_bills'] ?? 0,
                'subtotal' => $val['Subtotal'] ?? 0,
                'total' => $val['Total'] ?? 0
            );


            if ($val['total_bills']) {
                $totalBill += $val['total_bills'];
            } else {
                $totalBill += 0;
            }

            if ($val['Subtotal']) {
                $subTotal += $val['Subtotal'];
            } else {
                $subTotal += 0;
            }

            if ($val['Total']) {
                $Total += $val['Total'];
            } else {
                $Total += 0;
            }
        }
    }

    echo json_encode(["chart_data" => $chart_data, "totalBill" => $totalBill, "subTotal" => $subTotal, "Total" => $Total]);
}
