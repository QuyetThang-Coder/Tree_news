<?php 
    session_start();
?>
<?php
    class CartController extends BaseController
    {
        private $cartModel;
        /* Khởi tạo */
        public function __construct()
        {
            $this -> loadModel('CategoryModel');
            $this -> categoryModel = new CategoryModel;

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
            $user = $this -> loginModel -> getUser($id_user);
            $sum_cart = $this -> cartModel -> sum_cart($id_user);
            $allCart = $this -> cartModel -> allCart($id_user);
            
            unset($_SESSION["error"]);
            unset($_SESSION["sale_price"]);
            return $this -> view('frontend.cart.show',
                                [
                                    'category' => $category,
                                    'user'     => $user,
                                    'sum_cart'   => $sum_cart,
                                    'allCart'   => $allCart,
                                ]);
        }

        public function delete_cart1()
        {
            $id_product = $_GET["product"];
            $id_user = $_COOKIE['id_user'] ?? NULL;

            $delete = $this -> cartModel -> deleteCart($id_product,$id_user);
            header('Location: ' . $_SERVER['HTTP_REFERER']);

        }

        public function delete_cart()
        {
            $id_product = $_POST["id"];
            $id_user = $_COOKIE['id_user'] ?? NULL;
            
            $delete = $this -> cartModel -> deleteCart($id_product,$id_user);
            if(!$delete) {
                echo "Thành công";
                return;
            }
            echo "Thất bại";
            return;

        }

        public function update_cart1() {
            $id_product = $_POST["id_product"];
            $quantity = $_POST["quantity"];
            $id_user = $_SESSION['id_user'] ?? NULL;

            $update = $this -> cartModel -> updateCart($id_product, $id_user, $quantity);
            header('Location: ' . $_SERVER['HTTP_REFERER']);
        }

        public function update_cart() {
            $id_product = $_POST["id"];
            $quantity = $_POST["quantity"];
            $id_user = $_COOKIE['id_user'] ?? NULL;

            $update = $this -> cartModel -> updateCart($id_product, $id_user, $quantity);
            if(!$update) {
                echo "Thành công";
                return;
            }
            echo "Thất bại";
            return;
        }
        
    }