
<!-- Body -->
<div id="cart">
    <div class="cart">
        <!--  -->
        <div class="cart-page">
            <?php 
                if (isset($_COOKIE["id_user"])) {
                    foreach ($sum_cart as $sum_cart) {
                        if ($sum_cart["sum"] == 0) {
            ?>
                            <div class="cart-not_product">
                                <p>Không có sản phẩm nào trong giỏ hàng</p>
                                <div>
                                    <a href="/danh-muc-san-pham">Xem sản phẩm</a>
                                </div>
                            </div>
            <?php
                        } else {
            ?>
                            <div class="container">
                                <div class="cart-page-title">
                                    <h4 class="h4">Giỏ hàng</h4>
                                </div>
                                <div class="cart-page-table">
                                    <table class="table_cart">
                                        <tr width="100%" class="table-fixed-top" >
                                            <th width="5%">#</th>
                                            <th width="10%">Ảnh</th>
                                            <th width="20%">Tên sản phẩm</th>
                                            <th width="16%">Đơn giá</th>
                                            <th width="20%">Số lượng</th>
                                            <th width="16%">Thành tiền</th>
                                            <th width="8%">Xóa</th>
                                        </tr>

                                        
                                        <?php
                                            $i = 1; 
                                            $price = 0;
                                            foreach ($allCart as $allCart) {
                                        ?>
                                            <!-- <form action="index.php?controller=cart&action=update_cart" method="POST"> -->
                                                <tr class="table-border-bottom tr_<?php echo $allCart['id']; ?>">
                                                    <td><?php echo $i++ ?></td>
                                                    <td><img src="admin/Public/uploads/<?php echo $allCart["image_product"] ?>" alt="<?php echo $allCart["name_product"] ?>"></td>
                                                    <td><?php echo $allCart["name_product"] ?></td>
                                                    <td>
                                                        <?php
                                                            if ($allCart['discount_product'] == 0) {
                                                        ?>
                                                                <?php echo number_format($allCart['price_product']) ?> ₫
                                                        <?php
                                                            } else {
                                                        ?>
                                                                <?php echo  number_format($allCart['discount_product']) ?> ₫
                                                        <?php
                                                            }
                                                        ?>
                                                    </td>
                                                    <td>
                                                        <div class="quantity">
                                                            <input type="number" class="txt_quantity_<?php echo $allCart['id']; ?>" name="quantity" min="1" max="<?php echo $allCart["amount_product"] ?>" step="1" readonly value="<?php echo $allCart["quantity"] ?>">
                                                            <div class="quantity-nav">
                                                                <div class="quantity-button quantity-up" onclick="quantity_up(<?php echo $allCart['id']; ?>,<?php echo $allCart['quantity']; ?>,<?php echo $allCart['amount_product']; ?>)">+</div>
                                                                <div class="quantity-button quantity-down" onclick="quantity_down(<?php echo $allCart['id']; ?>,<?php echo $allCart['quantity']; ?>,<?php echo $allCart['amount_product']; ?>)">-</div>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <?php 
                                                            if ($allCart['discount_product'] == 0) {
                                                        ?>
                                                                <?php $price += ($allCart['price_product'] * $allCart['quantity']); echo number_format($allCart['price_product'] * $allCart['quantity']) ?> ₫
                                                        <?php
                                                            } else {
                                                        ?>
                                                                <?php $price += ($allCart['discount_product'] * $allCart['quantity']); echo number_format($allCart['discount_product'] * $allCart['quantity']) ?> ₫
                                                        <?php
                                                            }
                                                        ?>
                                                    </td>
                                                    <td>
                                                        <!-- <input type="submit" value="Xóa"> -->
                                                        <span class="btn btn-danger" onclick="delete_product_cart(<?php echo $allCart['id']; ?>)">
                                                            <i class="bi bi-trash3"></i>
                                                        </span>
                                                    </td>
                                                </tr>
                                            <!-- </form> -->
                                        <?php
                                            }
                                        ?>
                                    

                                        <tr class="table-fixed-bottom">
                                            <td colspan="5" class="sum">Tạm tính:</td>
                                            <td colspan="2" class="price"><?php echo number_format($price) ?> vnđ</td>
                                        </tr>
                                    </table>
                                </div>
                                
                                <!--  -->
                                <div class="cart-bottom">
                                    <div class="cart-left">
                                        <a href="/danh-muc-san-pham">Tiếp tục xem sản phẩm</a>
                                    </div>
                                    <div class="cart-right">
                                        <a href="/dat-hang">Thanh toán</a>
                                    </div>
                                </div>
                            </div>
            <?php    
                        }
                    }
                } else {
            ?>
                    <div class="cart-not_product">
                        <p>Vui lòng đăng nhập</p>
                        <div>
                            <a href="/dang-nhap">Đăng nhập</a>
                        </div>
                    </div>
            <?php
                }
            ?>
            
            <!-- <div class="cart-not_product">
                <p>Không có sản phẩm nào trong giỏ hàng</p>
                <div>
                    <a href="product.html">Xem sản phẩm</a>
                </div>
            </div> -->
        </div>
        
        <!--  -->
        <div class="home-bottom">
            <div class="container">
                <div class="home-bottom-share">
                    <a href="mailto:caycanhstore@gmail.com"><h6>Chia sẻ <i class="bi bi-envelope-fill"></i></h6></a>
                </div>
                <div class="home-bottom-content">
                    <div class="row">
                        <div class="col col-xl-3 home-bottom-content-item">
                            <div class="home-bottom-content-item-left">
                                <img src="admin/Public/uploads/ic1.jpg" alt="">
                            </div>
                            <div class="home-bottom-content-item-right">
                                <h6>Chất lượng hàng đầu</h6>                                       
                                <p>Cam kết tất cả sản phẩm chính hãng 100%</p>                                       
                            </div>
                        </div>
                        <div class="col col-xl-3 home-bottom-content-item">
                            <div class="home-bottom-content-item-left">
                                <img src="admin/Public/uploads/ic2.jpg" alt="">
                            </div>
                            <div class="home-bottom-content-item-right">
                                <h6>Giao hàng siêu nhanh</h6>                                       
                                <p>Chúng tôi cam kết giao hàng nhanh nhất đến khách hàng</p>                                       
                            </div>
                        </div>
                        <div class="col col-xl-3 home-bottom-content-item">
                            <div class="home-bottom-content-item-left">
                                <img src="admin/Public/uploads/ic3.jpg" alt="">
                            </div>
                            <div class="home-bottom-content-item-right">
                                <h6>Mua hàng tiết kiệm</h6>                                       
                                <p>Giảm giá & khuyến mại với ưu đãi cực lớn</p>                                       
                            </div>
                        </div>
                        <div class="col col-xl-3 home-bottom-content-item">
                            <div class="home-bottom-content-item-left">
                                <img src="admin/Public/uploads/ic4.jpg" alt="">
                            </div>
                            <div class="home-bottom-content-item-right">
                                <h6>Hỗ trợ online 24/7</h6>                                       
                                <p>Gọi ngay 0946.312.559 để được tư vấn</p>                                       
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="Public/frontend/js/quantity.js"></script>
