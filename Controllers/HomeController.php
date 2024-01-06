<?php 
    session_start();
?>
<?php
    class HomeController extends BaseController
    {
        private $homeModel;
        private $categoryModel;
        private $bannerModel;
        private $productModel;
        private $loginModel;
        private $cartModel;
        /* Khởi tạo */
        public function __construct()
        {
            $this -> loadModel('CategoryModel');
            $this -> categoryModel = new CategoryModel;

            $this -> loadModel('BannerModel');
            $this -> bannerModel = new BannerModel;

            $this -> loadModel('ProductModel');
            $this -> productModel = new ProductModel;

            $this -> loadModel('LoginModel');
            $this -> loginModel = new LoginModel;

            $this -> loadModel('CartModel');
            $this -> cartModel = new CartModel;
        }

        public function show() 
        {
            $phone = $_COOKIE['phone'] ?? NULL;
            $id_user = $_COOKIE['id_user'] ?? NULL;

            $category = $this -> categoryModel -> getAllSql();
            $newproduct = $this -> productModel -> getNewProduct();
            $user = $this -> loginModel -> getUser($id_user);
            $sum_cart = $this -> cartModel -> sum_cart($id_user);
            $allCart = $this -> cartModel -> allCart($id_user);
            $banner = $this -> bannerModel -> getAll();
            $productSelling = $this -> productModel -> productSelling();
            
            unset($_SESSION["error"]);
            unset($_SESSION["sale_price"]);
            return $this -> view('frontend.home.show',
                                [
                                    'category'   => $category,
                                    'banner'     => $banner,
                                    'newproduct' => $newproduct,
                                    'user'       => $user,
                                    'sum_cart'   => $sum_cart,
                                    'allCart'   => $allCart,
                                    'productSelling'   => $productSelling,
                                ]);
        }

        public function autocomplete() {
            $keyword = $_POST['keywords'];
            if (empty($keyword)) {
                echo '';
                return;
            }
            $products = $this -> productModel -> search($keyword);
            if(!empty($products)) {
                $output = '';
                foreach($products as $key => $item) {
                    $output .= '
                    <div class="search-item">
                        <div class="row g-0">
                            <div class="col-lg-3 col-md-2 col-4 position-relative overflow-hidden">
                                <a href="" title="'.$item['name_product'].'">
                                    <img src="admin/Public/uploads/'.$item['image_product'].'" class="img-fluid img-square rounded" alt="'.$item['name_product'].'">
                                </a>
                            </div>
                            <div class="col-lg-9 col-md-10 col-8">
                                <div class="ps-3">
                                    <h5 class="search-title mb-1">
                                        <a href="" title="'.$item['name_product'].'" class="link-dark text-decoration-none title-right-line">'.$item['name_product'].'</a>
                                    </h5>
                                    <p class="search-summary card-text lh-sm summary-right-line">'.$item['short_description'].'</p>
                                </div>
                            </div>
                        </div>
                    </div>';
                }

                $output .= '';
                echo $output;
                return;
            }
            echo '';

        }
        
    }