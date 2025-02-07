<?php

require('../Carbon/autoload.php');
require('inc/essentials.php');
adminLogin();
if($_SESSION['mUser']['RoleID'] != 5){
    echo "You are not allowed to access this page.";
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/morris.js/0.5.1/morris.css">
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.0/jquery.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/raphael/2.1.0/raphael-min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/morris.js/0.5.1/morris.min.js"></script>

    <title>Admin Panel - Dashboard</title>
    <?php require('inc/links.php'); ?>
</head>

<body class="bg-light">

    <?php require('inc/header.php'); ?>

    <div class="container-fluid" id="main-content">
        <div class="row">
            <div class="col-lg-10 ms-auto p-4 overflow-hidden">
                <h3 class="mb-4">Contact</h3>
                <div class="table-responsive-md" style="height:450px; overflow-y:scroll;">
                    <table class="table table-hover border text-cennter">
                        <thead class="sticky-top">
                            <tr class="bg-dark text-light">
                                <th scope="col">Id</th>
                                <th scope="col">Name</th>
                                <th scope="col">Email</th>
                                <th scope="col">Phone</th>
                                <th scope="col">Subject</th>
                                <th scope="col">Message</th>
                                <th scope="col">Create_At</th>
                                <th scope="col">Status</th>
                            </tr>
                        </thead>
                        <tbody id="contact-data">

                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>
    <?php require('inc/scripts.php'); ?>
    <script>
        function get_all_contacts() {

            let xhr = new XMLHttpRequest();
            xhr.open("POST", "ajax/contact.php", true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

            xhr.onload = function() {
                document.getElementById('contact-data').innerHTML = this.responseText;
            }
            xhr.send('get_all_contacts');
        }
        window.onload = function() {
            get_all_contacts();
        }

        function toggle_status(id, val) {

            let xhr = new XMLHttpRequest();
            xhr.open("POST", "ajax/contact.php", true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

            xhr.onload = function() {
                if (this.responseText == 1) {
                    toast('success', 'Status toggled!');
                    get_all_contacts();
                } else {
                    toast('error', 'Server Down!');
                }
            }
            xhr.send('toggle_status=' + id + '&value=' + val);
        }
    </script>
</body>

</html>