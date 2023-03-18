<?php
class HomeController
{
    function index()
    {
        $conds = [];
        $page = 1;
        $item_per_page = 4;

        // Lấy những sản phẩm nổi bật
        // Sắp xếp giảm dần
        $sorts = ['featured' => 'DESC'];
        $productRepository = new ProductRepository();
        $featuredProducts = $productRepository->getBy($conds, $sorts, $page, $item_per_page);

        // Lấy những sản phẩm mới nhất
        // Sắp xếp ngày tạo giảm dần
        $sorts = ['created_date' => 'DESC'];
        $productRepository = new ProductRepository();
        $latestProducts = $productRepository->getBy($conds, $sorts, $page, $item_per_page);

        // Lấy tất cả sản phẩm theo từng danh mục
        $categoryRepository = new CategoryRepository();
        $categories = $categoryRepository->getAll();
        // Biến này dùng lưu trữ danh sách sản phẩm theo từng danh mục
        $categoryProducts = [];
        foreach ($categories as $category) {
            $conds = [
                'category_id' => [
                    'type' => '=',
                    'val' => $category->getId()
                ]
            ];
            $products = $productRepository->getBy($conds, $sorts, $page, $item_per_page);
            //SELECT * FROM view_product WHERE category_id = 4

            // Thêm vào danh sách để lưu (đem dữ liệu ra view)
            $categoryProducts[] = [
                'categoryName' => $category->getName(),
                'products' => $products
            ];
        }

        require 'view/home/index.php';
    }
}