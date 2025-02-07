<?php
require('../inc/db_config.php');
require('../inc/essentials.php');
if (isset($_POST['get_all_contacts'])) {
    $res = selectAll('contact');
    $data = "";

    while ($row = mysqli_fetch_assoc($res)) {
        if ($row['status'] == '1') {
            $status = "<button onclick='toggle_status($row[id],0)' class='btn btn-dark btn-sm shadow-none'>Seen</button>";
        } else {
            $status = "<button onclick='toggle_status($row[id],1)' class='btn btn-warning btn-sm shadow-none'>Not Seen</button>";
        }
        $data .= "
                        <tr class='align-middle'>
                            <td>$row[id]</td>
                            <td>$row[name]</td>
                            <td>$row[email]</td>
                            <td>$row[phone]</td>
                            <td>$row[subject]</td>
                            <td>$row[message]</td>
                            <td>$row[create_at]</td>
                            
                            <td>$status</td>
                        </tr>
                    ";
    }
    echo $data;
}

if (isset($_POST['toggle_status'])) {
    $frm_data = filteration($_POST);
    $q = "UPDATE `contact` SET `status`=? WHERE `id`=?";
    $v = [$frm_data['value'], $frm_data['toggle_status']];

    if (update($q, $v, 'ii')) {
        echo 1;
    } else {
        echo 0;
    }
}
