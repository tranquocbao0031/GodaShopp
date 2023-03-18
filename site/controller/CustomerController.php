<?php
class CustomerController
{
    protected function checkLogin()
    {
        if (empty($_SESSION['email'])) {
            // Về trang chủ
            header('location: /');
            exit;
        }
    }

    // Thông tin tài khoản
    function show()
    {
        $this->checkLogin();
        $email = $_SESSION['email'];
        $customerRepository = new CustomerRepository();
        $customer = $customerRepository->findEmail($email);
        require 'view/customer/show.php';
    }

    // Địa chỉ giao hàng mặc định
    function defaultShipping()
    {
        $this->checkLogin();
        require 'view/customer/defaultShipping.php';
    }

    //Đơn hàng của tôi
    function orders()
    {
        $this->checkLogin();
        // Lấy order của người đăng nhập
        $email = $_SESSION['email'];
        $customerRepository = new CustomerRepository();
        $customer = $customerRepository->findEmail($email);
        $customer_id = $customer->getId();
        $orderRepository = new OrderRepository();
        $orders = $orderRepository->getByCustomerId($customer_id);
        require 'view/customer/orders.php';
    }

    //Chi tiết đơn hàng
    function orderDetail()
    {
        $this->checkLogin();
        $id = $_GET['id'];
        $orderRepository = new OrderRepository();
        $order = $orderRepository->find($id);
        // check đơn hàng của người đăng nhập
        $email = $_SESSION['email'];
        $customerRepository = new CustomerRepository();
        $customer = $customerRepository->findEmail($email);
        $customer_id = $customer->getId();
        if ($customer_id !=  $order->getCustomerId()) {
            $_SESSION['error'] = 'Bạn không có quyền truy cập vào đơn hàng của người khác';
            header('location: ?c=customer&a=orders');
            exit;
        }
        require 'view/customer/orderDetail.php';
    }

    function updateInfo()
    {
        $this->checkLogin();
        $email = $_SESSION['email'];
        $customerRepository = new CustomerRepository();
        $customer = $customerRepository->findEmail($email);

        // update data
        $customer->setName($_POST['fullname']);
        $customer->setMobile($_POST['mobile']);

        // Người dùng có thay đổi mật khẩu không
        // Người dùng nhập mật khẩu hiện tại và mật khẩu mới
        if (!empty($_POST['current_password']) && !empty($_POST['password'])) {
            if (!password_verify($_POST['current_password'], $customer->getPassword())) {
                $_SESSION['error'] = 'Mật khẩu hiện tại không đúng';
                header('location: ?c=customer&a=show');
                exit;
            }
            // mã hóa mật khẩu
            $encodePassword = password_hash($_POST['password'], PASSWORD_BCRYPT);
            $customer->setPassword($encodePassword);
        }

        // Update xuống database
        if ($customerRepository->update($customer)) {
            $_SESSION['name'] = $customer->getName();
            $_SESSION['success'] = 'Đã cập nhật thông tin tài khoản thành công';
            header('location: ?c=customer&a=show');
            exit;
        }
        $_SESSION['error'] = $customerRepository->getError();
        header('location: ?c=customer&a=show');
    }
}
