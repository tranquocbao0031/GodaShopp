<?php
// Auth là Authentication (xác thực)
class AuthController
{
    function login()
    {
        $email = $_POST['email'];
        $customerRepository = new CustomerRepository();
        $customer = $customerRepository->findEmail($email);
        // Email không tồn tại
        if (empty($customer)) {
            $_SESSION['error'] = 'Lỗi: Email không tồn tại';
            header('location: /');
            exit;
        }
        // Check password
        $password = $_POST['password'];
        if (!password_verify($password, $customer->getPassword())) {
            $_SESSION['error'] = 'Lỗi: Mật khẩu không đúng';
            header('location: /');
            exit;
        }

        // Check account active
        if (!$customer->getIsActive()) {
            $_SESSION['error'] = 'Lỗi: Tài khoản chưa được kích hoạt. Vui lòng kiểm tra email để kích hoạt tài khoản';
            header('location: /');
            exit;
        }

        // Đăng nhập thành công
        $_SESSION['email'] = $email;
        $_SESSION['name'] = $customer->getName();
        $_SESSION['success'] = 'Đăng nhập thành công';
        header('location: ?c=customer&a=show');
    }

    function logout()
    {
        // hủy session (nghĩa là $_SESSION sẽ empty)
        session_destroy();
        // trở về trang chủ
        header('location: /');
    }
}
