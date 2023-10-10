<?php 
    session_start();
?>

<?php
    class PaymentController extends BaseController
    {
        private $paymentModel;
        /* Khởi tạo */
        public function __construct()
        {

            $this -> loadModel('CartModel');
            $this -> cartModel = new CartModel;

            $this -> loadModel('PaymentModel');
            $this -> paymentModel = new PaymentModel;

            $this -> loadModel('LoginModel');
            $this -> loginModel = new LoginModel;

            $this -> loadModel('SaleModel');
            $this -> saleModel = new SaleModel;

            $this -> loadModel('OrderModel');
            $this -> orderModel = new OrderModel;

            $this -> loadModel('ProductModel');
            $this -> productModel = new ProductModel;
        }

        public function show() 
        {
            $id_user = $_COOKIE['id_user'] ?? NULL;

            $user = $this -> loginModel -> getUserById($id_user);
            $allCart = $this -> cartModel -> allCart($id_user);
            $CountSale = $this -> saleModel -> CountSale();
            $getSale = $this -> saleModel -> getAll();
            $sum_cart = $this -> cartModel -> sum_cart($id_user);

            return $this -> view('frontend.payment.show',
                                [
                                    'user'     => $user,
                                    'allCart'  => $allCart,
                                    'CountSale'  => $CountSale,
                                    'getSale'  => $getSale,
                                    'sum_cart' => $sum_cart,
                                ]);
        }

        public function checkSale()
        {
            $name_sale = $_POST['sale'] ?? NULL;
            $price_feeship = $_POST['price_feeship'] ?? NULL;

            $sale = $this -> saleModel -> findSale($name_sale);

            if ($sale == 0) {
                unset($_SESSION["sale_price"]);
                unset($_SESSION["sale_name"]);
                echo "Mã giảm giá không hợp lệ";
            } else {
                foreach ($sale as $sale) {
                    if ($sale["sale_rule"] == "0") {
                        if ($sale["sale_remain"] < 1) {
                            unset($_SESSION["sale_price"]);
                            unset($_SESSION["sale_name"]);
                            echo "Số lượng mã giảm giá đã hết";
                        } else {
                            $_SESSION["sale_price"] = $sale["sale_price"];
                            $_SESSION["sale_name"] = $sale["sale_name"];
                            echo "0";
                        }
                    }
                    else if ($sale["sale_rule"] != "0") {
                        if ($price_feeship < $sale["sale_rule"]) {
                            unset($_SESSION["sale_price"]);
                            unset($_SESSION["sale_name"]);
                            echo "Đơn hàng tối thiểu ".number_format($sale["sale_rule"])." vnđ";
                        } else if ($price_feeship >= $sale["sale_rule"]) {
                            if ($sale["sale_remain"] < 1) {
                                unset($_SESSION["sale_price"]);
                                unset($_SESSION["sale_name"]);
                                echo "Số lượng mã giảm giá đã hết";
                            } else {
                                $_SESSION["sale_price"] = $sale["sale_price"];
                                $_SESSION["sale_name"] = $sale["sale_name"];
                                echo "0";
                            }
                        }
                    }
                }
                // echo "0";
            }
        }

        public function update_order($name, $phone, $address,$city , $txt_city, $district, $txt_district, $ward, $txt_ward, $redirect, $sale_price, $sum_price, $sale_name)
        {
            $id_user = $_COOKIE['id_user'] ?? NULL;
            if (empty($name) || empty($phone) || empty($address) || empty($city) || empty($district) || empty($district) || empty($ward) || empty($redirect)) {
                die;
                $_SESSION["error"] = "Đặt hàng thất bại vui lòng nhập đầy đủ các trường";
                header('Location: ' . $_SERVER['HTTP_REFERER']);
            } else if (!empty($name) && !empty($phone) && !empty($address) && !empty($city) && !empty($district) && !empty($district) && !empty($ward) && !empty($redirect)) {
                $data = [
                    "id_user" => $id_user,
                    "order_name" => $name,
                    "order_phone" => $phone,
                    "order_address" => $address.'-, '.$txt_ward.'.'.$ward.'-, '.$txt_district.'.'.$district.'-, '.$txt_city.'.'.$city,
                    "sale_price" => $sale_price,
                    "order_price" => $sum_price,
                    "payment" => $redirect,
                ];
                $order = $this -> orderModel -> insertData($data);
                $allCart = $this -> cartModel -> allCart($id_user);
                foreach ($allCart as $allCart) {
                    $data_amount_product =  [
                        "amount_product"  => $allCart["amount_product"] - $allCart["quantity"],
                    ];
                    $data_detail = [
                        "id_order" => implode('',$order),
                        "id_product" => $allCart["id_product"],
                        "name_product" => $allCart["name_product"],
                        "image_product" => $allCart["image_product"],
                        "quantity_product" => $allCart["quantity"],
                        "price_product" => $allCart["price_product"] * $allCart["quantity"],
                    ];
                    $order_detail = $this -> orderModel -> inserOrderDetail($data_detail);
                    $dele_cart = $this -> cartModel -> deleteCart($allCart["id_product"],$id_user);
                    $update_amount = $this -> productModel -> updateData($allCart["id_product"], $data_amount_product);
                    if ($sale_name != "" && isset($sale_name)) {
                        $sale = $this -> saleModel -> findSale($sale_name);
                        if($sale != 0) {
                            foreach ($sale as $sale) {
                                $data_sale =  [
                                    "sale_remain"  => $sale["sale_remain"] - 1,
                                ];
                                $update_sale = $this -> saleModel -> updateData($sale["id_sale"], $data_sale);
                            }
                        }
                        
                    }
                }
                unset($_SESSION["error"]);
                unset($_SESSION["sum_price"]);
                unset($_SESSION["sale_name"]);
                header('Location: ' . $_SERVER['HTTP_REFERER']);
            }
        }

        public function order()
        {
            $id_user = $_COOKIE['id_user'] ?? NULL;

            $name = $_POST["txtName"];
            $phone = $_POST["txtPhone"];
            $address = $_POST["txtAddress"];
            $city = $_POST["slCity"];
            $txt_city = $_POST["txtCity"];
            $district = $_POST["slDistrict"];
            $txt_district = $_POST["txtDistrict"];
            $ward = $_POST["slWard"];
            $txt_ward = $_POST["txtWard"];
            $redirect = $_POST["redirect"];
            $sale_price = $_POST["sale_price"];
            $sum_price = $_SESSION["sum_price"];
            $sale_name = $_SESSION["sale_name"] ?? NULL;


            if($redirect == "online") {
                $vnp_Url = "https://sandbox.vnpayment.vn/paymentv2/vpcpay.html";
                $vnp_Returnurl = "http://tree.test/";
                $vnp_TmnCode = "CGXZLS0Z";//Mã website tại VNPAY 
                $vnp_HashSecret = "XNBCJFAKAZQSGTARRLGCHVZWCIOIGSHN"; //Chuỗi bí mật

                $vnp_TxnRef = time(); //Mã đơn hàng. Trong thực tế Merchant cần insert đơn hàng vào DB và gửi mã này sang VNPAY
                $vnp_OrderInfo = "Thanh toán VNPAY";
                $vnp_OrderType = "VNPAY";
                $vnp_Amount = $sum_price * 100;
                $vnp_Locale = "vn";
                $vnp_BankCode = "NCB";
                $vnp_IpAddr = $_SERVER['REMOTE_ADDR'];

                $inputData = array(
                    "vnp_Version" => "2.1.0",
                    "vnp_TmnCode" => $vnp_TmnCode,
                    "vnp_Amount" => $vnp_Amount,
                    "vnp_Command" => "pay",
                    "vnp_CreateDate" => date('YmdHis'),
                    "vnp_CurrCode" => "VND",
                    "vnp_IpAddr" => $vnp_IpAddr,
                    "vnp_Locale" => $vnp_Locale,
                    "vnp_OrderInfo" => $vnp_OrderInfo,
                    "vnp_OrderType" => $vnp_OrderType,
                    "vnp_ReturnUrl" => $vnp_Returnurl,
                    "vnp_TxnRef" => $vnp_TxnRef
                   
                );

                if (isset($vnp_BankCode) && $vnp_BankCode != "") {
                    $inputData['vnp_BankCode'] = $vnp_BankCode;
                }

                //var_dump($inputData);
                ksort($inputData);
                $query = "";
                $i = 0;
                $hashdata = "";
                foreach ($inputData as $key => $value) {
                    if ($i == 1) {
                        $hashdata .= '&' . urlencode($key) . "=" . urlencode($value);
                    } else {
                        $hashdata .= urlencode($key) . "=" . urlencode($value);
                        $i = 1;
                    }
                    $query .= urlencode($key) . "=" . urlencode($value) . '&';
                }

                $vnp_Url = $vnp_Url . "?" . $query;
                if (isset($vnp_HashSecret)) {
                    $vnpSecureHash =   hash_hmac('sha512', $hashdata, $vnp_HashSecret);//  
                    $vnp_Url .= 'vnp_SecureHash=' . $vnpSecureHash;
                }
                $returnData = array('code' => '00'
                    , 'message' => 'success'
                    , 'data' => $vnp_Url);
                    $update = $this -> update_order($name, $phone, $address,$city , $txt_city, $district, $txt_district, $ward, $txt_ward, $redirect, $sale_price, $sum_price, $sale_name);
                    if (isset($_POST['payUrl'])) {
                        header('Location: ' . $vnp_Url);
                        die();
                    } else {
                        echo json_encode($returnData);
                    }
                
            }
            
            $update = $this -> update_order($name, $phone, $address,$city , $txt_city, $district, $txt_district, $ward, $txt_ward, $redirect, $sale_price, $sum_price, $sale_name);
        }
        
    }