<?php
class ContactController
{
    // Hiển thị form liên hệ
    function form()
    {
        require 'view/contact/form.php';
    }

    // Gởi mail đến chủ cửa hảng
    function sendEmail()
    {
        $fullname = $_POST['fullname'];
        $email = $_POST['email'];
        $mobile = $_POST['mobile'];
        $content = $_POST['content'];

        $emailService = new EmailService();
        $to = SHOP_OWNER;
        $subject = 'Godashop: Liên hệ';
        $website = get_domain();
        $content = "
        Xin chào chủ cửa hàng, <br>
        Dưới đây là thông tin khách hàng liên hệ: <br>
        Tên: $fullname, <br>
        Số điện thoại: $mobile, <br>
        Email: $email, <br>
        Nội dung: $content, <br>
        Được gởi từ website: $website
        ";
        if ($emailService->send($to, $subject, $content)) {
            echo 'Đã gởi mail thành công';
            return;
        }
        echo $emailService->error;
    }
}
