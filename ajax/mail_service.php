<?php
require('../admin/inc/db_config.php');
require('../admin/inc/essentials.php');
session_start();
require __DIR__ . '/../vendor/autoload.php';  // Đảm bảo đã cài đặt PHPMailer

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if (isset($_POST['forgot_pass'])) {
    $data = filteration($_POST);
    $email = $data['email'];
    $checkUser = $con->prepare("SELECT * FROM user WHERE Email = ? AND Status=0 AND deleted=0 AND (RoleID=1 OR RoleID=5)");
    $checkUser->bind_param("s", $email);
    $checkUser->execute();
    $result = $checkUser->get_result();

    if ($result->num_rows > 0) {
        $token = bin2hex(random_bytes(3)); // Tạo mã ngẫu nhiên
        $_SESSION['reset_token'] = $token;
        $_SESSION['reset_email'] = $email;
        $_SESSION['token_expire'] = time() + 300; // Token hết hạn sau 5 phút

        // Gửi email qua Mailtrap
        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host = 'sandbox.smtp.mailtrap.io'; // Mailtrap SMTP
            $mail->SMTPAuth = true;
            $mail->Username = 'eebc5fedba5dcd';
            $mail->Password = 'fd1b150935fad3';
            $mail->Port = 2525;

            $mail->setFrom('noreply@example.com', 'Library System');
            $mail->addAddress($email);
            $mail->CharSet = 'UTF-8';
            $mail->Encoding = 'base64';
            $mail->isHTML(true);
            $mail->Subject = 'Password Reset Request';
            $mail->Body    = "<!DOCTYPE html>
<html lang='vi'>
<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <title>Password Reset Request</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
            margin: 0;
            padding: 20px;
            color: #333;
        }
        .email-container {
            max-width: 600px;
            margin: 20px auto;
            background: #ffffff;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            padding: 20px;
            border: 1px solid #ddd;
        }
        .email-header {
            text-align: center;
            border-bottom: 2px solid #007bff;
            padding-bottom: 10px;
        }
        .email-header h2 {
            color: #007bff;
        }
        .email-body {
            padding: 20px;
            line-height: 1.6;
        }
        .email-body p {
            margin: 0 0 15px;
        }
        .email-footer {
            margin-top: 20px;
            text-align: center;
            font-size: 0.9em;
            color: #555;
        }
        .verification-code {
            font-size: 24px;
            font-weight: bold;
            color: #d9534f;
        }
        .btn {
            display: inline-block;
            padding: 10px 15px;
            background: #28a745;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin-top: 15px;
        }
        .btn:hover {
            background: #218838;
        }
    </style>
</head>
<body>
    <div class='email-container'>
        <div class='email-header'>
            <h2>MyKingdom System</h2>
        </div>
        <div class='email-body'>
            <p>Xin chào,</p>
            <p>Bạn đã yêu cầu đặt lại mật khẩu. Vui lòng sử dụng mã xác minh bên dưới để đặt lại mật khẩu của bạn:</p>
            <p class='verification-code'>$token</p>
        </div>
        <div class='email-footer'>
            <p>Nếu bạn không yêu cầu đặt lại mật khẩu, vui lòng bỏ qua email này.</p>
            <p>&copy; 2025 MyKingdom System</p>
        </div>
    </div>
</body>
</html>";
            $mail->send();
            echo 1;
        } catch (Exception $e) {
            echo "Không thể gửi email: {$mail->ErrorInfo}";
        }
    } else {
        echo 0;
    }
}


if (isset($_POST['verify_token'])) {
    $data = filteration($_POST);
    $token = $data['token'];

    if ($token === $_SESSION['reset_token'] && time() < $_SESSION['token_expire']) {
        unset($_SESSION['reset_token']);
        unset($_SESSION['token_expire']);
        echo 1;
    } else {
        echo 0;
    }
}

if (isset($_POST['reset_pass'])) {
    $data = filteration($_POST);
    $enc_pass = password_hash($data['pass'], PASSWORD_BCRYPT);

    $query = "UPDATE `user` SET `Password`=? WHERE `Email`=?";
    $values = [$enc_pass, $_SESSION['reset_email']];
    if (update($query, $values, 'ss')) {
        unset($_SESSION['reset_email']);
        echo 1;
    } else {
        echo 0;
    }
}
