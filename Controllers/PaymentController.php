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

        public function update_payment_status() {
            
        }

        public function update_order($name, $phone, $email, $address,$city , $txt_city, $district, $txt_district, $ward, $txt_ward, $redirect, $sale_price, $sum_price, $sale_name)
        {
            $id_user = $_COOKIE['id_user'] ?? NULL;
            if (empty($name) || empty($phone) || empty($address) || empty($city) || empty($district) || empty($district) || empty($ward) || empty($redirect)) {
                $_SESSION["error"] = "Đặt hàng thất bại vui lòng nhập đầy đủ các trường";
                header('Location: ' . $_SERVER['HTTP_REFERER']);
            } else if (!empty($name) && !empty($phone) && !empty($address) && !empty($city) && !empty($district) && !empty($district) && !empty($ward) && !empty($redirect)) {
                $data = [
                    "id_user" => $id_user,
                    "order_name" => $name,
                    "order_phone" => $phone,
                    "order_email" => $email,
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
                return $order;
                // header('Location: ' . $_SERVER['HTTP_REFERER']);
            }
        }

        public function order()
        {
            $id_user = $_COOKIE['id_user'] ?? NULL;

            $name = $_POST["txtName"];
            $phone = $_POST["txtPhone"];
            $email = $_POST["txtEmail"];
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

            $update = $this -> update_order($name, $phone, $email, $address,$city , $txt_city, $district, $txt_district, $ward, $txt_ward, $redirect, $sale_price, $sum_price, $sale_name);
            if(isset($update)) {
                if($redirect == "online") {
                    $vnp_Url = "https://sandbox.vnpayment.vn/paymentv2/vpcpay.html";
                    $vnp_Returnurl = "http://tree.test/?index.php&controller=payment&action=check_payment";
                    $vnp_TmnCode = "B3I6753X";//Mã website tại VNPAY 
                    $vnp_HashSecret = "QBIKHYEIQHVGOCZJWTUMBEGVOIMAOCXP"; //Chuỗi bí mật

                    $vnp_TxnRef = implode('',$update); //Mã đơn hàng. Trong thực tế Merchant cần insert đơn hàng vào DB và gửi mã này sang VNPAY
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
                    $returnData = array('code' => '00', 'message' => 'success', 'data' => $vnp_Url);
                        if (isset($_POST['payUrl'])) {
                            header('Location: ' . $vnp_Url);
                        } else {
                            echo json_encode($returnData);
                        }
                    
                } else {
                    $this -> send_mail(implode('',$update));
                    header('Location: /thong-tin-don-hang-' . implode('',$update));
                    // header('Location: ' . $_SERVER['HTTP_REFERER']);
                }
            }
        }

        public function check_payment() {
            $id = $_GET['vnp_TxnRef'];

            if($_GET['vnp_TransactionStatus'] == '00') {
                $payment_status =  [
                    "payment_status"  => '1',
                ];
            } else {
                $payment_status =  [
                    "payment_status"  => '0',
                ];
            }
            
            $update = $this -> orderModel -> updateOrder($id, $payment_status);
            $this -> send_mail($id);
            header('Location: /thong-tin-don-hang-' . $id);
			
        }

        public function send_mail($id) {
            require "./PHPMailer/src/PHPMailer.php"; 
            require "./PHPMailer/src/SMTP.php"; 
            require './PHPMailer/src/Exception.php'; 
            $mail = new PHPMailer\PHPMailer\PHPMailer(true);
            $email = $this -> orderModel -> findOrder_user($id);
            $emails = '';
            foreach ($email as $item) {
                $emails = $item['order_email'];
            }
            try {
                $mail->SMTPDebug = 0;  // 0,1,2: chế độ debug
                $mail->isSMTP();  
                $mail->CharSet  = "utf-8";
                $mail->Host = 'smtp.gmail.com'; //địa chỉ server
                $mail->SMTPAuth = true; 
                $tennguoigui = 'Cây cảnh store'; //Nhập tên người gửi
                $mail->Username='caycanhstore.com@gmail.com';
                $mail->Password = 'tylm zhoq zgcn ivwk'; // mật khẩu ứng dụng
                $mail->SMTPSecure = 'ssl';   
                $mail->Port = 465;              
                $mail->setFrom('caycanhstore.com@gmail.com'); 
                $mail->addAddress($emails); //mail người nhận  
                $mail->isHTML(true);  
                $mail->Subject = 'Thông báo đơn hàng';      
                // $mail->Body = nl2br("Đơn hàng thành công"); //nội dung thư
                $mail->Body = $this -> content_email($id); //nội dung thư
                $mail->smtpConnect( array("ssl" => array(
                    "verify_peer" => false,
                    "verify_peer_name" => false,
                    "allow_self_signed" => true
            )));
            $mail->send();
            echo 'Đã gửi mail xong';
            } catch (Exception $e) {
                echo 'Mail không gửi được. Lỗi: ', $mail->ErrorInfo;
            }
        }

        public function content_email($id) {
            $content = "";
            $order = $this -> orderModel -> findOrder_user($id);
            $order_detail = $this -> orderModel -> findOrder_detail($id);
            $content .= "<p>- Thông tin đơn hàng</p>";
            
            $id_order = "";
            $name_order = "";
            $phone_order = "";
            $email_order = "";
            $location_order = "";
            $payment = "";
            $sale = "";
            $sum = "";
            $payment_status = "";
            $date_order = "";
            foreach ($order as $item) {
                $address = strstr ($item['order_address'],"-, ", true);

                $address_1 = strchr($item['order_address'],"-, ");
                $dele_ward = trim($address_1,"-, ");
                $address_ward = strstr ($dele_ward,"-, ", true);
                $ward_txt = strstr ($address_ward,".", true);
                $ward = strstr ($address_ward,".");
                $ward_id = trim($ward,".");

                $address_2 = strchr($dele_ward, "-, ");
                $dele_district = trim($address_2,"-, ");
                $address_district = strstr ($dele_district,"-, ", true);
                $district_txt = strstr ($address_district,".", true);
                $district = strstr ($address_district,".");
                $district_id = trim($district,".");

                $address_3 = strchr($dele_district, "-, ");
                $address_city = trim($address_3,"-, ");
                $city_txt = strstr ($address_city,".", true);
                $city = strstr ($address_city,".");
                $city_id = trim($city,".");


                $id_order = $item['id'];
                $name_order = $item['order_name'];
                $phone_order = $item['order_phone'];
                $email_order = $item['order_email'];
                $location_order = $address.', '.$ward_txt.', '.$district_txt.', '.$city_txt;
                $sale = $item["sale_price"];
                $payment = $item['payment'];
                $payment_status = $item['payment_status'];
                $sum = $item['order_price'];
                $date_order = $item['order_date'];
            }
            $content .= "<table style='border: 1px #ccc solid; border-collapse: collapse;'>";
            $content .= "<tr>";
                    $content .= "<td style='border: 1px #ccc solid;  padding: 4px 14px;'>Mã đơn hàng</td>";
                    $content .= "<td style='border: 1px #ccc solid;  padding: 4px 14px;'>$id_order</td>";
                $content .= "</tr>";
                $content .= "<tr>";
                    $content .= "<td style='border: 1px #ccc solid;  padding: 4px 14px;'>Họ tên</td>";
                    $content .= "<td style='border: 1px #ccc solid;  padding: 4px 14px;'>$name_order</td>";
                $content .= "</tr>";
                $content .= "<tr>";
                    $content .= "<td style='border: 1px #ccc solid;  padding: 4px 14px;'>Số điện thoại</td>";
                    $content .= "<td style='border: 1px #ccc solid;  padding: 4px 14px;'>$phone_order</td>";
                $content .= "</tr>";
                $content .= "<tr>";
                    $content .= "<td style='border: 1px #ccc solid;  padding: 4px 14px;'>Email</td>";
                    $content .= "<td style='border: 1px #ccc solid;  padding: 4px 14px;'>$email_order</td>";
                $content .= "</tr>";
                $content .= "<tr>";
                    $content .= "<td style='border: 1px #ccc solid;  padding: 4px 14px;'>Địa chỉ</td>";
                    $content .= "<td style='border: 1px #ccc solid;  padding: 4px 14px;'>$location_order</td>";
                $content .= "</tr>";
                if ($payment == "cod") {
                    $content .= "<tr>";
                        $content .= "<td style='border: 1px #ccc solid;  padding: 4px 14px;'>Hình thức thanh toán</td>";
                        $content .= "<td style='border: 1px #ccc solid;  padding: 4px 14px;'>Thanh toán khi nhận hàng</td>";
                    $content .= "</tr>";
                } else {
                    $content .= "<tr>";
                        $content .= "<td style='border: 1px #ccc solid;  padding: 4px 14px;'>Hình thức thanh toán</td>";
                        $content .= "<td style='border: 1px #ccc solid;  padding: 4px 14px;'>Thanh toán online ".$payment_status = 1 ? '(Đã thanh toán)':'Chưa thanh toán'."</td>";
                    $content .= "</tr>";
                }
            
                $content .= "<tr>";
                    $content .= "<td style='border: 1px #ccc solid;  padding: 4px 14px;'>Ngày đặt</td>";
                    $content .= "<td style='border: 1px #ccc solid;  padding: 4px 14px;'>".date_format(date_create($date_order), "d-m-Y H:i:s")."</td>";
                $content .= "</tr>";
            $content .= "</table>";
            $content .= "<br>";

            $content .= "<p>- Danh sách sản phẩm</p>";
            $content .= "<table style='border: 1px #ccc solid; border-collapse: collapse;'>";
                $content .= "<tr>";
                    $content .= "<th style='border: 1px #ccc solid;  padding: 4px 14px; color: #fff; background-color: #00b214'>STT</th>";
                    $content .= "<th style='border: 1px #ccc solid;  padding: 4px 14px; color: #fff; background-color: #00b214'>Tên sản phẩm</th>";
                    $content .= "<th style='border: 1px #ccc solid;  padding: 4px 14px; color: #fff; background-color: #00b214'>Đơn giá</th>";
                    $content .= "<th style='border: 1px #ccc solid;  padding: 4px 14px; color: #fff; background-color: #00b214'>Số lượng</th>";
                    $content .= "<th style='border: 1px #ccc solid;  padding: 4px 14px; color: #fff; background-color: #00b214'>Thành tiền</th>";
                $content .= "</tr>";
                
                $i = 0;
                $sum_price = 0;
                foreach($order_detail as $item) {
                    $i += 1;
                    $sum_price += $item["price_product"];
                    $content .= "<tr style='text-align: center'>";
                        $content .= "<td style='border: 1px #ccc solid;  padding: 4px 14px;'>$i</td>";
                        $content .= "<td style='border: 1px #ccc solid;  padding: 4px 14px;'>".$item['name_product']."</td>";
                        $content .= "<td style='border: 1px #ccc solid;  padding: 4px 14px;'>".number_format($item['price_product'] / $item['quantity_product'])."</td>";
                        $content .= "<td style='border: 1px #ccc solid;  padding: 4px 14px;'>".$item['quantity_product']."</td>";
                        $content .= "<td style='border: 1px #ccc solid;  padding: 4px 14px;'>".number_format($item['price_product'])." vnđ</td>";
                    $content .= "</tr>";
                }

                $content .= "<tr>";
                    $content .= "<td style='text-align: right; border: 1px #ccc solid;  padding: 4px 14px;' colspan='4'><b>Tạm tính</b></td>";
                    $content .= "<td style='text-align: center; border: 1px #ccc solid;  padding: 4px 14px;'>".number_format($sum_price)." vnđ</td>";
                $content .= "</tr>";
                $content .= "<tr>";
                    $content .= "<td style='text-align: right; border: 1px #ccc solid;  padding: 4px 14px;' colspan='4'><b>Phí vận chuyển</b></td>";
                    $content .= "<td style='text-align: center; border: 1px #ccc solid;  padding: 4px 14px;'>30,000 vnđ</td>";
                $content .= "</tr>";
                $content .= "<tr>";
                    $content .= "<td style='text-align: right; border: 1px #ccc solid;  padding: 4px 14px;' colspan='4'><b>Giảm giá</b></td>";
                    $content .= "<td style='text-align: center; border: 1px #ccc solid;  padding: 4px 14px;'>- ".number_format($sale)." vnđ</td>";
                $content .= "</tr>";
                $content .= "<tr>";
                    $content .= "<td style='text-align: right; border: 1px #ccc solid;  padding: 4px 14px;' colspan='4'><b>Tổng cộng</b></td>";
                    $content .= "<td style='text-align: center; border: 1px #ccc solid;  padding: 4px 14px;'>".number_format($sum)." vnđ</td>";
                $content .= "</tr>";
            $content .= "</table>";
            return $content;

        }
        
    }