<?php
class ProductController
{
    // Hiển thị trang danh sách sản phẩm
    function index()
    {
        $categoryRepository = new CategoryRepository();
        $categories = $categoryRepository->getAll();
        $conds = [];
        $sorts = [];
        $page = $_GET['page'] ?? 1; //mặc định
        $item_per_page = 10; //10 sản phẩm mỗi trang
        $productRepository = new ProductRepository();

        // toán tử 3 ngôi rút gọn
        $category_id = $_GET['category_id'] ?? null;
        $category_name = 'Tất cả sản phẩm';
        if ($category_id) {
            $conds = [
                'category_id' => [
                    'type' => '=',
                    'val' => $category_id
                ]
            ];
            // SELECT * FROM view_product WHERE category_id=3

            // $category_name sẽ dựa vào category_id
            $category = $categoryRepository->find($category_id);
            $category_name = $category->getName();
        }

        // price-range
        $price_range = $_GET['price-range'] ?? null;
        if ($price_range) {
            $temp = explode('-', $price_range);
            $start = $temp[0];
            $end = $temp[1];
            $conds = [
                'sale_price' => [
                    'type' => 'BETWEEN',
                    'val' => "$start AND $end"
                ]
            ];
            //SELECT * FROM view_product WHERE sale_price BETWEEN 100000 AND 200000
            if ($end == 'greater') {
                $conds = [
                    'sale_price' => [
                        'type' => '>=',
                        'val' => $start
                    ]
                ];
            }
            //SELECT * FROM view_product WHERE sale_price >= 1000000
        }

        // search
        $search = $_GET['search'] ?? null;
        if ($search) {
            $conds = [
                'name' => [
                    'type' => 'LIKE',
                    'val' => "'%$search%'"
                ]
            ];
            //SELECT * FROM view_product WHERE name LIKE '%kem%'
        }

        // sort
        $sort = $_GET['sort'] ?? null;
        if ($sort) {
            $temp = explode('-', $sort);
            $order = $temp[1];
            $fakeCol = $temp[0];
            $map = ['price' => 'sale_price', 'alpha' => 'name', 'created' => 'created_date'];
            $realCol = $map[$fakeCol];
            $sorts = [
                $realCol => $order
            ];

            // SELECT * FROM view_product ORDER BY name asc
        }

        $products = $productRepository->getBy($conds, $sorts, $page, $item_per_page);

        // Phân trang
        $totalProducts = $productRepository->getBy($conds, $sorts);
        // 24 sản phẩm
        // mỗi trang 10 sản phẩm
        // totalPage: 24/10 làm trên là 3
        $totalPage = ceil(count($totalProducts) / $item_per_page);
        require 'view/product/index.php';
    }

    function detail()
    {
        $categoryRepository = new CategoryRepository();
        $categories = $categoryRepository->getAll();
        $id = $_GET['id'];

        $productRepository = new ProductRepository();
        $product = $productRepository->find($id);
        $category_id = $product->getCategoryId();
        $conds = [
            'category_id' => [
                'type' => '=',
                'val' => $category_id
            ],
            'id' => [
                'type' => '!=',
                'val' => $id
            ]
        ];
        $sorts = [];
        $page = 1;
        $item_per_page = 10;
        $relatedProducts = $productRepository->getBy($conds, $sorts, $page, $item_per_page);
        // SELECT * FROM view_product WHERE category_id=3 AND id != 2;
        require 'view/product/detail.php';
    }

    function storeComment()
    {
        $data = [];
        $data["email"] = $_POST['email'];
        $data["fullname"] = $_POST['fullname'];
        $data["star"] = $_POST['rating'];
        $data["created_date"] = date('Y-m-d H:i:s');
        $data["description"] = $_POST['description'];
        $data["product_id"] = $_POST['product_id'];
        $commmentRepository = new CommentRepository();
        $commmentRepository->save($data);
        // Gởi danh sách comment của product này về cho trình duyệt
        $productRepository = new ProductRepository();
        $product = $productRepository->find($data["product_id"]);
        require 'view/product/commentList.php';
    }

    function ajaxSearch() {
        // search
        $search = $_GET['pattern'] ?? null;
        if ($search) {
            $conds = [
                'name' => [
                    'type' => 'LIKE',
                    'val' => "'%$search%'"
                ]
            ];
            //SELECT * FROM view_product WHERE name LIKE '%kem%'
        }
        $productRepository = new ProductRepository();
        $products = $productRepository->getBy($conds, [], 1, 100);
        require 'view/product/ajaxSearch.php';
    }
}